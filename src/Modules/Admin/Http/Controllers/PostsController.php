<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Modules\Admin\Entities\Post as ModelName;

class PostsController extends Controller
{
    public function index()
    {
        return Inertia::module('admin::Posts/Index', [
            'filters' => Request::all('search', 'trashed'),
            'posts' => ModelName::paginate(10),
        ]);
    }

    public function create()
    {
        return Inertia::module('admin::Posts/Create');
    }

    public function store()
    {
        $validate = Request::validate([
            'name' => ['required', 'max:255'],
            'teaser' => ['required', 'max:500'],
            'description' => ['required'],
        ]);
        $slug = Str::slug($validate['name']);
        $md5 = md5($slug);
        $model = new ModelName();
        $model->name = $validate['name'];
        $model->slug = $slug;
        $model->md5 = $md5;
        $model->teaser = $validate['teaser'];
        $model->description = $validate['description'];
        $model->save();

        return Redirect::route('posts')->with('success', 'Contact created.');
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
        ]);
        $model->name = $validate['name'];
        $model->teaser = $validate['teaser'];
        $model->description = $validate['description'];
        $model->save();
        return Redirect::route('posts')->with('success', 'Contact updated.');
    }

    public function destroy(ModelName $model)
    {
        $model->delete();
        return Redirect::route('posts')->with('success', 'Contact deleted.');
    }

    public function restore(ModelName $model)
    {
        $model->restore();
        return Redirect::back()->with('success', 'Contact restored.');
    }
}