<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Admin\Helpers\Casts\CastPicture;
use Modules\Admin\Helpers\Casts\HtmlClean;
use Modules\Admin\Helpers\Casts\JsonConfig;
use Toanld\DebugToSql\DebugToSQL;

class Configuration extends Model
{
    use HasFactory;
    use DebugToSQL;

    protected $fillable = [];
    protected $casts = [
        'value' => JsonConfig::class,
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = "db_configs";
    }

    public static function getConfig(){
        if(app()->runningInConsole()) {
            // we are running in the console
            $argv = \Request::server('argv', null);
            $arrayIgnore = [
                "migrate",
                "vendor:",
                "publish",
                "package:",
                "module:",
                "optimize:",
                "make:",
            ];
            if(!isset($argv[1]) && strpos($argv[0],'artisan') !== false) {
                return [];
            }
            foreach ($arrayIgnore as $check){
                if(isset($argv[1]) && strpos($argv[0],'artisan') !== false && strpos($argv[1],$check) !== false) {
                    return [];
                }
            }
        }
        $db = self::all();
        $arrConfig = [];
        foreach ($db as $item){
            $arrConfig[$item->name] = $item->value;
        }
        return $arrConfig;
    }
}
