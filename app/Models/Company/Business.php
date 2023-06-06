<?php

namespace App\Models\Company;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /app/Models/Company/Business.php
 * Created Time: 2023-03-16 11:15:59
 * Last Edit Time: 2023-04-24 17:10:05
 * Description: 客户所属行业Model
 */

use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    public $timestamps = false; //不维护时间

    protected $fillable = [
        'upid',
        'name',
        'description',
        'order',
        'items_count',
        'status'
    ];
}
