<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Modules\Admin\Entities\StaticPage as ModelName;

class StaticController extends Controller
{
    public function __construct()
    {
        Inertia::share('routeName', "statics");
    }

    public function index()
    {
        return Inertia::module('admin::Statics/Index', [
            'filters' => Request::all('search', 'trashed'),
            'listings' => ModelName::paginate(10),
        ]);
    }

    public function create()
    {
        return Inertia::module('admin::Statics/Create');
    }

    public function store()
    {
        $validate = Request::validate([
            'name' => ['required', 'max:255'],
            'description' => ['required']
        ]);
        $slug = Str::slug($validate['name']);
        $md5 = md5($slug);
        $model = new ModelName();
        $model->name = $validate['name'];
        $model->description = $validate['description'];
        $model->slug = $slug;
        $model->md5 = $md5;
        $model->save();

        return Redirect::route('statics')->with('success', 'Contact created.');
    }

    public function edit( ModelName $model)
    {
        return Inertia::module('admin::Statics/Edit', [
            'model' => $model
        ]);
    }

    public function update( ModelName $model)
    {
        $validate = Request::validate([
            'name' => ['required', 'max:255'],
            'description' => ['required']
        ]);
        $model->name = $validate['name'];
        $model->description = $validate['description'];
        $model->save();
        return Redirect::route('statics')->with('success', 'Data updated.');
    }

    public function destroy(ModelName $model)
    {
        $model->delete();
        return Redirect::route('statics')->with('success', 'Data deleted.');
    }

    public function restore(ModelName $model)
    {
        $model->restore();
        return Redirect::back()->with('success', 'Data restored.');
    }
}
