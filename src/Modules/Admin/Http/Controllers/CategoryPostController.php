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
        return Inertia::module('admin::CategoryPosts/Index', [
            'filters' => Request::all('search', 'trashed'),
            'listings' => ModelName::paginate(10),
        ]);
    }

    public function apiSearch(){
        return \Modules\Ecommerce\Entities\Category::limit(20)->get();
    }

    public function create()
    {
        return Inertia::module('admin::CategoryPosts/Create');
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $validate = Request::validate([
            'name' => ['required', 'max:255']
        ]);
        $slug = Str::slug($validate['name']);
        $md5 = md5($slug);
        $model = new ModelName();
        $model->name = $validate['name'];
        $model->slug = $slug;
        $model->md5 = $md5;
        $model->save();

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
