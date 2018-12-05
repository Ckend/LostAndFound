<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Base;
use App\User;
class HomeController extends Controller
{
    public function index(Request $request)
    {
        return view('index');
    }
}
