<?php

namespace App\Http\Controllers\web;

use App\models\mod_common;
use App\models\mod_goods_cat;
use Illuminate\Http\Request;
use App\models\mod_feedback;

class ctl_contact extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        return view('web.contact_index', []);
    }
}
