<?php

namespace Modules\Admin\Helpers\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class CastBlogPicture implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $value = (array) json_decode($value, true);
        foreach ($value as $k  => $val){
            if(isset($val["filename"]) && !isset($val["thumb"])){
                $val["thumb"] = imageGetPathThumb($val["filename"], 600, 600);
                $value[$k] = $val;
            }
        }
        return $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if(empty($value)) {
            $contentPics = (array)mymodule()->getVar('post_pictures');
            dd($contentPics);
            $pictures = [];
            foreach ($contentPics as $pic) {
                $filename = explode("/", $pic);
                $filename = end($filename);
                $pictures[] = [
                    "filename" => $filename,
                    'thumb' => imageGetPathThumb($filename, 600, 600)
                ];
            }
            return json_encode($pictures);
        }
        if(is_array($value)){
            return json_encode($value);
        }
        return $value;

    }
}
