<?php

namespace App\Http\Controllers\System;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /app/Http/Controllers/System/LoginlogController.php
 * Created Time: 2022-09-23 10:57:18
 * Last Edit Time: 2023-04-03 23:21:50
 * Description: 后台管理首页
 */

use App\Http\Controllers\Controller;
use App\Models\Staff\StaffLoginLog;

class LoginlogController extends Controller
{
    public function __invoke()
    {
        $this->data['logs'] = StaffLoginLog::orderByDesc('created_at')->paginate($this->perpage);
        $this->data['count'] = StaffLoginLog::count();
        return $this->view('log.loginlogs');
    }
}
