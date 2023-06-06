<?php

namespace App\Http\Controllers\Company;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /app/Http/Controllers/Company/AppointmentController.php
 * Created Time: 2022-04-10 19:36:45
 * Last Edit Time: 2023-03-29 17:41:10
 * Description: 预约提醒控制器
 */

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company\Company;
use App\Models\Company\Appointment;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; //时间包
class AppointmentController extends Controller
{
    //显示$id下所有的预约记录(包括软删除的)
    public function show(Request $request)
    {
        try {
            $company = Company::find($request->id);
            throw_unless($company, new \ErrorException('客户不存在~'));
            //权限验证
            if (Auth::user()->isAdmin()) {
                $this->result['data'] = Appointment::withTrashed()->where('company_id', $company->id)->orderByDesc('track_at')->get();
            } elseif (Auth::id() === $company->staff_id) {
                $this->result['data'] = Appointment::where('company_id', $company->id)->orderByDesc('track_at')->get();
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

    //显示$user_id下所有的预约记录(提醒)
    public function tips()
    {
        try {
            $appaointments = Appointment::where('staff_id', Auth::id())
                ->where('status', 0)
                ->orderByDesc('track_at')
                ->get();
            foreach ($appaointments as $appaointment) {
                if (Carbon::create($appaointment->track_at)->isToday()) {
                    $appaointment->company; //反查公司信息
                    $this->result['data'][] = $appaointment;
                }
            }
            if (count($this->result['data']) > 0) {
                $this->result['status'] = 'success';
            }
        } catch (\Throwable $th) {
            $this->result['msg'] = $th->getMessage();
        }
        return $this->api_response();
    }

    //设置预约记录状态
    public function status(Request $request)
    {
        try {
            $appaointment = Appointment::find($request->id);
            throw_unless($appaointment, new \ErrorException('预约记录不存在~'));
            if (Auth::id() === $appaointment->staff_id || Auth::user()->isAdmin()) {
                $appaointment->status = $request->status ? 1 : 0;
                $appaointment->save();
                $this->result['status'] = 'success';
            } else {
                throw new \ErrorException('您无权处理当前客户数据~');
            }
        } catch (\Throwable $th) {
            $this->result['msg'] = $th->getMessage();
        }
        return $this->api_response();
    }
    //接收跟进记录
    public function store(Request $request)
    {
        $request->validate(['message' => 'required', 'next_appointment_time' => 'required|date']);
        try {
            $company = Company::find($request->id);
            //权限验证
            throw_unless($company, new \ErrorException('客户不存在~'));
            if (Auth::id() === $company->staff_id || Auth::user()->isAdmin()) {
                Appointment::create([
                    'company_id' => $company->id,
                    'staff_id' => $company->staff_id, //客户要求管理员可以帮助业务员预约，这里的UID应该是业务员的。
                    'staff_name' => Auth::user()->name, //记录管理员名字
                    'username' => Auth::user()->username, //为了判断是谁预约的，这里用户名依然是真实的。
                    'message' => $request->message,
                    'track_at' => $request->next_appointment_time,
                    'status' => 0
                ]);
            }
        } catch (\Throwable $th) {
            $this->result['msg'] = $th->getMessage();
            return back()->withErrors($this->result);
        }
        return back();
    }
    /**
     * 软删除预约记录
     */
    public function destroy(Request $request)
    {
        try {
            if (Auth::user()->isAdmin()) {
                $appaointment = Appointment::withTrashed()->find($request->id); //管理员查询回收站
            } else {
                $appaointment = Appointment::find($request->id);
            }
            throw_unless($appaointment, new \ErrorException('预约记录不存在~'));
            //权限验证
            if (Auth::id() === $appaointment->staff_id || Auth::user()->isAdmin()) {
                if ($appaointment->trashed()) {
                    $appaointment->forceDelete(); //永久删除
                } else {
                    //软删除
                    if (!$appaointment->delete()) {
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
                $appaointment = Appointment::withTrashed()->find($request->id); //管理员查询回收站
                throw_unless($appaointment, new \ErrorException('跟进记录不存在~'));
                if ($appaointment->trashed()) {
                    $appaointment->restore();
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
