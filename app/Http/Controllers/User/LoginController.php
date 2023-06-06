<?php

namespace App\Http\Controllers\User;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /app/Http/Controllers/User/LoginController.php
 * Created Time: 2022-04-10 19:36:45
 * Last Edit Time: 2023-05-30 21:58:40
 * Description: 员工登陆、注销控制器
 */

use App\Http\Controllers\Controller; //控制器基类
use Illuminate\Http\Request; //用户输入接收
use Illuminate\Support\Facades\Auth; //验证用户与退出
use App\Models\Staff\StaffLoginLog; //登陆日志
use App\Models\Staff\Staff; //员工
use App\Models\System\Session; //session
class LoginController extends Controller
{
    //显示登陆的View
    public function __invoke()
    {
        return $this->view('auth.login');
    }

    //验证登陆信息
    public function store(Request $request)
    {
        $rules = [
            'username' => ['required', 'string'],
            'password' => ['required', 'string']
        ];
        if ($this->app['captcha']) {
            $rules['captcha'] = 'required|captcha'; //系统设置开启验证码
        }
        $credentials = $request->validate($rules);
        $loginLog = StaffLoginLog::create(['ip' => $request->ip(), 'staff_id' => 0, 'username' => $request->username, 'password' => $request->password]);
        unset($credentials['captcha']); //删除验证码
        //校验用户名密码(并设置持续在线)
        if (Auth::attempt($credentials, true)) {
            $request->session()->regenerate(); //生成一个新的会话标识符。            
            $loginLog->update(['staff_id' => Auth::user()->id, 'password' => '']); //登陆成功的记录
            $staff = Staff::find(Auth::user()->id);
            $login_count = StaffLoginLog::where('staff_id', $staff->id)->count(); //登陆次数
            $staff->login_count = $staff->login_count < $login_count ? $login_count : $staff->login_count; //登陆次数对比
            $staff->login_at = now(); //登陆时间
            $staff->last_login_ip = $request->ip(); //登陆IP
            //更新个人信息
            $staff->customers_count = \App\Models\Company\Company::where('staff_id', $staff->id)->count(); //客户数
            $staff->contacts_count = \App\Models\Company\Contact::where('staff_name', $staff->name)->count(); //联系人数
            $staff->follows_count = \App\Models\Company\Followup::where('staff_id', $staff->id)->count(); //跟进数
            $staff->appointments_count = \App\Models\Company\Appointment::where('staff_id', $staff->id)->where('status', 0)->count(); //预约数
            $staff->save();
            //写入session
            Session::firstOrCreate([
                'staff_id' => $staff->id,
                'username' => $staff->username,
                'ip' => $request->ip(),
                'token' => $request->session()->getId()
            ], [
                'abilities' =>  '[]',
                'route' =>  ''
            ]);

            return redirect()->intended(\App\Providers\RouteServiceProvider::HOME); //跳转到用户首页
        }
        return back()->withErrors([
            'username' => '登陆认证失败，请检查您的输入信息！',
        ])->onlyInput('username');
    }

    //退出登陆
    public function destroy(Request $request)
    {
        Session::where('token', $request->session()->getId())->delete(); //删除session
        Auth::logout(); //登出系统
        $request->session()->invalidate(); //刷新并重新生成sessionID
        $request->session()->regenerateToken(); //重新生成CSRF令牌值。
        return redirect('/'); //跳转到网站首页
    }
}
