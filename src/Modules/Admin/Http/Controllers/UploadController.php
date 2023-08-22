<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Entities\ModelExample as Admin;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Inertia\Inertia;

class UploadController extends Controller
{
    public function upload(Request $request){
        $validator = Validator::make($request->all(), [
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Đảm bảo chỉ tải lên ảnh và định dạng phù hợp
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $picture = $request->file('file');
        $extension = $picture->getClientOriginalExtension();
        $fileName = time() . '_' . Str::random(4) . '.' . $extension;
        $path = $picture->storeAs('public/uploads', $fileName);
        $path = str_replace("public/","",$path);
        return response()->json(['location'=>"/storage/$path","data" => ["fullsize" => "/storage/$path"]]);
    }
}
