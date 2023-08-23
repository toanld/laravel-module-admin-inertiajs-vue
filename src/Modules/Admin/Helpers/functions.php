<?php
function imageGetPathThumb($fileName,$w,$h){
    $fileTime = intval($fileName);
    $pathTime = date("Y/m",$fileTime);
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

    return route($routeName,$param);
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
    return false;
}
function getFileNameFromUrl($file){
    $file = str_replace("\\","/",$file);
    $file = explode("/",$file);
    $file = end($file);
    return $file;
}
