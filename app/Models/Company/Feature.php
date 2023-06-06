<?php

namespace App\Models\Company;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /app/Models/Company/Feature.php
 * Created Time: 2023-03-16 11:15:59
 * Last Edit Time: 2023-04-01 21:46:40
 * Description: 客户产品特性Model
 */

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    public $timestamps = false; //不维护时间
    protected $fillable = [
        'company_id',
        'feature',
        'description'
    ];
}
