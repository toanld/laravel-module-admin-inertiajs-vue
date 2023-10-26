<?php

namespace Modules\Admin\Helpers\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class JsonConfig implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $arr = json_decode($value, true);
        if(empty($arr)){
            return $value;
        }else{
           return $arr;
        }
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if(is_array($value)){
            return json_encode($value);
        }
        return $value;
    }
}
