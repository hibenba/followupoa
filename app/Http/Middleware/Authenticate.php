<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    //获取用户未经过身份验证时应重定向到的路径。
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login'); //未通过验证的跳转到login路由
    }
}
