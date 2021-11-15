<?php

namespace App\Http\Controllers\web;

use App\models\mod_goods_cat;
use App\models\mod_h5;
use Illuminate\Http\Request;

class ctl_about extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        return view('web.about_index', []);
    }
}
