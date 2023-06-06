<?php

namespace App\Models\Tag;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /app/Models/Tag/TagMap.php
 * Created Time: 2022-04-15 20:27:32
 * Last Edit Time: 2023-03-24 18:24:40
 * Description: 标签与文章/菜谱的中间关联表
 */

use Illuminate\Database\Eloquent\Model;

class TagMap extends Model
{
    protected $table = 'tags_maps'; //标签关联表表名
    protected $primaryKey = false;
    public $incrementing = false; //非自增表
    public $timestamps = false; //不维护时间
    //可批量填充
    protected $fillable = [
        'tag_id',
        'taggable_id',
        'taggable_type'
    ];
}
