<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Admin\Helpers\Casts\CastPicture;
use Modules\Admin\Helpers\Casts\HtmlClean;
use Toanld\DebugToSql\DebugToSQL;

class Configuration extends Model
{
    use HasFactory;
    use DebugToSQL;

    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = "db_configs";
    }

    protected static function newFactory()
    {
        return \Modules\Admin\Database\factories\PostFactory::new();
    }
}
