<?php

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /app/Providers/RouteServiceProvider.php
 * Created Time: 2023-02-28 02:37:48
 * Last Edit Time: 2023-03-20 10:21:11
 * Description: 路由服务提供商
 */

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/home'; //用户登陆后跳转位置

    /**
     * 定义路由模型绑定、模式过滤器和其他路由配置。
     */
    public function boot(): void
    {
        $this->configureRateLimiting(); //路由限速
        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware(['web', 'auth', 'session'])
                ->group(base_path('routes/web.php'));

            Route::middleware(['web', 'guest'])
                ->group(base_path('routes/auth.php'));
            //管理权限
            Route::middleware(['web', 'auth', 'admin'])
                ->name('admin.')
                ->group(base_path('routes/admin.php'));
        });
    }

    /**
     * 配置需要限速的路由
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
