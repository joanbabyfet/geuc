<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;

class ctl_style extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        return view('web.style_index', []);
    }
}
