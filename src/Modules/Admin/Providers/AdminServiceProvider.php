<?php

namespace Modules\Admin\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\Admin\Console\CreateUser;
use Modules\Admin\Console\GenerateDatabase;
use Modules\Admin\Console\InstallFirst;
use Modules\Admin\Console\VueCreatePage;
use Modules\Admin\Entities\Configuration;
use Modules\Admin\Http\Middleware\AdminAuthenticate;
use Modules\Admin\Http\Middleware\CreateThumbImage;
use Modules\Admin\Http\Middleware\EditTranslate;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Admin';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'admin';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        include_once __DIR__ . "/../Helpers/includes.php";
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
        $kernel = $this->app->make('Illuminate\Contracts\Http\Kernel');
        $kernel->appendMiddlewareToGroup('superadmin', AdminAuthenticate::class);
        $kernel->appendMiddlewareToGroup('web', EditTranslate::class);
        $kernel->appendMiddlewareToGroup('web', CreateThumbImage::class);
        $kernel->pushMiddleware('Modules\Admin\Http\Middleware\CreateThumbImage');
        $kernel->pushMiddleware('Modules\Admin\Http\Middleware\EditTranslate');
        if ($this->app->runningInConsole()) {
            $this->commands([
                VueCreatePage::class,
                GenerateDatabase::class,
                CreateUser::class
            ]);
        }
        Config::set('db',Configuration::getConfig());
        $meta = [
            "title" => config('db.title'),
            "description" => config('db.description'),
            "og:title" => config('db.title'),
            "og:description" => config('db.title')
        ];
        if(!empty(config('db.meta'))){
            $meta = array_merge($meta,config('db.meta'));
        }
        meta()->set($meta);
        Validator::extend('my_number', function ($attribute, $value) {

            // This will only accept alpha and spaces.
            // If you want to accept hyphens use: /^[\pL\s-]+$/u.
            return preg_match('/^[0-9\.\,]+$/u', $value);

        });
        if(App::isLocal()) {
            $adminMenu = config('admin.menu');
            $showDemo = true;
            foreach ($adminMenu as $key => $val) {
                if (isset($val["name"]) && $val["name"] == "demo") {
                    $showDemo = false;
                    break;
                }
            }
            if ($showDemo){
                    $adminMenu[] = [
                        'name' => 'demo',
                        'route' => 'demo',
                    ];
            }
            Config::set('admin.menu',$adminMenu);
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'), $this->moduleNameLower
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
            $this->loadJsonTranslationsFrom(module_path($this->moduleName, 'Resources/lang'));
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (\Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }
}
