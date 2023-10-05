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
    var $defaultConfig = [
        "title" => "Website thương mại điện tử",
        "description" => "Website thương mại điện tử",
        "meta" => [
            "title" => "Website thương mại điện tử",
            "description" => "Website thương mại điện tử",
            "site_name" => "Website thương mại điện tử",
            "type" => "Website",
            "locale" => "vi_VN",
        ],
        "contact" => [
            "phone" => '190xxxx',
            "zalo" => '098xxxx',
            "fb" => 'https://www.facebook.com/xxxxx',
            "youtube" => 'https://www.facebook.com/xxxxx',
            "email" => 'xxx@gmail.com',
            "map" => 'https://map.google.com/sxxxx',
        ]

    ];
    public function __construct()
    {
        Inertia::share('routeName', "configurations");
    }

    public function index()
    {
        $data = ModelName::get()->keyBy('name');
        $reload = false;
        foreach ($this->defaultConfig as $key => $value){
            if(!isset($data[$key])){
                $m = new ModelName();
                $m->name = $key;
                $m->md5 = md5($key);
                $m->value = is_array($value) ? json_encode($value) : $value;
                $m->type = is_array($value) ? "json" : "string";
                $m->save();
                $reload = true;
            }
        }
        if($reload) $data = ModelName::keyBy('name')->get();
        return Inertia::module('admin::Configurations/Index',[
            "datas" => $data
        ]);
    }


    public function store()
    {
        $validate = Request::validate([
            'name' => ['required', 'max:255']
        ]);
        $slug = Str::slug($validate['name']);
        $md5 = md5($slug);
        $model = new ModelName();
        $model->name = $validate['name'];
        $model->slug = $slug;
        $model->md5 = $md5;
        $model->save();

        return Redirect::route('configurations')->with('success', 'Contact created.');
    }

}
