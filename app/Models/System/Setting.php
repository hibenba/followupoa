<?php

namespace App\Models\System;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /app/Models/System/Setting.php
 * Created Time: 2022-04-19 19:46:33
 * Last Edit Time: 2023-03-16 11:18:24
 * Description: 系统设置项Model
 */

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public $timestamps = false; //不维护时间
    protected $primaryKey = 'key'; //主键
    protected $keyType = 'string'; //主键类型
    public $incrementing = false; //主键自增
    protected $fillable = ['key', 'value', 'note']; //可批量填充的字段
}
