<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Modules\Admin\Entities\Category;
use Modules\Admin\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Modules\Admin\Entities\Category as ModelName;

class CategoryPostController extends Controller
{
    public function __construct()
    {
        Inertia::share('routeName', "categoryposts");
    }

    public function index()
    {
        $nodes = ModelName::get()->toTree();
        $listings = [];
        $traverse = function ($categories, $level = 1) use (&$traverse, &$listings) {
            if (count($categories) > 0) {
                foreach ($categories as $category) {
                    if($level > 1) {
                        $category->name = "|" . str_repeat('-', $level) . " " . $category->name;
                    }else{
                        $category->name = " <b>$category->name</b>";
                    }
                    if($category->level != $level) {
                        ModelName::where('id',$category->id)->update([
                            "level" => $level
                        ]);
                    }
                    $listings[] = $category;
                    $traverse($category->children, $level + 1);
                }
            }
        };
        $traverse($nodes);
        return Inertia::module('admin::CategoryPosts/Index', [
            'filters' => Request::all('search', 'trashed'),
            'listings' => $listings,
        ]);
    }

    public function apiSearch(){
        return \Modules\Ecommerce\Entities\Category::limit(20)->get();
    }

    public function create()
    {
        $nodes = ModelName::get()->toTree();
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
                        ModelName::where('id',$category->id)->update([
                            "level" => $level
                        ]);
                    }
                    $traverse($category->children, $level + 1);
                    $listings[] = $category;
                }
            }
        };
        $traverse($nodes);
        return Inertia::module('admin::CategoryPosts/Create',compact('listings'));
    }

    public function store(\Illuminate\Http\Request $request)
    {

        $validate = Request::validate([
            'name' => ['required', 'max:255']
        ]);
        $cat_id = $request->input('cat_id.id');
        $slug = Str::slug($validate['name']);
        $md5 = md5($slug);
        $model = new ModelName();
        $model->level = 1;
        if($cat = Category::where('id',$cat_id)->first()){
            $model->parent_id = $cat->id;
            $model->level = intval($cat->level) + 1;
        }
        $model->name = $validate['name'];
        $model->slug = $slug;
        $model->md5 = $md5;
        $model->save();
        if($model){
            $model->{"cat_" . $model->level} = $model->id;
            $model->save();
        }

        return Redirect::route('categoryposts')->with('success', 'Contact created.');
    }

    public function edit( ModelName $model)
    {
        return Inertia::module('admin::CategoryPosts/Edit', [
            'model' => $model
        ]);
    }

    public function update( ModelName $model)
    {
        $validate = Request::validate([
            'name' => ['required', 'max:255']
        ]);
        $model->name = $validate['name'];
        $model->save();
        return Redirect::route('categoryposts')->with('success', 'Data updated.');
    }

    public function destroy(ModelName $model)
    {
        $model->delete();
        return Redirect::route('categoryposts')->with('success', 'Data deleted.');
    }

    public function restore(ModelName $model)
    {
        $model->restore();
        return Redirect::back()->with('success', 'Data restored.');
    }
}
