<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Modules\Admin\Entities\Configuration;
use Modules\Admin\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Modules\Admin\Entities\Configuration as ModelName;

class ConfigController extends Controller
{
    var $defaultConfig = [];
    public function __construct()
    {
        Inertia::share('routeName', "configurations");
    }

    public function index()
    {
        $data = ModelName::get();
        if(count($data) > 0) $data = $data->keyBy('name')->toArray();
        $reload = false;
        foreach ($this->getDefaultConfigDB() as $key => $value){
            if(!isset($data[$key])){
                $m = new ModelName();
                $m->name = $key;
                $m->md5 = md5($key);
                $m->value = $value["value"];
                $m->type = is_array($value["value"]) ? "json" : "string";
                $m->save();
                $reload = true;
            }
        }
        if($reload) {
            $data = ModelName::get();
            if (count($data) > 0) $data = $data->keyBy('name')->toArray();
        }
        $dataConfig = [];
        foreach ($this->defaultConfig as $key => $value){
            $value["field"] = $key;
            if(isset($data[$key])){
                if(is_array($value["value"])){
                    $dbvalue = $data[$key]["value"];
                    if(!empty($dbvalue)){
                        $dbvalue = (array) $dbvalue;
                        $value["value"] = array_merge($value["value"],$dbvalue);
                    }
                }else{
                    $value["value"] = $data[$key]["value"];
                }
                $value["type"] = $data[$key]["type"];
            }
            if(is_array($value["value"])){
                $value["value"] = json_encode($value["value"],JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            }
            $dataConfig[$key] = $value;
        }
        return Inertia::module('admin::Configurations/Index',[
            "datas" => $dataConfig
        ]);
    }

    /**
     * Lấy ra config mặc định mẫu từ các module
     */
    public function getDefaultConfigDB(){
        if(!empty($this->defaultConfig)) return $this->defaultConfig;
        $modules = mymodule()->getModules();
        $this->defaultConfig = [];
        foreach ($modules as $module => $status){
            $arrConfig = config(strtolower($module). ".db_configs");
            if(is_array($arrConfig)){
                $this->defaultConfig = array_merge($this->defaultConfig,$arrConfig);
            }
        }
        //kểm tra lại xem đủ chuẩn config db chưa
        foreach ($this->defaultConfig as $key => $value){
            if(empty($value)) unset($this->defaultConfig[$key]);
            if(!isset($value["label"])) $value["label"] = null;
            if(!isset($value["value"])) $value["value"] = null;
            if(!isset($value["type"])) $value["type"] = null;
            $this->defaultConfig[$key] = $value;
        }
        return $this->defaultConfig;
    }


    public function store(\Illuminate\Http\Request $request)
    {
        $data_configs = (array)$request->input('data_configs');
        foreach ($data_configs as $name => $val){
            $m = Configuration::where('name',$name)->first();
            if($m){
                $arr = json_decode($val["value"],true);
                if(empty($arr)){
                    $m->value = $val["value"];
                }else{
                    $m->value = $arr;
                }
                $m->save();
            }
        }
        return Redirect::route('configurations')->with('success', 'Contact created.');
    }

}
