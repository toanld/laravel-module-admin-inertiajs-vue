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

    public function getLftName()
    {
        return 'left';
    }

    public function getRgtName()
    {
        return 'right';
    }

    public function getParentIdName()
    {
        return 'parent_id';
    }

    protected static function newFactory()
    {
        return \Modules\Admin\Database\factories\PostFactory::new();
    }
    public function childrent() {
        return $this->hasMany(Category::class,"parent_id","id");
    }
    public function getUrlAttribute(){
        if($this->count()==0){
            return 'not_url';
        }
        return route('category-blog',['id'=>$this->id,'slug'=>$this->slug]);
    }
}
