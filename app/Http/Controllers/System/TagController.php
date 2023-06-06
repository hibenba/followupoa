<?php

namespace App\Http\Controllers\System;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /app/Http/Controllers/System/TagController.php
 * Created Time: 2022-09-23 10:57:18
 * Last Edit Time: 2023-06-06 09:50:33
 * Description: 标签资源控制器
 */

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag\Tag;
use App\Models\Tag\TagMap;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->data['tags'] = Tag::paginate($this->perpage);
        $this->data['count'] = Tag::count(); //标签总数
        return $this->view('tag.index');
    }
    /**
     * 编辑标签表单
     */
    public function edit(int $id)
    {
        $this->data = Tag::find($id);
        abort_if(empty($this->data), 404, '此标签已不存在~');
        return $this->view('tag.create');
    }

    /**
     * 更新标签
     */
    public function update(Request $request, string $id)
    {
        $request->validate(['name' => 'required|min:1|max:32'], [], ['name' => '标签名']);
        $tag = Tag::find($id);
        abort_if(empty($tag), 404, '此标签已不存在~');
        $tag->name = $request->name;
        $tag->color = $request->color ?? '';
        if ($tag->isDirty()) {
            $tag->save();
        }
        return redirect()->route('tags.index'); //返回到详细页(编辑后查看显示效果)
    }
    //标签删除
    public function destroy(int $id)
    {
        $tag = Tag::find($id);
        abort_if(empty($tag), 404, '此标签已不存在~');
        TagMap::where('tag_id', $tag->id)->delete();
        $tag->delete();
        return back();
    }
}
