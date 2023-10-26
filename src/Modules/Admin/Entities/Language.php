<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Language extends Model
{
    use HasFactory;

    protected $table = 'languages';

    protected $fillable = ["json_default", "json_translate"];

    public static $listFields = ["id", "json_default", "json_translate", "created_at", "updated_at"];

    protected $casts = [

    ];
}
