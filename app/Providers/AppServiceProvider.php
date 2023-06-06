<?php

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /app/Providers/AppServiceProvider.php
 * Created Time: 2023-02-28 02:37:48
 * Last Edit Time: 2023-03-16 10:50:29
 * Description: App服务提供商(将启动/注册自己定义的程序)
 */


namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    //注册APP服务
    public function register(): void
    {
        //
    }

    //放入要启动的APP服务     
    public function boot(): void
    {
        //数据库面里用到的xxable_type字段时，必须在这里注册：No morph map defined for model [App\Models\User].
        \Illuminate\Database\Eloquent\Relations\Relation::morphMap([
            'company' => \App\Models\Company\Company::class, //公司模型
        ]);
    }
}
