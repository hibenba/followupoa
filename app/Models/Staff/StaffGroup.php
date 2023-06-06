<?php

namespace App\Models\Staff;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /app/Models/Staff/StaffGroup.php
 * Created Time: 2022-04-24 21:42:37
 * Last Edit Time: 2023-06-05 18:11:14
 * Description: 部门表
 */

use Illuminate\Database\Eloquent\Model;

class StaffGroup extends Model
{
    public $timestamps = false; //不维护时间
    protected $fillable = [
        'name',
        'color',
        'icon',
        'add_company',
        'edit_company',
        'is_admin',
        'type',
        'description',
    ];
}
