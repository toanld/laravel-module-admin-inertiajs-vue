<?php
namespace Modules\Admin\Helpers;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class Upload {

    public function upfile($file='')
    {
        $pathSavePicture = storage_path("app/public/uploads") . "/";
        $error = null;
        // Kiểm tra xem tệp tin có định dạng hình ảnh hay không
        $ext = strtolower($file->getClientOriginalExtension());
        $fileTime = time();
        $pathDate = date('/Y/m/');
        $fileName = $fileTime . '_' . Str::random(4) . '.' . $ext;
        if ($file->isValid() && in_array($ext,["jpg","png"])) {
            $imgFile = Image::make($file->getRealPath());
            $pathSavePic = $pathSavePicture . "fullsize" . $pathDate;
            //lưu ảnh to
            if(!file_exists($pathSavePic)) mkdir($pathSavePic, 0777, true);
            $imgFile->resize(1500, 1500, function ($constraint) {
                $constraint->aspectRatio();
            })->save($pathSavePic . $fileName);
            //lưu ảnh thumb
        }else{
            $error = "File ảnh không hợp lệ";
        }
        if($error){
             return [
                "error" => $error,
                'check' => true
            ];
        }

        $width = 600;
        $height = 400;
        $pathThub = "public/uploads/thumb/$width" . "x" . $height;
        $pathSaveThumb = $pathSavePicture  . "/thumb/";
        if(!file_exists($pathSaveThumb)) mkdir($pathSaveThumb, 0777, true);
        $imgFile->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        })->save($pathSaveThumb . "/" . $fileName);
        $url = "/" . $pathThub . "/" . $fileName;

        return [
            'name' => $fileName,
            'url'  => $url,
            "error" => $error,
            'active' => 0,
            'check' => false,
            'fullsize' => '/uploads/fullsize/'.$fileName,
            'thumb' => '/uploads/thumb/'.$fileName
        ];
    }
}


 ?>
