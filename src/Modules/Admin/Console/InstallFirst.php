<?php

namespace Modules\Admin\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class InstallFirst extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->addComposer();
        $arrayComposer = [
            "nwidart/laravel-modules",
            "ryannielson/meta",
            "toanld/ziggy",
            "toanld/laravel-debug-to-sql",
            "toanld/modules-inertia",
            "toanld/multi-relationships",
            "inertiajs/inertia-laravel",
        ];
        $arrayNpm = [
            "@inertiajs/inertia",
            "@inertiajs/progress",
            "@inertiajs/server",
            "@inertiajs/vue3",
            "@popperjs/core",
            "pinia",
            "sass",
            "nprogress",
            "@tailwindcss/forms",
            "@tailwindcss/line-clamp",
            "tailwindcss",
            "lodash",
            "@tinymce/tinymce-vue@^5",
            "uuid",
            "vue-multiselect",
            "vue3-tags-input",
            "autoprefixer",
            "@vitejs/plugin-vue",
            "@vue/server-renderer",
            "vue@latest",
        ];
        foreach ($arrayComposer as $package){
            $this->info("composer require $package");
        }
        foreach ($arrayNpm as $package){
            $this->info("npm -D install $package");
        }
        $this->info('php artisan vendor:publish --provider="Nwidart\Modules\LaravelModulesServiceProvider"');
        $this->info("php artisan migrate");
        $this->info("php artisan module:seed");
        //kiểm tra xem đẵ đăng ký HandleInertiaRequests.php
        $path = base_path() . "/app/Http/Middleware/";
        if(!file_exists($path . "HandleInertiaRequests.php")){
            $this->info("Copy HandleInertiaRequests.php");
            copy(__DIR__ . "/../Stubs/HandleInertiaRequests.stub",$path . "HandleInertiaRequests.php");
        }
        //kiểm tra trong config/app.php đã có khai báo HandleInertiaRequests chua
        $appConfig = file_get_contents(base_path() . "/app/Http/Kernel.php");
        if(strpos($appConfig,"HandleInertiaRequests::class") === false){
            $appConfig = str_replace("'web' => [","'web' => [\n            \\App\\Http\\Middleware\\HandleInertiaRequests::class,\n",$appConfig);
            file_put_contents(base_path() . "/app/Http/Kernel.php",$appConfig);
        }

        if(!file_exists(base_path() . "/app/Traits/SingletonTrait.php")){
            if(!file_exists(dirname(base_path() . "/app/Traits/SingletonTrait.php"))) mkdir(dirname(base_path() . "/app/Traits/SingletonTrait.php"), 0777, true);
            copy(__DIR__ . "/../Stubs/SingletonTrait.stub",base_path() . "/app/Traits/SingletonTrait.php");
        }

        $path = base_path() . "/helpers/";
        if(!file_exists($path . "Myapp.php")){
            $this->info("Copy Myapp.php");
            if(!file_exists($path)) mkdir($path);
            copy(__DIR__ . "/../Stubs/Myapp.stub",$path . "Myapp.php");
        }
        if(!file_exists(base_path() . "/helpers/includes.php")){
            if(!file_exists(dirname(base_path() . "/helpers/includes.php"))) mkdir(dirname(base_path() . "/helpers/includes.php"), 0777, true);
            copy(__DIR__ . "/../Stubs/includes.stub",base_path() . "/helpers/includes.php");
        }

        $include = file_get_contents(base_path() . "/helpers/includes.php");
        if(strpos($include,"Myapp.php") === false){
            $include = str_replace('<?php','<?php' . "\n" . 'include_once __DIR__ . "/Myapp.php";' . "\n" ,$include);
            file_put_contents(base_path() . "/helpers/includes.php",$include);
        }

        $include = file_get_contents(base_path() . "/config/modules.php");
        if(strpos($include,"'source' => '") === false){
            $include = str_replace("'migration' => base_path('database/migrations'),","'source' => 'Resources/Vuejs/Pages'," . "\n" . "'migration' => base_path('database/migrations')," . "\n" ,$include);
            file_put_contents(base_path() . "/config/modules.php",$include);
        }



        $this->copyStub(__DIR__ . "/../Stubs/postcss.config.js",base_path() . "/postcss.config.js");
        $this->copyStub(__DIR__ . "/../Stubs/tailwind.config.js",base_path() . "/tailwind.config.js");

        $this->warn("Lưu ý sửa file vite.config.js dựa trên file Modules/Admin/Stubs/vite.config.stub");
        //
    }

    function copyStub($from,$to){
        if(file_exists($to)){
            $this->warn("Exists: $to");
            if($this->confirm('Bạn muốn copy đè file ' . $to . ' ? (yes|no)[no]',true))
            {
                if(!file_exists(dirname($to))) mkdir(dirname($to), 0777, true);
                unlink($to);
                copy($from,$to);
                return;
            }
        }else{
            if(!file_exists(dirname($to))) mkdir(dirname($to), 0777, true);
            copy($from,$to);
            $this->info("Copy: " . $to);
        }
    }

    public function addComposer(){
        // Đường dẫn tới tệp composer.json của bạn
        $composerFilePath = base_path() . '/composer.json';

        // Đọc nội dung từ tệp composer.json
        $composerData = json_decode(file_get_contents($composerFilePath), true);

        // Thêm mục autoload: files nếu nó chưa tồn tại
        if (!isset($composerData['autoload']['files'])) {
            $composerData['autoload']['files'] = [];
        }

        if(!in_array('helpers/includes.php',$composerData['autoload']['files'])){
            // Thêm các tệp bạn muốn autoload vào mục autoload: files
            $filesToAutoload = [
                'helpers/includes.php'
            ];
            $composerData['autoload']['files'] = array_merge($composerData['autoload']['files'], $filesToAutoload);
            // Ghi nội dung đã sửa vào tệp composer.json
            file_put_contents($composerFilePath, json_encode($composerData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        }



    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
           // ['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
           // ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
