<?php

namespace App\Http\Controllers\System;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /app/Http/Controllers/System/TodolistController.php
 * Created Time: 2022-09-23 10:57:18
 * Last Edit Time: 2023-04-17 10:22:40
 * Description: 待办事项控制器
 */

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\System\TodoList;
use Illuminate\Support\Facades\Auth;

class TodolistController extends Controller
{
    //列表  todolist?page=1
    public function index(Request $request)
    {
        $this->data['status'] = empty($request->todo) ? 0 : 1;
        $this->appends = ['todo' => $this->data['status']]; //分页额外参数
        $this->data['count'] =  TodoList::where(['status' => 0], ['staff_id' =>  Auth::id()])->count();
        $this->data['completed'] =  TodoList::where(['status' =>  1], ['staff_id' => Auth::id()])->count();
        $this->data['todolist'] = TodoList::where(['status' =>  $this->data['status'] ?? 0], ['staff_id' => Auth::id()])->orderByDesc('created_at')->paginate($this->perpage)->appends($this->appends);
        return $this->view('todolist');
    }
    //新增 POST
    public function store(Request $request)
    {
        $request->validate(['subject' => 'required|min:1|max:180'], [], ['subject' => '待办事项']);
        TodoList::create([
            'subject' => $request->subject,
            'send_username' => Auth::user()->name, //发送人name
            'send_user_id' => Auth::id(), //发送人ID
            'staff_id' => $request->id ?? Auth::id(), //接收ID
            'username' =>  $request->username ?? Auth::user()->name //接收人
        ]);
        return redirect()->route('todolist.index'); //返回到列表
    }
    //更新API(put)
    public function update(Request $request)
    {
        try {
            $request->validate(['id' => 'required|numeric']);
            $todo = TodoList::find($request->id);
            throw_if(empty($todo->id), new \ErrorException('事项不存在/已删除~'));
            if (empty($request->checked)) {
                $todo->complete_at = null;
                $todo->status = 0;
            } else {
                $todo->complete_at = now();
                $todo->status = 1;
            }
            $todo->completed_id = Auth::id();
            $todo->completed_name = Auth::user()->name;
            $todo->save();
            $this->result['status'] = 'success';
        } catch (\Throwable $th) {
            $this->result['msg'] = $th->getMessage();
        }
        return response()->json($this->result);
    }

    //删除
    public function destroy(Request $request)
    {
        $this->result['msg'] = '未能从数据库中删除';
        if (TodoList::destroy($request->id)) {
            $this->result['status'] = 'success';
        }
        return response()->json($this->result);
    }
}
