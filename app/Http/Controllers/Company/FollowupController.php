<?php

namespace App\Http\Controllers\Company;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /app/Http/Controllers/Company/FollowupController.php
 * Created Time: 2022-04-10 19:36:45
 * Last Edit Time: 2023-03-28 23:12:26
 * Description: 跟进记录控制器
 */

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company\Company;
use App\Models\Company\Followup;
use Illuminate\Support\Facades\Auth;

class FollowupController extends Controller
{
    //显示$id下所有的跟进记录(包括软删除的)
    public function show(Request $request)
    {
        try {
            $company = Company::find($request->id);
            throw_unless($company, new \ErrorException('客户不存在~'));
            //权限验证
            if (Auth::user()->isAdmin()) {
                $this->result['data'] = Followup::withTrashed()->where('company_id', $company->id)->orderByDesc('created_at')->get();
            } elseif (Auth::id() === $company->staff_id) {
                $this->result['data'] = Followup::where('company_id', $company->id)->orderByDesc('created_at')->get();
            } else {
                throw new \ErrorException('无权查看相关跟进记录~');
            }
            if (count($this->result['data']) === 0) {
                throw new \ErrorException('未查询到相关跟进记录~');
            } else {
                $this->result['status'] = 'success';
            }
        } catch (\Throwable $th) {
            $this->result['msg'] = $th->getMessage();
        }
        return $this->api_response();
    }

    //接收跟进记录
    public function store(Request $request)
    {
        $request->validate(['message' => 'required']);
        try {
            $company = Company::find($request->id);
            //权限验证
            throw_unless($company, new \ErrorException('客户不存在~'));
            if (Auth::id() === $company->staff_id || Auth::user()->isAdmin()) {
                Followup::create([
                    'company_id' => $company->id,
                    'staff_id' => Auth::id(),
                    'staff_name' => Auth::user()->name,
                    'username' => Auth::user()->username,
                    'message' => $request->message
                ]);
                $company->track_at = now();
                $company->save();
            }
        } catch (\Throwable $th) {
            $this->result['msg'] = $th->getMessage();
            return back()->withErrors($this->result);
        }
        return back();
    }
    /**
     * 软删除跟进日志
     */
    public function destroy(Request $request)
    {
        try {
            if (Auth::user()->isAdmin()) {
                $followup = Followup::withTrashed()->find($request->id); //管理员查询回收站
            } else {
                $followup = Followup::find($request->id);
            }
            throw_unless($followup, new \ErrorException('跟进记录不存在~'));
            //权限验证
            if (Auth::id() === $followup->staff_id || Auth::user()->isAdmin()) {
                if ($followup->trashed()) {
                    $followup->forceDelete(); //永久删除
                } else {
                    //软删除
                    if (!$followup->delete()) {
                        throw new \ErrorException('删除失败~');
                    }
                }
                $this->result['status'] = 'success';
            } else {
                throw new \ErrorException('没有删除权限~');
            }
        } catch (\Throwable $th) {
            $this->result['msg'] = $th->getMessage();
        }
        return $this->api_response();
    }
    //恢复软删除记录
    public function recycle(Request $request)
    {
        try {
            if (Auth::user()->isAdmin()) {
                $followup = Followup::withTrashed()->find($request->id); //管理员查询回收站
                throw_unless($followup, new \ErrorException('跟进记录不存在~'));
                if ($followup->trashed()) {
                    $followup->restore();
                    $this->result['status'] = 'success';
                } else {
                    throw new \ErrorException('不能恢复回收站以外的数据~');
                }
            } else {
                throw new \ErrorException('没有操作权限~');
            }
        } catch (\Throwable $th) {
            $this->result['msg'] = $th->getMessage();
        }
        return $this->api_response();
    }
}
