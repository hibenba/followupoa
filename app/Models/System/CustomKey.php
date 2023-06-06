<?php

namespace App\Models\System;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /app/Models/System/CustomKey.php
 * Created Time: 2022-04-24 21:42:37
 * Last Edit Time: 2023-05-22 17:04:58
 * Description: 自定义字段表Model
 */

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute; //属性修改器
class CustomKey extends Model
{
    public $timestamps = false; //不维护时间
    protected $primaryKey = 'key'; //主键
    protected $keyType = 'string'; //主键类型
    public $incrementing = false; //主键自增
    protected $fillable = ['key', 'value', 'note', 'type']; //可批量填充的字段

    //字段处理(属性修改器)
    protected function value(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true), //查询器: 返回json解码后的数据
            set: fn ($value) => json_encode($value, JSON_UNESCAPED_UNICODE), //修改器：写入json编码后的数据
        );
    }
}
