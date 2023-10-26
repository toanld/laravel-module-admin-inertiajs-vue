<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Admin\Helpers\Casts\CastBlogPicture;
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
        'pictures'  => CastBlogPicture::class
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
        return $this->getPictureFileName();
    }
    public function getImgAttribute()
    {
        $img = '/storage/uploads/thumb/600/400';
        return $img;
    }

    public function getThumb($width, $height)
    {
        $fileName = $this->getPictureFileName();
        return imageGetPathThumb($fileName, $width, $height);
    }
    public function getPictureFileName()
    {
        if (is_array($this->pictures)) {
            $data = $this->pictures;
        } else {
            $data = (array)json_decode($this->pictures, true);
        }
        if (isset($data["filename"])) return $data["filename"];
        if (isset($data["name"])) return $data["name"];
        foreach ($data as $item) {
            if (is_array($item)) {
                if (isset($item["filename"])) return $item["filename"];
                if (isset($item["name"])) return $item["name"];
            }
        }
        if (empty($data)) {
            return  null;
        }
        return null;
    }
    public function getCategoryAttribute()
    {
        if ($this->cat_4 > 0) {
            $cat = Category::where('id', '=', $this->cat_4)->first();
        } elseif ($this->cat_3 > 0) {
            $cat = Category::where('id', '=', $this->cat_3)->first();
        } elseif ($this->cat_2 > 0) {
            $cat = Category::where('id', '=', $this->cat_2)->first();
        } else {
            $cat = Category::where('id', '=', $this->cat_1)->first();
        }
        return $cat;
    }
    public function getTimeAttribute()
    {
        $datePost = strtotime($this->created_at);
        $today = strtotime(date("Y-m-d H:i:s"));
        $timedif = $today - $datePost;
        $agohour = floor($timedif / (60 * 60));
        $agoday = floor($timedif / (60 * 60 * 24));
        if ($agoday == 0 && $agohour == 0) {
            return 'Đã đăng gần đây';
        } elseif ($agoday == 0 && $agohour > 0) {
            return 'Đã đăng ' . $agohour . ' giờ trước';
        } elseif ($agoday > 30) {
            'Đã đăng ' . floor($agohour / 30) . ' tháng trước';
        } elseif ($agoday > 365) {
            'Đã đăng ' . floor($agohour / 365) . ' năm trước';
        } else {
            return 'Đã đăng ' . $agoday . ' ngày trước';
        }
    }
    public function getUrlAttribute(){
        if($this->count()==0){
            return 'not_url';
        }
        return route('detail-blog',['id'=>$this->id,'slug'=>$this->slug]);
    }
}
