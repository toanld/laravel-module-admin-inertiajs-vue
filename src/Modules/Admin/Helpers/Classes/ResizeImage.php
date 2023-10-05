<?php
namespace Modules\Admin\Helpers\Classes;
use Intervention\Image\Facades\Image;

class ResizeImage {
    public function resize($fileName,$with,$height,$check_exists = false){
        $pathSaveFile = get_pictures_save_path($fileName,'uploads');
        if(!$pathSaveFile) return false;
        $pathThumb = storage_path_picture('thumb') . $with . "/" . $height . "/";
        if(!file_exists($pathThumb)) mkdir($pathThumb, 0777, true);
        if(file_exists($pathThumb . $fileName) && $check_exists) return true;
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
        $watermask = config('admin.watermark');
        if(!empty($watermask) && $with > 500 && $height > 500){
            // Thêm Watermark
            $newImage->text($watermask, intval($with/2)-70, intval($height/2), function($font) {
                $pathFont = base_path('Modules/Admin/Resources/assets/fonts/bloomberg/Nimbus Sans Becker PBla.otf');
                //dd(file_get_contents($pathFont));
                $font->file($pathFont); // Đường dẫn đến font
                $font->size(50);
                $font->color(array(255, 255, 255, 0.5)); // Màu trắng, độ mờ 50%
            });
        }
        $newImage->save($pathThumb . $fileName,100);
        return $newImage->response('jpg',100);
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
}
