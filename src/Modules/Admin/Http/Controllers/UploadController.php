<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Modules\Admin\Helpers\Upload;
use Modules\Admin\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Entities\ModelExample as Admin;
use Inertia\Inertia;


class UploadController extends Controller
{
    public function upload(Request $request){
        $files = array_keys($request->allFiles());
        $arrValidate = [];
        foreach ($files as $file){
            $arrValidate[$file] = 'required|image|mimes:jpeg,png,jpg,gif|max:204800';
        }
        //$validator = Validator::make($request->all(), $arrValidate);

        $validate = \Illuminate\Support\Facades\Request::validate($arrValidate);
        $arrData = [];
        foreach ($files as $file) {
            $picture = $request->file($file);
            $extension = $picture->getClientOriginalExtension();
            $fileTime = time();
            $pathTime = date("Y/m", $fileTime);
            $fileName = $fileTime . '_' . Str::random(4) . '.' . $extension;
            $picture->storeAs('public/uploads/' . $pathTime, $fileName);
            $arrData[] =  [
                "filename" => $fileName,
                "fullsize" => imageGetPathFullsize($fileName),
                "thumb" => imageGetPathThumb($fileName,600,400)
            ];
        }
        return response()->json(['location'=>imageGetPathFullsize($fileName),"data" => $arrData]);
    }


    public function getLastUpload($limit){
        $folder = null;
        $path = storage_path("app/public/uploads");
        for($i = 12; $i > 0; $i--){
            if($i < 10) $i = "0$i";

            if(is_dir($path . "/" . date("Y") . "/$i")){
                $folder = date("Y") . "/$i";
                break;
            }
        }
        $arrData = [];
        $fileName = null;
        if(!empty($folder)){
            $path = $path . "/$folder";
            $files = $this->scanFilesInDirectory($path,$limit);
            foreach ($files as $file){
                if(empty($fileName)) $fileName = $file;
                $arrData[] =  [
                    "filename" => $file,
                    "fullsize" => imageGetPathFullsize($file),
                    "thumb" => imageGetPathThumb($file,600,400)
                ];
            }
        }
        if(!empty($arrData)){
            $arrData = ['location'=>imageGetPathFullsize($fileName),"data" => $arrData];
        }
        return response()->json($arrData);;
    }

    function scanFilesInDirectory($directory, $limit) {
        $fileList = scandir($directory,1);
        $scannedFiles = [];

        $fileCount = 0;
        foreach ($fileList as $file) {
            if ($fileCount >= $limit) {
                break;
            }
            if ($file !== '.' && $file !== '..' && is_file($directory . '/' . $file)) {
                $scannedFiles[] = $file;
                $fileCount++;
            }
        }

        return $scannedFiles;
    }

         /**
     * destroy images  in storage.
     * @param Request $request
     * @return Renderable
     */
    public function destroy(Request $request)
    {
        $picture_name = $request->get('name');
        if (\Storage::disk('public')->exists($picture_name)) {
            \Storage::disk('public')->delete($picture_name);
        }else{
            return response()->json([
               'message' => 'Không tìm thấy tên ảnh'
            ],410);
        }
        return response()->json([
           'message' => 'Xóa thành công'
        ]);
    }

}
