<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Modules\Admin\Entities\Category;
use Modules\Admin\Entities\Post as ModelName;
use Modules\Admin\Helpers\Classes\HtmlCleanUp;

class PostsController extends Controller
{
    public function api(\Illuminate\Http\Request $request){
        $title = $request->input('title');
        $content = $request->input('content');
        $teaser = $request->input('intro');
        $secret = $request->input('secret');
        if($secret != config('db.secret_post_api')){
            return [
                "success" => false,
                "post_id" => "Error secret"
            ];
        }
        $slug = Str::slug($title);
        $md5 = md5($slug);
        $model = new ModelName();
        $model->name = $title;
        $model->slug = $slug;
        $model->md5 = $md5;
        $model->length = strlen($slug);
        $model->teaser = $teaser;
        $model->description = $content;
        $model->pictures = [];
        $model->status = 1;
        if(empty($title) || empty($content) || empty($teaser)){
            return [
                "success" => false,
                "post_id" => "empty data post"
            ];
        }else{
            $model->save();
            return [
                "success" => true,
                "data" => [
                    "post_id" => $model->id
                ]

            ];
        }

    }
    public function index()
    {
        return Inertia::module('admin::Posts/Index', [
            'filters' => Request::all('search', 'trashed'),
            'posts' => ModelName::orderBy('updated_at','DESC')->paginate(30),
        ]);
    }

    public function create()
    {
        $nodes = \Modules\Admin\Entities\Category::get()->toTree();
        $listings = [];
        $traverse = function ($categories, $level = 1) use (&$traverse, &$listings) {
            if (count($categories) > 0) {
                foreach ($categories as $category) {
                    if($level > 1) {
                        $category->name = "|" . str_repeat('-', $level) . " " . $category->name;
                    }else{
                        $category->name = "$category->name";
                    }
                    if($category->level != $level) {
                        Category::where('id',$category->id)->update([
                            "level" => $level
                        ]);
                    }
                    $listings[] = [
                        "id" => $category->id,
                        "name" => $category->name
                    ];
                    $traverse($category->children, $level + 1);
                }
            }
        };
        $traverse($nodes);
        return Inertia::module('admin::Posts/Create',["categories" => $listings]);
    }

    public function store(\Illuminate\Http\Request $request)
    {
        //dd($request->all());
        $category = Category::find($request->input('category.id'));
        $validate = Request::validate([
            'name' => ['required', 'max:255'],
            'teaser' => ['required', 'max:500'],
            'description' => ['required'],
            'pictures' => ['array'],
            'status' => ['boolean'],
        ]);

        $slug = Str::slug($validate['name']);
        $md5 = md5($slug);
        $model = new ModelName();
        $model->name = $validate['name'];
        $model->slug = $slug;
        $model->md5 = $md5;
        if($category){
            $model->cat_1 = $category->cat_1;
            $model->cat_2 = $category->cat_2;
            $model->cat_3 = $category->cat_3;
            $model->cat_4 = $category->cat_4;
        }
        $model->length = strlen($slug);
        $model->teaser = $validate['teaser'];
        $model->description = $validate['description'];
        $model->use_id = Auth::user()->id;
        $model->status = $validate['status'] ? 1 : 0;

        $pictures = [];
        foreach ($validate['pictures'] as $pic){
            $pictures[] = [
                "filename" => $pic["filename"],
                "thumb" => $pic["thumb"]
            ];
        }
        $model->pictures = json_encode($pictures);
        $model->save();

        return Redirect::route('posts')->with('success', 'Data created.');
    }

    public function edit( ModelName $model)
    {
        return Inertia::module('admin::Posts/Edit', [
            'post' => $model
        ]);
    }

    public function update( ModelName $model)
    {
        $validate = Request::validate([
            'name' => ['required', 'max:255'],
            'teaser' => ['required', 'max:500'],
            'description' => ['required'],
            'pictures' => ['array'],
            'status' => ['boolean'],
        ]);
        $slug = Str::slug($validate['name']);
        $md5 = md5($slug);
        $model->name = $validate['name'];
        $model->slug = $slug;
        $model->md5 = $md5;
        $model->length = strlen($slug);
        $model->teaser = $validate['teaser'];
        $model->description = $validate['description'];
        $model->use_id = Auth::user()->id;
        $model->status = $validate['status'] ? 1 : 0;
        $pictures = [];
        foreach ($validate['pictures'] as $pic){
            $pictures[] = [
                "filename" => $pic["filename"],
                "thumb" => $pic["thumb"]
            ];
        }

        $model->pictures = json_encode($pictures);

        $model->save();
        return Redirect::route('posts')->with('success', 'Data updated.');
    }

    public function destroy(ModelName $model)
    {
        $model->delete();
        return Redirect::route('posts')->with('success', 'Data deleted.');
    }

    public function restore(ModelName $model)
    {
        $model->restore();
        return Redirect::back()->with('success', 'Data restored.');
    }
}
