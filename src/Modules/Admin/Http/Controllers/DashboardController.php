<?php

namespace Modules\Admin\Http\Controllers;

use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::module('admin::Dashboard/Index');
    }
}
