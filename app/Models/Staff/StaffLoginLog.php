<?php

namespace App\Models\Staff;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /app/Models/Staff/StaffLoginLog.php
 * Created Time: 2022-04-24 21:42:37
 * Last Edit Time: 2023-03-18 18:29:50
 * Description: 员工表Model(Staff复数没有s)
 */

use Illuminate\Database\Eloquent\Model;

class StaffLoginLog extends Model
{
    public $timestamps = false; //不维护时间
    protected $fillable = [
        'ip',
        'staff_id',
        'username',
        'password',
    ];
}
