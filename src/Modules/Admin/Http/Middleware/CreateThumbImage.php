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
        $response = $next($request);
        $url = url()->full();
        if ($response->status() === 404 && strpos($url,'/storage/') !== false && preg_match("/thumb\/([0-9]+)\/([0-9]+)\/([0-9\_a-zA-Z\.]+)/",$url,$match)) {
            $with = $match[1];
            $height = $match[2];
            $fileName = $match[3];
            $resize = new ResizeImage();
            $image = $resize->resize($fileName,$with,$height);
            if(!$image) return $response;
            return $image;
        }

        return $response;
    }



    function resize(){
        $url = url()->full();
        $pathSaveFile = get_pictures_save_path($url,'products');
        if(!$pathSaveFile){
            $pathSaveFile = get_pictures_save_path($url,'categories');
        }
        if(!$pathSaveFile) return $response;
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
