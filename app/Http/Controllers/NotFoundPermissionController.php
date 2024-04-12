<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotFoundPermissionController extends Controller
{
    public function __invoke()
    {
        return view('not-found');
    }
}
