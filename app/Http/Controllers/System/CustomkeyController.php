<?php

namespace App\Http\Controllers\System;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /app/Http/Controllers/System/CustomkeyController.php
 * Created Time: 2022-09-23 10:57:18
 * Last Edit Time: 2023-05-23 17:16:32
 * Description: 自定义字段控制器
 */

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Models\System\CustomKey;
use Illuminate\Http\Request;

class CustomkeyController extends Controller
{
    public function __invoke()
    {
        $this->data['customkeys'] = CustomKey::paginate($this->perpage);
        $this->data['count'] = CustomKey::count();
        return $this->view('system.customkeys');
    }

    /**
     * 显示编辑表单
     */
    public function edit(string $key)
    {
        $this->data = CustomKey::where('key', $key)->first();
        $this->data['note_text'] = $this->data->note;
        $this->data['title'] = '编辑字段《' . $this->data->note . '》';
        $this->data['note'] = '请按照字段属性编辑内容。';
        $this->data['action'] = route('admin.customkey.update', ['key' => $this->data->key]); //请求路由
        return $this->view('system.customkey_create');
    }
    /**
     * 显示新增表单
     */
    public function create()
    {
        $this->data['title'] = '新增一个字段';
        $this->data['note'] = '自定义字段主要用于扩展功能时的个性化需求。';
        $this->data['action'] = route('admin.customkey.store');
        $this->data['type'] = 1;
        $this->data['value'] = [''];
        return $this->view('system.customkey_create');
    }

    //更新字段
    public function update(Request $request, $key)
    {
        $customkey = CustomKey::where('key', $key)->first();
        abort_if(empty($customkey), 404);
        $values = [];
        if ($customkey->type == 1) {
            //处理名字和key
            $request->validate([
                'note' => 'required|min:2|max:255',
                'key' => 'required|alpha_dash',
            ], [], ['note' => '字段中文名称',  'key' => '字段Key']);
            $customkey->note = $request->note;
            $customkey->key = $request->key;
        }
        foreach ($request->value as $item => $value) {
            if (!empty($value)) {
                $values[$item] = $value;
            }
        }
        $customkey->value = $values; //Model里有处理
        $customkey->save();
        Cache::flush(); //清空缓存
        return redirect()->route('admin.customkeys');
    }
    //保存新增
    public function store(Request $request)
    {
        $request->validate([
            'note' => 'required|min:2|max:255',
            'key' => 'required|alpha_dash',
        ], [], ['note' => '字段中文名称',  'key' => '字段Key']);
        $customkey = new CustomKey;
        $customkey->note = $request->note;
        $customkey->key = $request->key;
        $customkey->type = 1;
        foreach ($request->value as $item => $value) {
            if (!empty($value)) {
                $values[$item] = $value;
            }
        }
        $customkey->value = $values; //Model里有处理
        $customkey->save();
        Cache::flush(); //清空缓存
        return redirect()->route('admin.customkeys');
    }
    //删除字段
    public function delete($key)
    {
        $customkey = CustomKey::where('key', $key)->first();
        abort_if(empty($customkey), 404);
        abort_if($customkey->type == 0, 403, '您不能删除系统内置字段！');
        $customkey->delete(); //删除字段
        return redirect()->route('admin.customkeys');
    }
}
