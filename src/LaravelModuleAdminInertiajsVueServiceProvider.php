<?php
namespace Toanld\LaravelModuleAdminInertiajsVue;
use Illuminate\Support\ServiceProvider;
use Toanld\LaravelModuleAdminInertiajsVue\Commands\InstallAdminCommand;

class LaravelModuleAdminInertiajsVueServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands(InstallAdminCommand::class);
        }
    }
}
