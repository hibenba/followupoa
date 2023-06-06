<?php

namespace App\Models\System;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /app/Models/System/Session.php
 * Created Time: 2022-04-24 21:42:37
 * Last Edit Time: 2023-03-20 10:12:32
 * Description: 登陆Session管理
 */

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $fillable = ['staff_id', 'username', 'abilities', 'ip', 'route', 'token']; //可批量填充的字段
}
