<?php

namespace Modules\Admin\Entities;

use App\Traits\MenuTree;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Toanld\DebugToSql\DebugToSQL;

class StaticPage extends Model
{
    use HasFactory;
    //use DebugToSQL;

    protected $fillable = [];
    protected $queryCallback;
    public $orderColumn = 'length';
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = "statics";
    }

    public function getUrlAttribute()
    {
        return route('static',['slug' => $this->slug]);
    }

}
