<?php

namespace Modules\Admin\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Modules\Admin\Entities\Category;
use Modules\Admin\Entities\Post;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SeedExampleDb extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'post:example';
    protected $arrayWord = [];
    protected $totalWord = 0;

    /**
     * The console command description.
     */
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //print_r($this->createRandImage());
        $this->createExample();
        //
    }

    public function createExample(){
        $arrayData = [
            [
                "id"   => 1,
                "name" => "Pages",
                'parent_id' => 0,
                'cat_1' => 1,
                'cat_2' => 0,
                'cat_3' => 0,
                'level' => 1,
            ],
            [
                "id"   => 2,
                "name" => "Home page",
                'parent_id' => 1,
                'cat_1' => 1,
                'cat_2' => 2,
                'cat_3' => 0,
                'level' => 2,
            ],
            [
                "id"   => 3,
                "name" => "Home page 1",
                'parent_id' => 2,
                'cat_1' => 1,
                'cat_2' => 2,
                'cat_3' => 3,
                'level' => 3,
            ],
            [
                "id"   => 4,
                "name" => "Home page 2",
                'parent_id' => 2,
                'cat_1' => 1,
                'cat_2' => 2,
                'cat_3' => 4,
                'level' => 3,
            ],
            [
                "id"   => 5,
                "name" => "Page",
                'parent_id' => 1,
                'cat_1' => 1,
                'cat_2' => 5,
                'cat_3' => 0,
                'level' => 2,
            ],
            [
                "id"   => 6,
                "name" => "Post",
                'parent_id' => 1,
                'cat_1' => 1,
                'cat_2' => 6,
                'cat_3' => 0,
                'level' => 2,
            ],
            [
                "id"   => 7,
                "name" => "Sport",
                'parent_id' => 0,
                'cat_1' => 7,
                'cat_2' => 0,
                'cat_3' => 0,
                'level' => 1,
            ],
            [
                "id"   => 8,
                "name" => "Travel",
                'parent_id' => 0,
                'cat_1' => 8,
                'cat_2' => 0,
                'cat_3' => 0,
                'level' => 1,
            ],
            [
                "id"   => 9,
                "name" => "Techno",
                'parent_id' => 0,
                'cat_1' => 9,
                'cat_2' => 0,
                'cat_3' => 0,
                'level' => 1,
            ],
            [
                "id"   => 10,
                "name" => "Worklife",
                'parent_id' => 0,
                'cat_1' => 10,
                'cat_2' => 0,
                'cat_3' => 0,
                'level' => 1,
            ],
            [
                "id"   => 11,
                "name" => "Worklife 1",
                'parent_id' => 10,
                'cat_1' => 10,
                'cat_2' => 11,
                'cat_3' => 0,
                'level' => 2,
            ],
            [
                "id"   => 12,
                "name" => "Worklife 2",
                'parent_id' => 10,
                'cat_1' => 10,
                'cat_2' => 12,
                'cat_3' => 0,
                'level' => 2,
            ],
            [
                "id"   => 13,
                "name" => "Future",
                'parent_id' => 0,
                'cat_1' => 13,
                'cat_2' => 0,
                'cat_3' => 0,
                'level' => 1,
            ]
        ];
        foreach ($arrayData as $item){
            if(!Category::find($item["id"])) {
                $m = new Category();
                $m->id = $item["id"];
                $m->name = $item["name"];
                $m->slug = Str::slug($item["name"]);
                $m->md5 = md5($m->slug);
                $m->parent_id = intval($item["parent_id"]);
                $m->level = $item["level"];
                $m->cat_1 = $item["cat_1"];
                $m->cat_2 = $item["cat_2"];
                $m->cat_3 = $item["cat_3"];
                $m->save();
                if($m){
                    for($i = 1; $i<15; $i++) {
                        $post = new Post();
                        $post->name = $this->createRandomText(rand(7, 13));
                        $post->slug = Str::slug($post->name);
                        $post->md5 = md5($post->slug);
                        $post->length = strlen($post->slug);
                        $post->teaser = $this->createRandomText(rand(18, 30));
                        $post->pictures = $this->createRandImage(2);
                        $description = '<p>' . $this->createRandomText(rand(30, 60)) . '</p>';
                        $description .= '<h2>' . $this->createRandomText(rand(5, 8)) . '</h2>';
                        $description .= '<p>' . $this->createRandomText(rand(50, 150)) . '</p>';
                        $description .= '<h2>' . $this->createRandomText(rand(5, 8)) . '</h2>';
                        $description .= '<p>' . $this->createRandomText(rand(50, 300)) . '</p>';
                        $description .= '<h2>' . $this->createRandomText(rand(5, 8)) . '</h2>';
                        $description .= '<p>' . $this->createRandomText(rand(50, 300)) . '</p>';
                        $description .= '<h2>' . $this->createRandomText(rand(5, 8)) . '</h2>';
                        $description .= '<p>' . $this->createRandomText(rand(50, 300)) . '</p>';
                        $description .= '<h2>' . $this->createRandomText(rand(5, 8)) . '</h2>';
                        $description .= '<p>' . $this->createRandomText(rand(50, 300)) . '</p>';
                        $post->description = $description;
                        $post->cat_1 = $m->cat_1;
                        $post->cat_2 = $m->cat_2;
                        $post->cat_3 = $m->cat_3;
                        $post->status = 2;
                        $post->save();
                    }
                }
            }
        }

    }

    function createRandImage($numPic = 1){
        $path = base_path("Modules/Admin/Database/contents/images");
        $arrFile = glob("$path/*.jpg");
        shuffle($arrFile);
        $fileTime = time();
        $i = 0;
        $arrayReturn = [];
        foreach ($arrFile as $filename) {
            if($i >= $numPic) break;
            $i++;
            echo "copy $filename\n";
            $newName = $fileTime . "_" . Str::random(10). ".jpg";
            $pathSave = storage_path("app/public") . "/uploads/" . date("Y/m/",$fileTime)  . $newName;
            if(!file_exists(dirname($pathSave))) mkdir(dirname($pathSave), 0777, true);
            copy($filename,$pathSave);
            $arrayReturn[] = [
                "filename" => $newName,
                "thumb"  => imageGetPathThumb($newName, 600, 600)
            ];
        }
        return $arrayReturn;
    }

    function createRandomText($numWord = 10){
        if(empty($this->arrayWord)){
            $specialChars = ['!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '_', '+', '=', '{', '}', '[', ']', ':', ';', '"', '\'', '<', '>', '?', '/', '\\', '|', '`', '~'];
            $stringText = removeHTML(file_get_contents(base_path("Modules/Admin/Database/contents/text.html")));
            $stringText = str_replace([chr(9),chr(10),chr(13)],"",$stringText);
            $stringText = str_replace($specialChars," ",$stringText);
            $stringText = trim(str_replace(["  ","  ","  ","  ","  ","  ","  ","  ","  ","  ","  ","  ","  ","  ","  ","  ","     "]," ",$stringText));
            $this->arrayWord = explode(" ",$stringText);
            $this->totalWord = count($this->arrayWord) - 1;
        }
        $arrTemp = [];
        for($i = 0; $i < $numWord; $i++){
            $arrTemp[] = $this->arrayWord[rand(0,$this->totalWord)];
        }
        return ucfirst(implode(" ",$arrTemp));
    }

    /**
     * Get the console command arguments.
     */
    protected function getArguments(): array
    {
        return [
            //['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     */
    protected function getOptions(): array
    {
        return [
           // ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
