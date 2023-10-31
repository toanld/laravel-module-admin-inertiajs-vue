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
function convertToUnicode($string)
{
    if(!empty($string)) $string = html_entity_decode($string, ENT_COMPAT, 'UTF-8');
    $string = replaceNCR($string);
    $trans = array("á" => "á", "à" => "à", "ả" => "ả", "ã" => "ã", "ạ" => "ạ", "ă" => "ă", "ắ" => "ắ",
        "ằ" => "ằ", "ẳ" => "ẳ", "ẵ" => "ẵ", "ặ" => "ặ", "â" => "â", "ấ" => "ấ", "ầ" => "ầ", "ẩ" => "ẩ",
        "ậ" => "ậ", "ẫ" => "ẫ", "ó" => "ó", "ò" => "ò", "ỏ" => "ỏ", "õ" => "õ", "ọ" => "ọ", "ô" => "ô",
        "ố" => "ố", "ồ" => "ồ", "ổ" => "ổ", "ỗ" => "ỗ", "ộ" => "ộ", "ơ" => "ơ", "ớ" => "ớ", "ờ" => "ờ",
        "ở" => "ở", "ỡ" => "ỡ", "ợ" => "ợ", "ú" => "ú", "ù" => "ù", "ủ" => "ủ", "ũ" => "ũ", "ụ" => "ụ",
        "ư" => "ư", "ứ" => "ứ", "ừ" => "ừ", "ử" => "ử", "ự" => "ự", "ữ" => "ữ", "é" => "é", "è" => "è",
        "ẻ" => "ẻ", "ẽ" => "ẽ", "ẹ" => "ẹ", "ê" => "ê", "ế" => "ế", "ề" => "ề", "ể" => "ể", "ễ" => "ễ",
        "ệ" => "ệ", "í" => "í", "ì" => "ì", "ỉ" => "ỉ", "ĩ" => "ĩ", "ị" => "ị", "ý" => "ý", "ỳ" => "ỳ",
        "ỷ" => "ỷ", "ỹ" => "ỹ", "ỵ" => "ỵ", "đ" => "đ", "Á" => "Á", "À" => "À", "Ả" => "Ả", "Ã" => "Ã",
        "Ạ" => "Ạ", "Ă" => "Ă", "Ắ" => "Ắ", "Ằ" => "Ằ", "Ẳ" => "Ẳ", "Ẵ" => "Ẵ", "Ặ" => "Ặ", "Â" => "Â",
        "Ấ" => "Ấ", "Ầ" => "Ầ", "Ẩ" => "Ẩ", "Ậ" => "Ậ", "Ẫ" => "Ẫ", "Ó" => "Ó", "Ò" => "Ò", "Ỏ" => "Ỏ",
        "Õ" => "Õ", "Ọ" => "Ọ", "Ô" => "Ô", "Ố" => "Ố", "Ồ" => "Ồ", "Ổ" => "Ổ", "Ỗ" => "Ỗ", "Ộ" => "Ộ",
        "Ơ" => "Ơ", "Ớ" => "Ớ", "Ờ" => "Ờ", "Ở" => "Ở", "Ỡ" => "Ỡ", "Ợ" => "Ợ", "Ú" => "Ú", "Ù" => "Ù",
        "Ủ" => "Ủ", "Ũ" => "Ũ", "Ụ" => "Ụ", "Ư" => "Ư", "Ứ" => "Ứ", "Ừ" => "Ừ", "Ử" => "Ử", "Ữ" => "Ữ",
        "Ự" => "Ự", "É" => "É", "È" => "È", "Ẻ" => "Ẻ", "Ẽ" => "Ẽ", "Ẹ" => "Ẹ", "Ê" => "Ê", "Ế" => "Ế",
        "Ề" => "Ề", "Ể" => "Ể", "Ễ" => "Ễ", "Ệ" => "Ệ", "Í" => "Í", "Ì" => "Ì", "Ỉ" => "Ỉ", "Ĩ" => "Ĩ",
        "Ị" => "Ị", "Ý" => "Ý", "Ỳ" => "Ỳ", "Ỷ" => "Ỷ", "Ỹ" => "Ỹ", "Ỵ" => "Ỵ", "Đ" => "Đ",
        "&#225;" => "á", "&#224;" => "à", "&#7843;" => "ả", "&#227;" => "ã", "&#7841;" => "ạ", "&#259;" => "ă",
        "&#7855;" => "ắ", "&#7857;" => "ằ", "&#7859;" => "ẳ", "&#7861;" => "ẵ", "&#7863;" => "ặ", "&#226;" => "â",
        "&#7845;" => "ấ", "&#7847;" => "ầ", "&#7849;" => "ẩ", "&#7853;" => "ậ", "&#7851;" => "ẫ", "&#243;" => "ó",
        "&#242;" => "ò", "&#7887;" => "ỏ", "&#245;" => "õ", "&#7885;" => "ọ", "&#244;" => "ô", "&#7889;" => "ố",
        "&#7891;" => "ồ", "&#7893;" => "ổ", "&#7895;" => "ỗ", "&#7897;" => "ộ", "&#417;" => "ơ", "&#7899;" => "ớ",
        "&#7901;" => "ờ", "&#7903;" => "ở", "&#7905;" => "ỡ", "&#7907;" => "ợ", "&#250;" => "ú", "&#249;" => "ù",
        "&#7911;" => "ủ", "&#361;" => "ũ", "&#7909;" => "ụ", "&#432;" => "ư", "&#7913;" => "ứ", "&#7915;" => "ừ",
        "&#7917;" => "ử", "&#7921;" => "ự", "&#7919;" => "ữ", "&#233;" => "é", "&#232;" => "è", "&#7867;" => "ẻ",
        "&#7869;" => "ẽ", "&#7865;" => "ẹ", "&#234;" => "ê", "&#7871;" => "ế", "&#7873;" => "ề", "&#7875;" => "ể",
        "&#7877;" => "ễ", "&#7879;" => "ệ", "&#237;" => "í", "&#236;" => "ì", "&#7881;" => "ỉ", "&#297;" => "ĩ",
        "&#7883;" => "ị", "&#253;" => "ý", "&#7923;" => "ỳ", "&#7927;" => "ỷ", "&#7929;" => "ỹ", "&#7925;" => "ỵ",
        "&#273;" => "đ", "&#193;" => "Á", "&#192;" => "À", "&#7842;" => "Ả", "&#195;" => "Ã", "&#7840;" => "Ạ",
        "&#258;" => "Ă", "&#7854;" => "Ắ", "&#7856;" => "Ằ", "&#7858;" => "Ẳ", "&#7860;" => "Ẵ", "&#7862;" => "Ặ",
        "&#194;" => "Â", "&#7844;" => "Ấ", "&#7846;" => "Ầ", "&#7848;" => "Ẩ", "&#7852;" => "Ậ", "&#7850;" => "Ẫ",
        "&#211;" => "Ó", "&#210;" => "Ò", "&#7886;" => "Ỏ", "&#213;" => "Õ", "&#7884;" => "Ọ", "&#212;" => "Ô",
        "&#7888;" => "Ố", "&#7890;" => "Ồ", "&#7892;" => "Ổ", "&#7894;" => "Ỗ", "&#7896;" => "Ộ", "&#416;" => "Ơ",
        "&#7898;" => "Ớ", "&#7900;" => "Ờ", "&#7902;" => "Ở", "&#7904;" => "Ỡ", "&#7906;" => "Ợ", "&#218;" => "Ú",
        "&#217;" => "Ù", "&#7910;" => "Ủ", "&#360;" => "Ũ", "&#7908;" => "Ụ", "&#431;" => "Ư", "&#7912;" => "Ứ",
        "&#7914;" => "Ừ", "&#7916;" => "Ử", "&#7918;" => "Ữ", "&#7920;" => "Ự", "&#201;" => "É", "&#200;" => "È",
        "&#7866;" => "Ẻ", "&#7868;" => "Ẽ", "&#7864;" => "Ẹ", "&#202;" => "Ê", "&#7870;" => "Ế", "&#7872;" => "Ề",
        "&#7874;" => "Ể", "&#7876;" => "Ễ", "&#7878;" => "Ệ", "&#205;" => "Í", "&#204;" => "Ì", "&#7880;" => "Ỉ",
        "&#296;" => "Ĩ", "&#7882;" => "Ị", "&#221;" => "Ý", "&#7922;" => "Ỳ", "&#7926;" => "Ỷ", "&#7928;" => "Ỹ",
        "&#7924;" => "Ỵ", "&#039;" => "'", "&#x27;" => "'"
        //"&#272;" => "Đ","&#x27;"=>"`","&#39;"=>"`","&#039;"=>"`","&#x22;"=>"“","&#34;"=>"“","\""=>"“"
    );
    $string = strtr($string, $trans);
    $string = mb_convert_encoding($string, "UTF-8", "UTF-8");
    return $string;
}
function replaceNCR($str)
{
    $codeNCR = array("&#224;", "&#225;", "&#7841;", "&#7843;", "&#227;", "&#226;", "&#7847;", "&#7845;", "&#7853;", "&#7849;", "&#7851;", "&#259;", "&#7857;", "&#7855;", "&#7863;", "&#7859;", "&#7861;",
        "&#232;", "&#233;", "&#7865;", "&#7867;", "&#7869;", "&#234;", "&#7873;", "&#7871;", "&#7879;", "&#7875;", "&#7877;",
        "&#236;", "&#237;", "&#7883;", "&#7881;", "&#297;",
        "&#242;", "&#243;", "&#7885;", "&#7887;", "&#245;", "&#244;", "&#7891;", "&#7889;", "&#7897;", "&#7893;", "&#7895;", "&#417;", "&#7901;", "&#7899;", "&#7907;", "&#7903;", "&#7905;",
        "&#249;", "&#250;", "&#7909;", "&#7911;", "&#361;", "&#432;", "&#7915;", "&#7913;", "&#7921;", "&#7917;", "&#7919;",
        "&#7923;", "&#253;", "&#7925;", "&#7927;", "&#7929;",
        "&#273;",

        "&#192;", "&#193;", "&#7840;", "&#7842;", "&#195;", "&#194;", "&#7846;", "&#7844;", "&#7852;", "&#7848;", "&#7850;", "&#258;", "&#7856;", "&#7854;", "&#7862;", "&#7858;", "&#7860;",
        "&#200;", "&#201;", "&#7864;", "&#7866;", "&#7868;", "&#202;", "&#7872;", "&#7870;", "&#7878;", "&#7874;", "&#7876;",
        "&#204;", "&#205;", "&#7882;", "&#7880;", "&#296;",
        "&#210;", "&#211;", "&#7884;", "&#7886;", "&#213;", "&#212;", "&#7890;", "&#7888;", "&#7896;", "&#7892;", "&#7894;", "&#416;", "&#7900;", "&#7898;", "&#7906;", "&#7902;", "&#7904;",
        "&#217;", "&#218;", "&#7908;", "&#7910;", "&#360;", "&#431;", "&#7914;", "&#7912;", "&#7920;", "&#7916;", "&#7918;",
        "&#7922;", "&#221;", "&#7924;", "&#7926;", "&#7928;",
        "&#272;",
    );

    $codeVN = array("à", "á", "ạ", "ả", "ã", "â", "ầ", "ấ", "ậ", "ẩ", "ẫ", "ă", "ằ", "ắ", "ặ", "ẳ", "ẵ",
        "è", "é", "ẹ", "ẻ", "ẽ", "ê", "ề", "ế", "ệ", "ể", "ễ",
        "ì", "í", "ị", "ỉ", "ĩ",
        "ò", "ó", "ọ", "ỏ", "õ", "ô", "ồ", "ố", "ộ", "ổ", "ỗ", "ơ", "ờ", "ớ", "ợ", "ở", "ỡ",
        "ù", "ú", "ụ", "ủ", "ũ", "ư", "ừ", "ứ", "ự", "ử", "ữ",
        "ỳ", "ý", "ỵ", "ỷ", "ỹ",
        "đ",

        "À", "Á", "Ạ", "Ả", "Ã", "Â", "Ầ", "Ấ", "Ậ", "Ẩ", "Ẫ", "Ă", "Ằ", "Ắ", "Ặ", "Ẳ", "Ẵ",
        "È", "É", "Ẹ", "Ẻ", "Ẽ", "Ê", "Ề", "Ế", "Ệ", "Ể", "Ễ",
        "Ì", "Í", "Ị", "Ỉ", "Ĩ",
        "Ò", "Ó", "Ọ", "Ỏ", "Õ", "Ô", "Ồ", "Ố", "Ộ", "Ổ", "Ỗ", "Ơ", "Ờ", "Ớ", "Ợ", "Ở", "Ỡ",
        "Ù", "Ú", "Ụ", "Ủ", "Ũ", "Ư", "Ừ", "Ứ", "Ự", "Ử", "Ữ",
        "Ỳ", "Ý", "Ỵ", "Ỷ", "Ỹ",
        "Đ",
    );

    $str = str_replace($codeNCR, $codeVN, $str);
    return $str;
}
function removeHTML($string){
    $string = preg_replace ('/<script.*?\>.*?<\/script>/si', ' ', $string);
    $string = preg_replace ('/<style.*?\>.*?<\/style>/si', ' ', $string);
    $string = preg_replace ('/<.*?\>/si', ' ', $string);
    $string = str_replace ('&nbsp;', ' ', $string);
    $string = mb_convert_encoding($string, "UTF-8", "UTF-8");
    $string = str_replace (array(chr(9),chr(10),chr(13)), ' ', $string);
    for($i = 0; $i <= 5; $i++) $string = str_replace ('  ', ' ', $string);
    return $string;
}
