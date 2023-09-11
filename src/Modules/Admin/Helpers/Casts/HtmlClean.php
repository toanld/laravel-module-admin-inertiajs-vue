<?php

namespace Modules\Admin\Helpers\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Helpers\Classes\HtmlCleanUp;

class HtmlClean implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $value = convertToUnicode($value);
        $html = new HtmlCleanUp($value,true);
        $html->clean();
        $value = $html->output_html;
        mymodule()->setVar('post_pictures',array_values($html->arrayPictures));
        return $value;
    }
}
