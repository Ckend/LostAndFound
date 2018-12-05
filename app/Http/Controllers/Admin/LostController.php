<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lost;
use App\Base;
use App\Http\Controllers\Admin\SSO;

class LostController extends Controller
{
    public function index()
    {
        return Lost::all();
    }

    public function store(Request $request)
    {
        $lost = new Lost;
        $images = $request->file('file');
        $filedir="images/"; // 定义图片上传路径
        if($images){
            $extension = $images -> getClientOriginalExtension(); // 获取上传图片的后缀名
            $newImagesName=md5(time()).random_int(5,5).".".$extension; // 重新命名上传文件名字
            $images->move($filedir,$newImagesName); // 使用move方法移动文件.
            $lost->ImgPath = $filedir . $newImagesName;
        }
        if($request->get('uploadPickTimeDate')){
            $pickTime = $request->get('uploadPickTimeDate') ." ".$request->get('uploadPickTimeHour');
            $lost->pickTime = $pickTime;
        }
        if($request->get('showTime')){
            $lost->showTime = $request->get('showTime');
        }
        $lost->school = $request->get('uploadSchool');
        $lost->academy = $request->get('uploadAcademy');
        $lost->stuNumber = $request->get('uploadStuNumber');
        $lost->lostType = $request->get('uploadType');
        $lost->pickLocation = $request->get('uploadPickLocation');
        $lost->getPlace = $request->get('uploadGetPlace');
        $lost->isCard = $request->get('uploadIsCard');
        $lost->remarks = $request->get('remarks');
        $lost->stuName = $request->get('uploadName');
        if ($lost->save()) {
            $info = Base::all();
            $weibo_asyn = $info[0]['sync_weibo'];
            $weibo_token = $info[0]['weibo_token'];
            $qiyehao_asyn = $info[0]['autoInform_qiyehao'];

            $SERVER_ADDR = $info[0]['SERVER_ADDR'];
            $CLIENT_ID = $info[0]['CLIENT_ID'];
            $CLIENT_SECRET = $info[0]['CLIENT_SECRET'];

            // 企业号通知
            if ($lost->stuNumber && $qiyehao_asyn == 1){
                $sendApi = new SSO($SERVER_ADDR,$CLIENT_ID,$CLIENT_SECRET);
                $sendApi->sendMessage('失物招领通知',$lost->stuName.'同学<br>您好，您丢失的卡已被捡拾。<br>领取地点是:'.$lost->getPlace.'。<br>[校会生活服务部]<br>',$lost->stuNumber,'校学生会');
            }

            // 微博同步发布
            if ($weibo_asyn == 1 && $lost->isCard == 0){
                $ch = curl_init();
                $url='https://api.weibo.com/2/statuses/share.json';
                $status = '[失物招领]'. $lost->lostType . ' :'
                    .'，捡拾地点:' . $lost->pickLocation . '，捡拾时间:' .$lost->pickTime
                    .'，详情：' . $lost->remarks
                    .'，领取地点:' . $lost->getPlace
                    .'...详情见：https://alltoshare.com';
                $status = urlencode($status);
//                if($images){
//                    $options = array(
//                        'status' => $status,
//                        'access_token' => $weibo_token,
//                        'pic' => new \CurlFile($images, 'image/png', $newImagesName)
//                    );
//                }else{
//                    $options = "status=".$status."&access_token=".$weibo_token;
//                }
                $options = "status=".$status."&access_token=".$weibo_token;

                $timeout = 5;
                curl_setopt ($ch, CURLOPT_URL, $url);
                curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt ($ch, CURLOPT_POST, 1);
                curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
                curl_setopt ($ch, CURLOPT_POSTFIELDS, $options);
                $file_contents = curl_exec($ch);
                curl_close($ch);
                echo $file_contents;
            }
            return '保存成功';
        } else {
            return redirect()->back()->withInput()->withErrors('保存失败！');
        }
    }
    public function update($id, Request $request)
    {
        $lost = Lost::find($id);
        $images = $request->file('file');
        $filedir="images/"; // 定义图片上传路径
        if($images){
            $extension = $images -> getClientOriginalExtension(); // 获取上传图片的后缀名
            $newImagesName=md5(time()).random_int(5,5).".".$extension; // 重新命名上传文件名字
            $images->move($filedir,$newImagesName); // 使用move方法移动文件.
            $lost->ImgPath = $filedir . $newImagesName;
        }
        if($request->get('uploadPickTimeDate')){
            $pickTime = $request->get('uploadPickTimeDate') ." ".$request->get('uploadPickTimeHour');
            $lost->pickTime = $pickTime;
        }
        if($request->get('showTime')){
            $lost->showTime = $request->get('showTime');
        }
        if($request->get('uploadSchool')){
            $lost->school = $request->get('uploadSchool');
        }
        if($request->get('uploadAcademy')){
            $lost->academy = $request->get('uploadAcademy');
        }
        if($request->get('uploadStuNumber')){
            $lost->stuNumber = $request->get('uploadStuNumber');
        }
        if($request->get('uploadType')){
            $lost->lostType = $request->get('uploadType');
        }
        if($request->get('uploadPickLocation')){
            $lost->pickLocation = $request->get('uploadPickLocation');
        }
        if($request->get('uploadGetPlace')){
            $lost->getPlace = $request->get('uploadGetPlace');
        }
        if($request->get('uploadIsCard')){
            $lost->isCard = $request->get('uploadIsCard');
        }
        if($request->get('remarks')){
            $lost->remarks = $request->get('remarks');
        }
        if($request->get('uploadName')){
            $lost->stuName = $request->get('uploadName');
        }
        if($request->get('takenName')){
            $lost->takenName = $request->get('takenName');
            $lost->takenTime = date("Y-m-d");
            $lost->taken = 1;
        }
        if($request->get('takenAcademy')){
            $lost->takenAcademy = $request->get('takenAcademy');
        }
        if($request->get('takenNumber')){
            $lost->takenNumber = $request->get('takenNumber');
        }
        if($request->get('takenPhone')){
            $lost->takenPhone = $request->get('takenPhone');
        }
        if($request->get('delete')){
            $lost->delete = $request->get('delete');
        }
        if ($lost->save()) {
            return '修改成功';
        } else {
            return redirect()->back()->withInput()->withErrors('修改失败！');
        }
    }
}
