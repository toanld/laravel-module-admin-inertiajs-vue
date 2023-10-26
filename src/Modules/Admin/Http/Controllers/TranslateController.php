<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class TranslateController extends Controller
{
    public function __construct()
    {
        Inertia::share('routeName', "translates");
    }

    public function index(Request $request){
        $lang = $request->input('lang');
        $dataConfig = myTranslate()->getAll($lang);
        $langs = config('lang');

        if(empty($langs)) $langs = ["vn" => "Tiếng Việt","en" => "Tiếng Anh"];
        return Inertia::module('admin::Translates/Index',[
            "datas" => $dataConfig,
            "langs" => $langs,
            'lang'  => $lang
        ]);
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $arrRequest = (array)$request->input('data_configs');
        $lang = $request->input('lang');
        $arrSave = [];
        foreach ($arrRequest as $key=>$val){
            $arrSave[$key] = $val["value"];
        }
        myTranslate()->store($arrSave,$lang);
        return Redirect::route('translates',["lang" => $lang])->with('success', 'Data updated.');
    }

    public function edit(Request $request){
        $key = $request->input('key');
        return myTranslate()->getEdit($key);
    }

    public function update(Request $request){
        $key = $request->input('translate_key');
        $translate_text = $request->input('translate_text');
        return myTranslate()->update($key,$translate_text);
    }
}

