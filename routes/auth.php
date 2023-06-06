<?php

use Illuminate\Support\Facades\Route;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /routes/auth.php
 * Created Time: 2023-02-28 02:37:48
 * Last Edit Time: 2023-04-16 13:36:15
 * Description: 登陆\登陆相关路由
 */

Route::get('login', App\Http\Controllers\User\LoginController::class)->name('login'); //GET用户登陆视图
Route::post('login', [App\Http\Controllers\User\LoginController::class, 'store'])->name('login.store'); //POST接收登陆信息
//Ajax验证码
Route::post('captcha', function () {
    $result = ['status' => 'error', 'msg' => ''];
    $captcha = session()->get('captcha'); //取到captcha(新版已加密)
    //Hash校验
    if (\Illuminate\Support\Facades\Hash::check(request()->captcha, $captcha['key'])) {
        $result['status'] = 'success';
    } else {
        $result['msg'] = '验证码错误';
    }
    return response()->json($result);
})->name('captcha');
