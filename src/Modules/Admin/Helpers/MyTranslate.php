<?php
/**
 * Created by Lê Đình Toản.
 * User: dinhtoan1905@gmail.com
 * Date: 11/22/2019
 * Time: 3:25 PM
 */

use App\Traits\SingletonTrait;
use Illuminate\Support\Facades\Cache;

class MyTranslate
{
    use SingletonTrait;
    var $texts = [];
    var $textDefaults = [];
    public $pathDefault = null;
    public $textDefaultVN = [];
    public $pathLang = null;
    protected $currentLang = null;
    var $m = null;
    function __construct()
    {
        $this->m = Cache::remember('languages',86400*10, function ()  {
            return Modules\Admin\Entities\Language::first();
        });
        if(!$this->m){
            $this->m = new Modules\Admin\Entities\Language();
            $this->m->json_default = json_encode($this->textDefaults);
            $this->m = $this->m->save();
        }
        if(!$this->m) return null;
        $this->m = Cache::remember('languages',86400*10, function ()  {
            return Modules\Admin\Entities\Language::first();
        });
        $this->textDefaults = (array) json_decode($this->m->json_default,true);
        $this->textDefaultVN = $this->textDefaults;
        $pathFileJsonDefault = base_path('lang/json') . "/";
        $lang = meta()->getCurrentLang();
        if(file_exists($pathFileJsonDefault . $lang . ".json")){
            $this->texts = (array) json_decode(file_get_contents($pathFileJsonDefault . $lang . ".json"),true);
        }

        $this->texts = array_merge($this->textDefaults,$this->texts);
    }

    public function add($text,$data = []){
        if(empty(trim($text))) return $text;
        $key = md5(trim($text));
        if(!isset($this->textDefaults[$key])){
            $this->textDefaults[$key] = $text;
            $this->saveDefault();
            Cache::forget('languages');
        }else{
            $text = data_get($this->texts,$key,$text);
        }

        foreach ($data as $k1 => $value){
            $text = str_replace("{_" . $k1 . "_}",$value,$text);
            $text = str_replace("{{" . $k1 . "}}",$value,$text);
            $text = str_replace("{" . $k1 . "}"," " . $value ." ",$text);
        }
        if(isAdmin()){
            return '{|tran' . $key . 'tran|}' . $text;
        }
        return $text;
    }

    public function save($langs){
        if(!$this->m) return false;
        $this->m->json_translate = json_encode($langs);
        $this->m->save();
        Cache::forget('languages');
    }

    public function getAll($lang = null){
        $pathFileJsonDefault = base_path('lang/json') . "/";
        if(empty($lang)) $lang = 'vn';
        $arrTranslated = [];
        if(file_exists($pathFileJsonDefault . $lang . ".json")){
            $arrTranslated = (array) json_decode(file_get_contents($pathFileJsonDefault . $lang . ".json"),true);
        }
        $arrayAll = [];
        foreach ($this->textDefaults as $key => $val){
            $value = isset($arrTranslated[$key]) ? $arrTranslated[$key] : null;
            $arrayAll[$key] = [
                'id'    => $key,
                "label" => $val,
                "value" => $value
            ];
        }
        return $arrayAll;
    }

    public function update($key,$value){
        if(isset($this->texts[$key])) {
            $pathFileJsonDefault = base_path('lang/json') . "/";
            $this->texts[$key] = $value;
            file_put_contents($pathFileJsonDefault . meta()->getCurrentLang() . ".json", json_encode($this->texts));
            return "Đã lưu thành công!";
        }else{
            return "Không tìm thấy nội dung để lưu! $key";
        }
    }

    public function store($arrayTranslated,$lang){
        $pathFileJsonDefault = base_path('lang/json') . "/";
        file_put_contents($pathFileJsonDefault . $lang . ".json", json_encode($arrayTranslated));
        return "Đã lưu thành công!";
    }

    public function getEdit($key){
        $data = [
            "key" => $key,
            "lang_name" => $this->getCurrentLangName()
        ];
        if(isset($this->textDefaults[$key])){
            $data["default"] = $this->textDefaults[$key];
        }
        if(isset($this->texts[$key])){
            $data["text"] = $this->texts[$key];
        }
        return $data;
    }

    public function getDefault($vn = false){
        if($vn){
            return $this->textDefaultVN;
        }else{
            return $this->textDefaults;
        }

    }

    public function getLang(){
        return $this->texts;
    }


    public function getCurrentLangName(){
        return meta()->getCurrentLangName();
    }


    public function saveDefault(){
        if(!$this->m) return false;
        $this->m->json_default = json_encode($this->textDefaults);
        $this->m->save();
        Cache::forget('languages');
    }
}
function myTranslate(){
    return MyTranslate::getInstance();
}
function myTrans($text,$data = []){
    return myTranslate()->add($text,$data);
}
function myTransAdmin($text){
    return $text;
}
