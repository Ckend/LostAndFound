<?php

namespace App\Http\Controllers;

use App\Lost;
use App\Base;
use App\User;
use Illuminate\Http\Request;

class Api_Data extends Controller
{
    // 返回数据库失物数据
    public function callBackLostData($pos, $limit, $school)
    {
        $Data = array();
        array_push($Data, Lost::where(['taken' => 0, 'delete' => 0, 'school' => $school])
            ->where('showTime', '<=', date("Y-m-d"))->orderBy('created_at', 'desc')->skip($pos)->take($limit)->get());
        array_push($Data, Lost::where(['taken' => 0, 'delete' => 0, 'school' => $school])->count());
        return $Data;
    }

    // 返回数据库所有失物数据
    public function callBackLostDataAll($school)
    {
        $Data = array();
        array_push($Data, Lost::where(['taken' => 0, 'delete' => 0, 'school' => $school])
            ->where('showTime', '<=', date("Y-m-d"))->orderBy('created_at', 'desc')->get());
        array_push($Data, Lost::where(['taken' => 0, 'delete' => 0, 'school' => $school])->count());
        return $Data;
    }

    // 返回数据库领取人信息
    public function callBackRecipientData($pos, $limit, $school)
    {
        $Data = array();
        array_push($Data, Lost::where(['taken' => 1, 'delete' => 0, 'school' => $school])->orderBy('created_at', 'desc')->skip($pos)->take($limit)->get());
        array_push($Data, Lost::where(['taken' => 1, 'delete' => 0, 'school' => $school])->count());
        return $Data;
    }

    // 返回搜索数据
    public function callBackSearchData($words, $pos, $limit, $school)
    {
        $Data = array();
        array_push($Data, Lost::Where(['taken' => 0, 'delete' => 0, 'school' => $school])
            // AND
            ->Where(function ($query) use ($words) {
                $query->Where('lostType', 'like', '%' . $words . '%')
                    ->orWhere(function ($query) use ($words) {
                        $query->where('remarks', 'like', '%' . $words . '%');
                    })
                    ->orWhere(function ($query) use ($words) {
                        $query->where('stuNumber', 'like', '%' . $words . '%');
                    })
                    ->orWhere(function ($query) use ($words) {
                        $query->where('getPlace', 'like', '%' . $words . '%');
                    })
                    ->orWhere(function ($query) use ($words) {
                        $query->where('stuName', 'like', '%' . $words . '%');
                    });
            })->orderBy('created_at', 'desc')->skip($pos)->take($limit)->get());
        array_push($Data, Lost::Where(['taken' => 0, 'delete' => 0, 'school' => $school])
            ->Where(function ($query) use ($words) {
                $query->Where('lostType', 'like', '%' . $words . '%')
                    ->orWhere(function ($query) use ($words) {
                        $query->where('remarks', 'like', '%' . $words . '%');
                    })
                    ->orWhere(function ($query) use ($words) {
                        $query->where('stuNumber', 'like', '%' . $words . '%');
                    })
                    ->orWhere(function ($query) use ($words) {
                        $query->where('getPlace', 'like', '%' . $words . '%');
                    })
                    ->orWhere(function ($query) use ($words) {
                        $query->where('stuName', 'like', '%' . $words . '%');
                    });
            })->count());
        return $Data;
    }

    public function callBacksearchRecipientData($words, $pos, $limit, $school)
    {
        $Data = array();
        array_push($Data, Lost::Where(['taken' => 1, 'delete' => 0, 'school' => $school])
            ->Where(function ($query) use ($words) {
                $query->Where('lostType', 'like', '%' . $words . '%')
                    ->orWhere(function ($query) use ($words) {
                        $query->where('takenName', 'lile', '%' . $words . '%');
                    })
                    ->orWhere(function ($query) use ($words) {
                        $query->where('takenNumber', 'like', '%' . $words . '%');
                    })
                    ->orWhere(function ($query) use ($words) {
                        $query->where('takenPhone', 'like', '%' . $words . '%');
                    });
            })->orderBy('created_at', 'desc')->skip($pos)->take($limit)->get());
        array_push($Data, Lost::Where(['taken' => 1, 'delete' => 0, 'school' => $school])
            ->Where(function ($query) use ($words) {
                $query->Where('lostType', 'like', '%' . $words . '%')
                    ->orWhere(function ($query) use ($words) {
                        $query->where('takenName', 'lile', '%' . $words . '%');
                    })
                    ->orWhere(function ($query) use ($words) {
                        $query->where('takenNumber', 'like', '%' . $words . '%');
                    })
                    ->orWhere(function ($query) use ($words) {
                        $query->where('takenPhone', 'like', '%' . $words . '%');
                    });
            })->count());
        return $Data;
    }

    public function getBase()
    {
        // 公开的
        $Data = array();
        array_push($Data, Base::all(['school_name', 'found'])[0]);
        array_push($Data, User::where('name', '!=', 'admin')->get(['name']));
        return $Data;
    }
}
