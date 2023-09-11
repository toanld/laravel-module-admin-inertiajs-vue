<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Admin\Helpers\Casts\CastPicture;
use Modules\Admin\Helpers\Casts\HtmlClean;
use Toanld\DebugToSql\DebugToSQL;

class Post extends Model
{
    use HasFactory;
    use DebugToSQL;

    protected $fillable = [];
    protected $casts = [
        'description' => HtmlClean::class,
        'pictures'  => CastPicture::class
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = "blog_posts";
    }

    protected static function newFactory()
    {
        return \Modules\Admin\Database\factories\PostFactory::new();
    }

    public function getPictureAttribute()
    {
        if(is_array($this->pictures)){
            $data = $this->pictures;
        }else {
            $data = (array)json_decode($this->pictures, true);
        }
        if(isset($data["filename"])) return $data["filename"];
        if(isset($data["name"])) return $data["name"];
        foreach ($data as $item){
            if(is_array($item)){
                if(isset($item["filename"])) return $item["filename"];
                if(isset($item["name"])) return $item["name"];
            }
        }
        if(empty($data)){
            return  $this->pictures;
        }
        return null;
    }
}
