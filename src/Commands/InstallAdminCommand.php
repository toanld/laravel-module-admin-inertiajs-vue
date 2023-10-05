<?php
namespace Toanld\LaravelModuleAdminInertiajsVue\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InstallAdminCommand extends Command
{
    protected $signature = 'install:admin';
    protected $description = 'Install Admin manager';
    protected $packages = [];
    protected $packageJs = [];

    public function handle()
    {
        $arrayComposer = [
            "nwidart/laravel-modules",
            "ryannielson/meta",
            "toanld/ziggy",
            "toanld/laravel-debug-to-sql",
            "toanld/modules-inertia",
            "toanld/multi-relationships",
            "inertiajs/inertia-laravel",
            "kalnoy/nestedset",
            "intervention/image"

        ];
        $arrayNpm = [
            "@inertiajs/inertia",
            "@inertiajs/progress",
            "@inertiajs/vue3",
            "@popperjs/core",
            "sass",
            "html2canvas",
            "nprogress",
            "postcss",
            "postcss-import",
            "@tailwindcss/forms",
            "@tailwindcss/line-clamp",
            "tailwindcss",
            "lodash",
            "uuid",
            "fuse.js",
            "autoprefixer",
            "@vitejs/plugin-vue",
            "vue",
        ];
        $checkInstall = false;
        foreach ($arrayComposer as $package){
            if (!$this->hasPackage($package)) {
                $checkInstall = true;
                $this->info('composer require ' . $package);
            }
        }
        foreach ($arrayNpm as $package){
            if (!$this->hasPackageJs($package)) {
                $checkInstall = true;
                $this->info("npm -D install $package");
            }
        }

        if(!$checkInstall){
            $this->addModuleStatus();
            $this->copyAdmin();

            $stubDir = __DIR__ . "/../Modules/Admin/Stubs";
            $this->copyDirectory($stubDir . "/public/assets/libs",base_path("public") . "/assets/libs");
            $this->copyStub($stubDir . "/HandleInertiaRequests.stub",base_path("app/Http/Middleware") . "/HandleInertiaRequests.php");
            $this->copyStub($stubDir . "/TrustProxies.stub",base_path("app/Http/Middleware") . "/TrustProxies.php");
            $appConfig = file_get_contents(base_path() . "/app/Http/Kernel.php");
            if(strpos($appConfig,"HandleInertiaRequests::class") === false){
                $appConfig = str_replace("'web' => [","'web' => [\n            \\App\\Http\\Middleware\\HandleInertiaRequests::class,\n",$appConfig);
                file_put_contents(base_path() . "/app/Http/Kernel.php",$appConfig);
            }
            if(strpos($appConfig,"'superadmin' =>") === false){
                $appConfig = str_replace('protected $middlewareGroups = [','protected $middlewareGroups = [' . "\n"  . "'superadmin' => [],\n",$appConfig);
                file_put_contents(base_path() . "/app/Http/Kernel.php",$appConfig);
            }
            $this->copyStub($stubDir . "/Config/modules.stub",base_path("config") . "/modules.php");
            $this->copyStub($stubDir . "/Config/ziggy.stub",base_path("config") . "/ziggy.php");
            $this->copyStub($stubDir . "/postcss.config.js",base_path() . "/postcss.config.js");
            $this->copyStub($stubDir . "/tailwind.config.js",base_path() . "/tailwind.config.js");
            $this->copyStub($stubDir . "/vite.config.js",base_path() . "/vite.config.js");
            $this->copyStub($stubDir . "/RedirectIfAuthenticated.stub",base_path("app/Http/Middleware") . "/RedirectIfAuthenticated.php");
            $this->addComposer();
            $this->warn("Lưu ý sửa file vite.config.js dựa trên file Modules/Admin/Stubs/vite.config.stub");
            $this->warn("Chạy lệnh sau");
            $this->info('composer update');
            Artisan::call('storage:link');
        }

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

    function hasPackage($package){
        if(empty($this->packages)) {
            $composerArray = json_decode(file_get_contents(base_path() . "/composer.json"),true);
            if (isset($composerArray["require"])) {
                $this->packages = array_merge($this->packages, $composerArray["require"]);
            }
            if (isset($composerArray["require-dev"])) {
                $this->packages = array_merge($this->packages, $composerArray["require-dev"]);
            }
        }
        return isset($this->packages[$package]);
    }

    function hasPackageJs($package){
        if(empty($this->packageJs)) {
            $composerArray = json_decode(file_get_contents(base_path() . "/package.json"),true);
            if(isset($composerArray["type"]) && $composerArray["type"] == "module"){
                unset($composerArray["type"]);
                file_put_contents(base_path() . "/package.json", json_encode($composerArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            }
            if (isset($composerArray["devDependencies"])) {
                $this->packageJs = array_merge($this->packageJs, $composerArray["devDependencies"]);
            }
            if (isset($composerArray["dependencies"])) {
                $this->packageJs = array_merge($this->packageJs, $composerArray["dependencies"]);
            }
        }
        return isset($this->packageJs[$package]);
    }

    public function copyAdmin(){
        $this->copyDirectory(__DIR__ . "/../Modules/Admin",base_path("Modules") . "/Admin");
    }

    public function addComposer(){
        // Đường dẫn tới tệp composer.json của bạn
        $composerFilePath = base_path() . '/composer.json';

        // Đọc nội dung từ tệp composer.json
        $composerData = json_decode(file_get_contents($composerFilePath), true);

        // Thêm mục autoload: files nếu nó chưa tồn tại
        if (!isset($composerData['autoload']['psr-4']['Modules\\'])) {
            $composerData['autoload']['psr-4']['Modules\\'] = 'Modules/';
            //$composerData['autoload']['psr-4']['Modules\\Admin\\Helpers'] = 'Modules/Admin/Helpers';
            file_put_contents($composerFilePath, json_encode($composerData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        }
        // Thêm mục autoload: files nếu nó chưa tồn tại
        if (!isset($composerData['autoload']['files'])) {
            $composerData['autoload']['files'] = [];
        }
        //*
        if(!in_array('Modules/Admin/Helpers/includes.php',$composerData['autoload']['files'])){
            // Thêm các tệp bạn muốn autoload vào mục autoload: files
            $filesToAutoload = [
                'Modules/Admin/Helpers/includes.php'
            ];
            $composerData['autoload']['files'] = array_merge($composerData['autoload']['files'], $filesToAutoload);
            // Ghi nội dung đã sửa vào tệp composer.json
            file_put_contents($composerFilePath, json_encode($composerData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        }
        //*/
    }

    function addModuleStatus(){
        $arrayModule = [];
        if(file_exists(base_path() . "/modules_statuses.json")){
            $arrayModule = (array) json_decode(file_get_contents(base_path() . "/modules_statuses.json"),true);
        }
        $arrayModule["Admin"] = true;
        file_put_contents(base_path() . "/modules_statuses.json", json_encode($arrayModule, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    function copyDirectory($source, $destination) {
        if (!is_dir($destination)) {
            mkdir($destination, 0777, true); // Tạo thư mục đích nếu chưa tồn tại
        }

        $dir = opendir($source);
        while (false !== ($file = readdir($dir))) {
            if ($file != '.' && $file != '..') {
                $sourcePath = $source . '/' . $file;
                $destinationPath = $destination . '/' . $file;

                if (is_dir($sourcePath)) {
                    $this->copyDirectory($sourcePath, $destinationPath); // Gọi đệ quy nếu là thư mục con
                } else {
                    copy($sourcePath, $destinationPath); // Sao chép tập tin
                }
            }
        }

        closedir($dir);
    }
}
