<?php

namespace App\Models\Company;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /app/Models/Company/Contact.php
 * Created Time: 2023-03-16 11:15:59
 * Last Edit Time: 2023-04-02 20:41:01
 * Description: 联系人主体Model
 */

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; //一对多
use Illuminate\Database\Eloquent\SoftDeletes; //软删除
class Contact extends Model
{
    use SoftDeletes; //开启软删除
    protected $fillable = [
        'company_id',
        'job',
        'staff_name',
        'is_main',
        'last_name',
        'name',
        'description',
        'ip',
        'status'
    ];
    //联系信息
    public function relation(): HasMany
    {
        return $this->hasMany(ContactsMap::class)
            ->orderByDesc('updated_at');
    }
}
