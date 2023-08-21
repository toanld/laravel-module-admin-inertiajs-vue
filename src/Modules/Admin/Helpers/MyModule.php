<?php
class MyModule
{
    use \Modules\Admin\Helpers\SingletonTrait;
    protected $modules = false;
    protected $currentModule = null;
    protected $rootViewInetia = 'admin::layouts.app';
    function rootViewInetia($rootView = null){
        if(!empty($rootView)) $this->rootViewInetia = $rootView;
        return $this->rootViewInetia;
    }
    function getModules(){
        if($this->modules !== false) return $this->modules;
        $fileJson = __DIR__ . "/../../../modules_statuses.json";
        if(file_exists($fileJson)) {
            $modules = json_decode(file_get_contents($fileJson), true);
            if (!empty($modules)) {
                $this->modules = $modules;
                return $modules;
            }
        }
        $this->modules = [];
        return [];
    }
    public function getCurrentModule(){
        if(!empty($this->currentModule)) return $this->currentModule;
        $url = request()->url();
        $path = request()->path();
        $moduleRoutes = app('router')->getRoutes()->getRoutesByMethod()["GET"];
        foreach ($moduleRoutes as $route) {
            if($path == "/" || empty($path)){
                if ($route->uri == "/" || empty($route->uri)) {
                    $currentModule = explode("\\",$route->getActionName());
                    $this->currentModule = ($currentModule[0] == "Modules" &&  isset($currentModule[1])) ? $currentModule[1] : null;
                    break;
                }
            }
            if (str_contains($path,$route->uri) && $route->uri != "/") {
                $currentModule = explode("\\",$route->getActionName());
                $this->currentModule = ($currentModule[0] == "Modules" &&  isset($currentModule[1])) ? $currentModule[1] : null;
                break;
            }
        }
        return $this->currentModule;
    }
    function getMenus(){
        $modules = $this->getModules();
        $arrayMenu = [];
        foreach ($modules as $module => $status){
            $keyconfig = strtolower($module) . ".menu";
            if(!empty(config($keyconfig))){
                $arrayMenu = array_merge($arrayMenu,config($keyconfig));
            }

        }
        foreach ($arrayMenu as $key => $row){
            $row["link"] = route($row["route"]);
            $arrayMenu[$key] = $row;
        }
        return $arrayMenu;
    }
}
function mymodule(){
    return MyModule::getInstance();
}