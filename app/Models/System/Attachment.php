<?php

namespace App\Models\System;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /app/Models/System/Attachment.php
 * Created Time: 2022-04-24 21:42:37
 * Last Edit Time: 2023-03-30 22:58:23
 * Description: 附件Model
 */

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; //软删除
use Illuminate\Database\Eloquent\Relations\HasOne; //一对一
class Attachment extends Model
{
    use SoftDeletes; //开启软删除
    protected $fillable = [
        'id',
        'company_id',
        'staff_id',
        'staff_name',
        'username',
        'file_path',
        'file_type',
        'file_size',
        'file_name',
        'ip',
        'description',
        'is_image',
    ];
    protected $appends = ['url']; //追加字段（虚拟字段访问器里的内容）
    //附件地址
    public function getUrlAttribute()
    {
        return $this->attributes['file_path'];
    }
    //员工信息
    public function staff(): HasOne
    {
        return $this->hasOne(\App\Models\Staff\Staff::class, 'id', 'staff_id');
    }
    //公司信息
    public function company(): HasOne
    {
        return $this->hasOne(\App\Models\Company\Company::class, 'id', 'company_id');
    }
}
