<?php

namespace Modules\Admin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Intervention\Image\ImageManager;
use Modules\Admin\Helpers\Classes\ResizeImage;

class CreateThumbImage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $url = url()->full();
        if(strpos($url,'/storage/') !== false){
            if(preg_match("/thumb\/([vh])\/([0-9]+)\/([0-9]+)\/([0-9\_a-zA-Z\.]+)/",$url,$match)) {
                $response = $next($request);
                if ($response->status() === 404) {
                    $path = $match[0];
                    $path = storage_path('app/public') . "/" . $path;
                    if(!file_exists(dirname($path))) mkdir(dirname($path), 0777, true);
                    $flip = $match[1];
                    $with = $match[2];
                    $height = $match[3];
                    $fileName = $match[4];
                    $resize = new ResizeImage();
                    $resize->setPathSave(dirname($path) . "/");
                    $image = $resize->resize($fileName, $with, $height,false,$flip);
                    if (!$image) return $response;
                    return $image;
                }
            }
            if(preg_match("/thumb\/([0-9]+)\/([0-9]+)\/([0-9\_a-zA-Z\.]+)/",$url,$match)) {
                $response = $next($request);
                if ($response->status() === 404) {
                    $path = $match[0];
                    $path = storage_path('app/public') . "/" . $path;
                    if(!file_exists(dirname($path))) mkdir(dirname($path), 0777, true);
                    $with = $match[1];
                    $height = $match[2];
                    $fileName = $match[3];
                    $resize = new ResizeImage();
                    $resize->setPathSave(dirname($path) . "/");
                    $image = $resize->resize($fileName, $with, $height);
                    if (!$image) return $response;
                    return $image;
                }
            }
        }


        return $next($request);
    }



    function resize(){
        $url = url()->full();
        $pathSaveFile = get_pictures_save_path($url,'products');
        if(!$pathSaveFile){
            $pathSaveFile = get_pictures_save_path($url,'categories');
        }
        if(!$pathSaveFile) return false;
        $fileName = getFileNameFromUrl($url);
        $pathThumb = storage_path_picture('thumb');
        if(!file_exists($pathThumb)) mkdir($pathThumb, 0777, true);
        //*
        $image = Image::make($pathSaveFile);
        $image->resize(600, 600, function ($constraint) {
            $constraint->aspectRatio();
        });
        $backgroundColor = '#ffffff';
        $newImage = Image::canvas(600, 600, $backgroundColor);
        // Chèn ảnh gốc vào ảnh mới để tạo ảnh vuông với background màu trắng
        $newImage->insert($image, 'center');
        // Chèn watermark vào ảnh gốc

        // Lưu ảnh mới
        $newImage->save($pathThumb . $fileName);
        //*/
        return $newImage->response('jpg',100);
    }
}
