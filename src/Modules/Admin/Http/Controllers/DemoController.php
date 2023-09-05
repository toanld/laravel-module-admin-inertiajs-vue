<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Modules\Admin\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Modules\Admin\Entities\Post as ModelName;

class DemoController extends Controller
{
    public function __construct()
    {
        Inertia::share('routeName', "demo");
    }

    public function index()
    {
        return Inertia::module('admin::Demo/Index', [
            'filters' => Request::all('search', 'trashed'),
            'listings' => ModelName::paginate(10),
        ]);
    }

    public function api(){
        $arrayReturn = [
            [
                "id" => 1,
                "name" => "Gợi ý 1"
            ],
            [
                "id" => 2,
                "name" => "Gợi ý 2"
            ],
            [
                "id" => 3,
                "name" => "Gợi ý 3"
            ]
        ];
        return $arrayReturn;
    }

    public function create()
    {
        return Inertia::module('admin::Demo/Create');
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $validate = Request::validate([
            'inputext' => ['required', 'max:255'],
            'fileinput' => ['required', 'max:255'],
            'typeeditor' => ['required', 'max:255'],
            'textarea' => ['required', 'max:255'],
        ]);
        dd($request->all());

        return Redirect::route('demo')->with('success', 'Contact created.');
    }

    public function edit( ModelName $model)
    {
        return Inertia::module('admin::Demo/Edit', [
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
        return Redirect::route('demo')->with('success', 'Data updated.');
    }

    public function destroy(ModelName $model)
    {
        $model->delete();
        return Redirect::route('demo')->with('success', 'Data deleted.');
    }

    public function restore(ModelName $model)
    {
        $model->restore();
        return Redirect::back()->with('success', 'Data restored.');
    }
}
