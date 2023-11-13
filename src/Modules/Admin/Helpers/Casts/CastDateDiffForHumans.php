<?php

namespace Modules\Admin\Helpers\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class CastDateDiffForHumans implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $date = Carbon::parse($value);
        $now = Carbon::now();
        $diffInDays = $date->diffInDays($now, false); // 'false' để tránh tính giá trị tuyệt đối
        if ($diffInDays > 5 || $diffInDays < -5) {
            // Nếu khoảng cách thời gian lớn hơn 30 ngày so với hiện tại
            return $date->format('Y-m-d'); // Định dạng ngày như bạn muốn
        } else {
            // Nếu không, sử dụng diffForHumans như bình thường
            return $date->diffForHumans();
        }
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value;
    }
}
