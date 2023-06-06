<?php

namespace App\Http\Middleware;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /app/Http/Middleware/Session.php
 * Created Time: 2022-06-17 14:16:03
 * Last Edit Time: 2023-04-04 22:12:51
 * Description: 验证数据库Session中间件
 */

use Closure;
use Illuminate\Http\Request;
use App\Models\Log\Log;
use Illuminate\Support\Facades\Auth;

class Session
{
    public function handle(Request $request, Closure $next)
    {
        $session = \App\Models\System\Session::where('token', $request->session()->getId())->first();
        if (!empty($session->id)) {
            $session->route = $request->getRequestUri();
            if ($session->isDirty()) {
                $session->save();
            }
            //记录访问日志
            Log::create([
                'staff_id' => Auth::id(),
                'staff_name' => Auth::user()->name,
                'username' => Auth::user()->username,
                'ip' => $request->ip(),
                'referer' => $request->server('HTTP_REFERER') ?? '',
                'user_agent' => $request->header('User-Agent') ?? '未知',
                'url' => $request->getRequestUri(),
                'request' => json_encode($request->toArray())
            ]);
            return $next($request); //验证通过
        }
        return (new \App\Http\Controllers\User\LoginController)->destroy($request); //退出系统
    }
}
