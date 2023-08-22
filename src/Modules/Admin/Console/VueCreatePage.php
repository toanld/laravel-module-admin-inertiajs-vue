<?php

namespace Modules\Admin\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\Validator;

class VueCreatePage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:admin-page';
    protected $module = null;

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
        $modules = array_keys(mymodule()->getModules());
        $module = $this->choice("Chọn module cần tạo page", $modules,0);
        $this->module = $module;
        $typepages = [
            "All",
            "Create.vue",
            "Edit.vue",
            "Index.vue"
        ];
        $typepage = $this->choice("Chọn kiểu page cần tạo", $typepages,0);
        $folderVue = $this->ask('Nhập tên folder /Resources/Vuejs/Pages/<folder name> (tên bảng dữ liệu ví dụ: Products, Categories, Menu, User v.v..v)?');
        $modelName = $this->askValid('Nhập tên model (model name) ví dụ : Product, Category.....','modelName',"[^a-zA-Z\/]");
        $controllerName = $this->askValid('Nhập tên Controller ví dụ : ProductController, CategoryController.....','ControllerName',"[^a-zA-Z\/]");
        $routerName = $this->askValid('Nhập tên router ví dụ : products, categories.....','routerName',"[^a-zA-Z\/]");
        $arrayReplace = [
            '$MODULE$' => $module,
            '$MODULE_LOWER$' => strtolower($module),
            '$PATH_FOLDER$' => null,
            '$MODEL_NAME$' => $modelName,
            '$CONTROLLER$' => $controllerName,
            '$FOLDER_VUE$' => $folderVue,
            '$ROUTER_NAME$' => $routerName,
        ];
        foreach ($arrayReplace as $key => $value){
            $this->info($key . " => " . $value);
        }
        //copy controler
        $stubPath = __DIR__ . "/../Stubs/";
        $controller = file_get_contents($stubPath . "Controller.stub");
        $pathSaveController = module_path($this->module) . "/Http/Controllers/$controllerName" . ".php";
        $pathSpace = $this->getFilePath($controllerName);
        if(!empty($pathSpace)) $pathSpace = str_replace("/",'\\',$pathSpace);
        $arrayReplace['$PATH_FOLDER$'] = $pathSpace;
        $arrayReplace['$CONTROLLER$'] = $this->getFileName($controllerName);
        foreach ($arrayReplace as $key => $value){
            $controller = str_replace($key,$value,$controller);
        }
        if(!file_exists($pathSaveController)){
            if(!file_exists(dirname($pathSaveController))) mkdir(dirname($pathSaveController), 0777, true);
            file_put_contents($pathSaveController,$controller);
            $this->info("Done: " . $pathSaveController);
        }else{
            $this->warn("Exists: " . $pathSaveController);
        }
        //tạo router
        $this->appentowebRouter($routerName,$controllerName,$module);
        //tạo thư mục vuejs và copy các file trong stub ra
        $pathVuejs = module_path($this->module) . "/Resources/Vuejs/Pages/$folderVue";
        if(!file_exists($pathVuejs)) mkdir($pathVuejs, 0777, true);
        switch ($typepage){
            case "All":
                $this->copyVue('Create',$arrayReplace,$folderVue);
                $this->copyVue('Edit',$arrayReplace,$folderVue);
                $this->copyVue('Index',$arrayReplace,$folderVue);
                break;
            case "Create.vue":
                $this->copyVue('Create',$arrayReplace,$folderVue);
                break;
            case "Edit.vue":
                $this->copyVue('Edit',$arrayReplace,$folderVue);
                break;
            case "Index.vue":
                $this->copyVue('Index',$arrayReplace,$folderVue);
                break;
        }
        $this->createMenu($this->module,$routerName);
    }

    protected function getFilePath($path){
        $arr = explode("/",$path);
        if(count($arr) > 1){
            unset($arr[count($arr)-1]);
        }else{
            return null;
        }
        $path = implode("/",$arr);
        if(substr($path,0,1) !== "/"){
            $path = "/" . $path;
        }
        return $path;
    }

    protected function getFileName($path){
        $arr = explode("/",$path);
        return end($arr);
    }

    protected function copyVue($filename,$arrayReplace,$folderVue){
        $source = __DIR__ . "/../Stubs/vuejs/$filename" . ".stub";
        $to = module_path($this->module) . "/Resources/Vuejs/Pages/$folderVue/$filename" . ".vue";
        if(!file_exists(dirname($to))) mkdir(dirname($to), 0777, true);
        if(!file_exists($source)){
            $this->warn("Notfound: " . $source);
            exit;
        }
        $content = file_get_contents($source);
        foreach ($arrayReplace as $key => $value){
            $content = str_replace($key,$value,$content);
        }
        if(!file_exists($to)){
            file_put_contents($to,$content);
            $this->info("Done: " . $to);
        }else{
            $this->warn("Exists: " . $to);
        }
    }

    protected function appentowebRouter($name,$controller,$module){
        $pathSpace = $this->getFilePath($controller);
        if(!empty($pathSpace)) $pathSpace = str_replace("/",'\\',$pathSpace);
        $arr = explode("/",$controller);
        $controller = end($arr);
        $name =  preg_replace("/[^A-Za-z0-9]/", "",$name);
        $arrayRoute = [
            [ "name" => '','method' => 'get','route' => '','fn' => 'index'],
            [ "name" => 'create','method' => 'get','route' => '/create','fn' => 'create'],
            [ "name" => 'store','method' => 'post','route' => '','fn' => 'store'],
            [ "name" => 'edit','method' => 'get','route' => '/{model}/edit','fn' => 'edit'],
            [ "name" => 'update','method' => 'put','route' => '/{model}','fn' => 'update'],
            [ "name" => 'destroy','method' => 'delete','route' => '/{model}','fn' => 'destroy'],
            [ "name" => 'restore','method' => 'put','route' => '/{model}/restore','fn' => 'restore'],
        ];
        $routeAdminPath = module_path($this->module) . "/Routes/admin.php";
        if(!file_exists($routeAdminPath)) file_put_contents($routeAdminPath,'<?php' . "\n");
        $fileContent = file_get_contents($routeAdminPath);
        $fileContent = str_replace("\"","'",$fileContent);

        file_put_contents($routeAdminPath, "\n\n//$controller\n",FILE_APPEND);

        foreach ($arrayRoute as $rote){
            $route_path = $name . $rote["route"];
            $method = $rote["method"];
            $fn = $rote["fn"];

            $addRoute = "Route::$method('$route_path', [$controller::class, '$fn'])->name('" . $name . (empty($rote["name"]) ? '' : '.' . $rote["name"]) . "')->middleware('auth');";
            if(strpos($fileContent,"'$route_path'") === false){
                file_put_contents($routeAdminPath,$addRoute . "\n",FILE_APPEND);
            }
        }
        $fileContent = file_get_contents($routeAdminPath);
        $nameSpace = "use Modules\\" . $module . "\\Http\\Controllers$pathSpace\\$controller;";
        $this->info($nameSpace);
        if(strpos($fileContent,$nameSpace) === false) {
            $fileContent = str_replace('<?php', '<?php ' . "\n" . $nameSpace . "\n", $fileContent);
            file_put_contents($routeAdminPath, $fileContent);
        }
        //kiểm tra xem trong /Routers/web.php đã include admin chưa
        $routeWeb = file_get_contents(module_path($this->module) . "/Routes/web.php");
        if(strpos($routeWeb,'include_once __DIR__ . "/admin.php";') === false){
            file_put_contents(module_path($this->module) . "/Routes/web.php",'Route::prefix(config("admin.route_prefix"))->middleware(\'superadmin\')->group(function() {
    if(file_exists(__DIR__ . "/admin.php")) include_once __DIR__ . "/admin.php";
});',FILE_APPEND);
        }

    }

    protected function askValid($question, $field,$rules)
    {
        $value = $this->ask($question);

        if(preg_match("/$rules/",$value,$match)) {
            $this->warn("$field không thỏa mãn điều kiện $rules");
            return $this->askValid($question, $field, $rules);
        }

        return $value;
    }

    protected function createMenu($module,$route){
        $arrayConfig = Config::get(strtolower($module));
        if(!isset($arrayConfig["menu"])) $arrayConfig["menu"] = [];
        $foundItem = false;
        foreach ($arrayConfig['menu'] as $item) {
            if ($item['route'] === $route) {
                $foundItem = true;
                break;
            }
        }
        if($foundItem) return;
        $arrayConfig["menu"][] = [
            "name" => $route,
            'route' => $route
        ];
        $jsonData = $this->arrayToConfigString($arrayConfig);

        // Đường dẫn đến tệp cấu hình
        $configFilePath = module_path($module,'Config/config.php');

        // Ghi dữ liệu vào tệp cấu hình
        file_put_contents($configFilePath, "<?php\n\nreturn $jsonData;");
    }

    function arrayToConfigString($array, $indent = 0) {
        $output = "[\n";

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $output .= str_repeat("    ", $indent + 1) . "'$key' => " . $this->arrayToConfigString($value, $indent + 1) . ",\n";
            } else {
                $output .= str_repeat("    ", $indent + 1) . "'$key' => " . var_export($value, true) . ",\n";
            }
        }

        $output .= str_repeat("    ", $indent) . "]";

        return $output;
    }


    protected function validateInput($rules, $fieldName, $value)
    {
        $validator = Validator::make([
            $fieldName => $value
        ], [
            $fieldName => $rules
        ]);

        return $validator->fails()
            ? $validator->errors()->first($fieldName)
            : null;
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
            //['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
