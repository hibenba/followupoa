<?php

namespace App\Models\Company;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /app/Models/Company/ContactsMap.php
 * Created Time: 2023-03-16 11:15:59
 * Last Edit Time: 2023-03-28 13:41:13
 * Description: 联系方式Model
 */

use Illuminate\Database\Eloquent\Model;

class ContactsMap extends Model
{
    protected $fillable = [
        'contact_id',
        'contact_type',
        'contact',
        'staff_name',
        'owner',
        'loacl_contact',
        'description',
        'status',
        'invalid_reasons',
    ];
}
