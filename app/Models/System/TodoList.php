<?php

namespace App\Models\System;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /app/Models/System/TodoList.php
 * Created Time: 2022-04-24 21:42:37
 * Last Edit Time: 2023-04-03 17:53:17
 * Description: 后台待办事项
 */

use Illuminate\Database\Eloquent\Model;

class TodoList extends Model
{
    protected $fillable = [
        'staff_id',
        'username',
        'send_user_id',
        'send_username',
        'subject',
        'completed_id',
        'completed_name',
        'displayorder',
        'status'

    ]; //可批量填充的字段
    protected $appends = ['time']; //追加字段（虚拟字段访问器里的内容）
    //人性化时间
    public function getTimeAttribute()
    {
        $time = \Illuminate\Support\Carbon::createFromDate($this->attributes['created_at']);
        return $time->locale('zh_CN')->diffForHumans();
    }
}
