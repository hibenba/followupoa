<?php

namespace App\Models\Tag;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /app/Models/Tag/Tag.php
 * Created Time: 2022-04-15 20:26:39
 * Last Edit Time: 2023-04-03 21:09:07
 * Description: 标签模型
 */

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; //一对多
//当前符合多对多(多态)情况
class Tag extends Model
{
    //可批量填充
    protected $fillable = ['name'];

    //关联
    public function tag_map(): HasMany
    {
        return $this->hasMany(TagMap::class);
    }
}
