<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Helpers\Constants;

function isAdmin(){
    if (auth()->check() && (auth()->user()->admin_type & Constants::USER_TYPE_SUPER_ADMIN) === Constants::USER_TYPE_SUPER_ADMIN) {
        return true;
    }
    return false;
}
function imageGetPathThumb($fileName,$w,$h){
    return "/storage/uploads/thumb/" . $w . "/" . $h . "/" . $fileName;
}
function imageGetPathFullsize($fileName){
    $fileTime = intval($fileName);
    $pathTime = date("Y/m",$fileTime);
    return "/storage/uploads/" . $pathTime . "/" . $fileName;
}
function myroute($routeName,$param = []){
    if($routeName == "static" && isset($param["slug"])){
        myweb()->setStaticSlug($param["slug"]);
    }
    if(Route::has($routeName)){
        return route($routeName,$param);
    }
    return "/?error=router";
}

function storage_path_picture($type = 'products'){
    return storage_path("app/public") . "/$type/";
}
function get_pictures_save_path($file,$type='products'){
    $file = getFileNameFromUrl($file);
    $fileTime = intval($file);
    $path = storage_path_picture($type) . date("Y/m",$fileTime) . "/" . $file;
    if(file_exists($path)){
        return $path;
    }
    $path = storage_path_picture("pictures/products") . date("m/d",$fileTime) . "/" . $file;
    if(file_exists($path)){
        return $path;
    }
    $path = storage_path_picture("pictures/categories") . date("m/d",$fileTime) . "/" . $file;
    if(file_exists($path)){
        return $path;
    }
    return false;
}
function getFileNameFromUrl($file){
    $file = str_replace("\\","/",$file);
    $file = explode("/",$file);
    $file = end($file);
    return $file;
}
function priceToString($price){
    $length = strlen($price);
    $price = (int)$price;
    if(substr($price, -3) != '000'){
        return format_number($price) .'<sup>&#8363;</sup>';
    }
    if($length <= 6){
        return format_number($price) .'<sup>&#8363;</sup>';
    }else {
        if($length > 6 && $length <= 9){
            $text = ' triệu';
            $priceFormat = roundingPrice($price / 1000);
            return $priceFormat . $text;
        }elseif ($length > 9) {
            if(substr($price, -6) != '000000'){
                return format_number($price) .'<sup>&#8363;</sup>';
            }
            $text = ' tỷ';
            $priceFormat1 = (int)floor($price / 1000000000);
            $priceFormat2 = roundingPrice(($price - ($priceFormat1 * 1000000000))/ 1000);
            if ($priceFormat2 < 100 && $priceFormat2 >= 10) {
                $priceFormat2 = '0' . $priceFormat2;
            } elseif ($priceFormat2 < 100 && $priceFormat2 > 0) {
                $priceFormat2 = '00' . $priceFormat2;
            } elseif ($priceFormat2 % 100 == 0){
                $priceFormat2 = $priceFormat2 / 100;
            }elseif ($priceFormat2 % 10 == 0) {
                $priceFormat2 = $priceFormat2 / 10;
            }
            if($priceFormat2 == 0){
                return spacePrice($priceFormat1) . $text;
            }
            return spacePrice($priceFormat1) . ',' .$priceFormat2 . $text;
        }
    }
}
function format_number($number, $num_decimal=2, $edit=0){

    $sep    = ($edit == 0 ? array(",", ".") : array(".", ""));
    $stt    = -1;
    $return = number_format( floatval($number), $num_decimal, $sep[0], $sep[1]);
    for($i=$num_decimal; $i>0; $i--){
        $stt++;
        if(intval(substr($return, -$i, $i)) == 0){
            $return = number_format( floatval($number), $stt, $sep[0], $sep[1]);
            break;
        }
    }
    return $return;
}
function roundingPrice($price){
    if($price % 1000 == 0){
        return $price / 1000;
    }elseif ($price % 100 == 0) {
        return str_replace('.', ',', round($price / 1000, 1 , PHP_ROUND_HALF_UP));
    }elseif($price % 10 == 0){
        return str_replace('.', ',', round($price / 1000, 2 , PHP_ROUND_HALF_UP));
    }else{
        return str_replace('.', ',', round($price / 1000, 3 , PHP_ROUND_HALF_UP));
    }
}
function spacePrice($price){
    $text = '';
    $price = trim($price);
    $strlen = strlen($price);
    for ($i=-1; $i >= -ceil(($strlen) / 3); $i--) {
        if(-$i*3 > $strlen){
            $text = substr($price, 0, $strlen + ($i+1)*3) . $text;
        }else{
            $text = ' ' . substr($price, $i*3, 3) . $text;
        }
    }
    return $text;
}
function linkDetailProject($slug, $id)
{
    return '/app/'.$slug.'/'.$id.'.html';
}

function linkDetailPost($slug, $id)
{
    return '/app/news/'.$id.'/'.$slug.'.html';
}

// xử lý thời gian
function generateDurationShort($int_time, $default="1 phút"){
    $strReturn  = $default;
    $arrTime    = array (
        31536000 => "năm",
        86400  => "ngày",
        3600  => "giờ",
        60    => "phút",
    );
    foreach($arrTime as $key => $value){
        if($int_time >= $key){
            $strReturn = format_number(intval($int_time/$key)) . " " . $value;
            return $strReturn;
        }
    }
    return $strReturn;
}
function replaceKeywordPlusToWhiteSpace($keyword) {
    return preg_replace('/\++|\s+/', ' ', $keyword);
}
function replaceFCK($string, $type = 0)
{
    $array_fck = array("&Agrave;", "&Aacute;", "&Acirc;", "&Atilde;", "&Egrave;", "&Eacute;", "&Ecirc;", "&Igrave;", "&Iacute;", "&Icirc;",
        "&Iuml;", "&ETH;", "&Ograve;", "&Oacute;", "&Ocirc;", "&Otilde;", "&Ugrave;", "&Uacute;", "&Yacute;", "&agrave;",
        "&aacute;", "&acirc;", "&atilde;", "&egrave;", "&eacute;", "&ecirc;", "&igrave;", "&iacute;", "&ograve;", "&oacute;",
        "&ocirc;", "&otilde;", "&ugrave;", "&uacute;", "&ucirc;", "&yacute;",
    );
    $array_text = array("À", "Á", "Â", "Ã", "È", "É", "Ê", "Ì", "Í", "Î",
        "Ï", "Ð", "Ò", "Ó", "Ô", "Õ", "Ù", "Ú", "Ý", "à",
        "á", "â", "ã", "è", "é", "ê", "ì", "í", "ò", "ó",
        "ô", "õ", "ù", "ú", "û", "ý",
    );
    if ($type == 1) $string = str_replace($array_fck, $array_text, $string);
    else $string = str_replace($array_text, $array_fck, $string);

    return $string;
}

