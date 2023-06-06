<?php

namespace App\Http\Middleware;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /app/Http/Middleware/Admin.php
 * Created Time: 2022-06-17 14:16:03
 * Last Edit Time: 2023-03-20 10:23:21
 * Description: 验证管理员权限中间件
 */

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Admin
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return $next($request); //验证通过
        }
        abort(403, '您没有管理权限');
    }
}
