<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Base;
use App\place;
use App\Academy;

class AdminController extends Controller
{
    public function index(Request $request){
        if ($request->user()['name'] == 'admin'){
            return view('admin/super')->with('user', \App\User::where('name','!=','admin')->get())->with('base', \App\User::where('name','!=','admin')->get());
        }
        else{
            return view('admin/branch_admin');
        }
    }
    public function getBranchInfo(Request $request){
        $Data = array();
        array_push($Data,User::where('name',$request->user()['name'])->first(['name']));
        array_push($Data,Academy::where('branch_school',$request->user()['name'])->get(['academy']));
        array_push($Data,place::where('branch_school',$request->user()['name'])->get(['place']));
        array_push($Data,Base::all(['school_name']));
        return $Data;
    }
    public function taken(){
        // 渲染后台领取人页面
        return view('admin/branch_taken');
    }
}
