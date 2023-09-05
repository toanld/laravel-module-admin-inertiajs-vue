<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Kalnoy\Nestedset\NodeTrait;
use Toanld\DebugToSql\DebugToSQL;

class Category extends Model
{
    use HasFactory;
    use DebugToSQL;
    use NodeTrait;

    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = "blog_categories";
    }

    protected static function newFactory()
    {
        return \Modules\Admin\Database\factories\PostFactory::new();
    }
}
