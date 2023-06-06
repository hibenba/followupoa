<?php

namespace App\Models\Company;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /app/Models/Company/Appointment.php
 * Created Time: 2023-03-16 11:15:59
 * Last Edit Time: 2023-03-29 17:42:29
 * Description: 跟进记录Model
 */

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; //软删除
use Illuminate\Database\Eloquent\Relations\HasOne; //一对一
class Appointment extends Model
{
    use SoftDeletes; //开启软删除    
    protected $fillable = [
        'company_id',
        'staff_id',
        'staff_name',
        'username',
        'message',
        'track_at',
        'status'
    ];
    //日期转换
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i​:s',
        'updated_at' => 'datetime:Y-m-d H:i​:s',
    ];
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
