<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Toanld\DebugToSql\DebugToSQL;

class Post extends Model
{
    use HasFactory;
    use DebugToSQL;

    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = "posts";
    }

    protected static function newFactory()
    {
        return \Modules\Admin\Database\factories\PostFactory::new();
    }
}
