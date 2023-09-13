<?php

namespace Modules\Admin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Intervention\Image\ImageManager;

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
            $pathSaveFile = get_pictures_save_path($fileName,'uploads');
            if(!$pathSaveFile) return $response;
            $fileName = getFileNameFromUrl($url);
            $pathThumb = storage_path_picture('thumb') . $with . "/" . $height . "/";
            if(!file_exists($pathThumb)) mkdir($pathThumb, 0777, true);

            $image = Image::make($pathSaveFile);
            // Lấy kích thước của ảnh gốc
            $originalWidth = $image->width();
            $originalHeight = $image->height();
            $left = $this->getBgPosition($image,'left',$originalWidth,$originalHeight);
            $right = $this->getBgPosition($image,'right',$originalWidth,$originalHeight);
            $top = $this->getBgPosition($image,'top',$originalWidth,$originalHeight);
            $bottom = $this->getBgPosition($image,'bottom',$originalWidth,$originalHeight);
            $newWidth = $originalWidth - $left - $right;
            $newHeight = $originalHeight - $top - $bottom;
            $image->crop($newWidth+10, $newHeight+10, $left-5,$top-5);
            $image->resize($with, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
            $image->resizeCanvas($with, $height, 'center', false, '#ffffff');
            $backgroundColor = '#cccccc';
            $newImage = Image::canvas($with, $height, $backgroundColor);
            // Chèn ảnh gốc vào ảnh mới để tạo ảnh vuông với background màu trắng
            $newImage->insert($image, 'center');
            $newImage->save($pathThumb . $fileName,100);
            return $newImage->response('jpg',100);


        }

        return $response;
    }

    function getBgPosition($image,$type,$originalWidth,$originalHeight){
        switch ($type){
            case "left":
                // Tìm giới hạn trái nhất có màu khác trắng
                for ($x = 0; $x < $originalWidth; $x++) {
                    for($y = 0; $y < $originalHeight; $y++){
                        $color = $image->pickColor($x, $y);
                        if(($color[0]+$color[1]+$color[2]) != (255*3)){
                            if($x > 10) $x = $x - 10;
                            return $x;
                        }
                    }
                }
                break;
            case "right":
                // Tìm giới hạn trái nhất có màu khác trắng
                for ($x = $originalWidth-1; $x > 0; $x--) {
                    for($y = 0; $y < $originalHeight; $y++){
                        $color = $image->pickColor($x, $y);
                        if(($color[0]+$color[1]+$color[2]) != (255*3)){
                            if($x+10 < $originalWidth) $x = $x + 10;
                            return $originalWidth-$x;
                        }
                    }
                }
                break;
            case "top":
                // Tìm giới hạn trái nhất có màu khác trắng
                for ($y = 0; $y < $originalHeight-1; $y++) {
                    for($x = 0; $x < $originalWidth-1; $x++){
                        $color = $image->pickColor($x, $y);
                        if(($color[0]+$color[1]+$color[2]) != (255*3)){
                            if($y > 10) $y = $y - 10;
                            return $y;
                        }
                    }
                }
                break;
            case "bottom":
                // Tìm giới hạn trái nhất có màu khác trắng
                for ($y = $originalHeight-1; $y > 0 ; $y--) {
                    for($x = 0; $x < $originalWidth-1; $x++){
                        $color = $image->pickColor($x, $y);
                        if(($color[0]+$color[1]+$color[2]) != (255*3)){
                            if($y+10 < $originalHeight) $y = $y + 10;
                            return $originalHeight-$y-1;
                        }
                    }
                }
                break;
        }
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
