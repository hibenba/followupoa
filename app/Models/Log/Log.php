<?php

namespace App\Models\Log;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /app/Models/Log/Log.php
 * Created Time: 2022-04-24 21:42:37
 * Last Edit Time: 2023-04-03 22:47:06
 * Description: 后台访问日志
 */

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = ['staff_id', 'staff_name', 'username', 'ip', 'referer', 'user_agent', 'url', 'request']; //可批量填充的字段

}
