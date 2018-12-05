<?php

namespace App\Http\Controllers\Admin;
use Auth;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Base;
use App\place;
use App\Academy;

class superController extends Controller
{
    public function Academy(Request $request){
        $academys = $request->get('academys');
        $branch = $request->get('branch_school');
        $academys = explode(',',$academys);
        Academy::truncate();
        for ($i = 0; $i < count($academys); ++$i){
            $academy = new Academy;
            $academy->academy = $academys[$i];
            $academy->branch_school = $branch;
            $academy->save();
        }
    }
    public function ShowAcademy($branch_school){
        return Academy::where('branch_school',$branch_school)->get();
    }

    public function Place(Request $request){
        $places = $request->get('places');
        $branch = $request->get('branch_school');
        $places = explode(',',$places);
        place::truncate();
        for ($i = 0; $i < count($places); ++$i){
            $place = new place;
            $place->place = $places[$i];
            $place->branch_school = $branch;
            $place->save();
        }
    }

    public function ShowPlace($branch_school){
        return place::where('branch_school',$branch_school)->get();
    }

    public function BranchDelete(Request $request){
        $branch = $request->get('branch_school');
        User::where('name',$branch)->delete();
        return 'success';
    }

    public function baseInfo(Request $request){
        Base::truncate();
        $base = new Base;
        $base->school_name = $request->get('schoolName');
        $base->sync_weibo = $request->get('weibo_sync');
        if ($request->get('weibo_user')){
            $base->weibo_user = $request->get('weibo_user');
        }
        if ($request->get('weibo_password')){
            $base->weibo_password = $request->get('weibo_password');
        }
        if ($request->get('weibo_token')){
            $base->weibo_token = $request->get('weibo_token');
        }
        if ($request->get('autoInform_qiyehao')){
            $base->autoInform_qiyehao = $request->get('autoInform_qiyehao');
        }
        if ($request->get('SERVER_ADDR')){
            $base->SERVER_ADDR = $request->get('SERVER_ADDR');
        }
        if ($request->get('CLIENT_ID')){
            $base->CLIENT_ID = $request->get('CLIENT_ID');
        }
        if ($request->get('CLIENT_SECRET')){
            $base->CLIENT_SECRET = $request->get('CLIENT_SECRET');
        }
        $base->save();
        return 'success!';
    }

    public function getBase(){
        return Base::all();
    }

    public function logoutAndRegister(Request $request) {
        Auth::logout();
        return redirect('/register');
    }
    public function logout(Request $request) {
        Auth::logout();
        return redirect('/login');
    }
}
