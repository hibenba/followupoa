<?php

namespace App\Http\Controllers\Team;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2023: Mr.Kwok
 * FilePath: /app/Http/Controllers/Team/StaffController.php
 * Created Time: 2023-06-05 18:01:00
 * Last Edit Time: 2023-06-06 21:03:55
 * Description: 职位管理控制器
 */


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staff\Staff;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff\StaffGroup;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->data['employees'] = Staff::orderByDesc('id')->get();
        return $this->view('team.staff');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->data['title'] = '新增员工';
        $this->data['groups'] = StaffGroup::orderByDesc('id')->get();
        return $this->view('team.staff_create');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->data = Staff::find($id);
        abort_if(empty($this->data), 404, '编辑的员工不存在！');
        $this->data['nickname'] =  $this->data->title;
        $this->data['title'] = '编辑《' .  $this->data->name . '》';
        $this->data['groups'] = StaffGroup::orderByDesc('id')->get();
        return $this->view('team.staff_create');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'username' => 'required|min:1|max:64',
            'group' => 'required|numeric'
        ];
        if (!empty($request->password)) {
            $rules['password'] = 'min:6|max:32';
        }
        if (empty($request->id)) {
            $rules['password'] = 'required|min:6|max:32';
        }
        if (!empty($request->email)) {
            $rules['email'] = 'email';
        }
        $request->validate($rules, [], [
            'name' => '登陆用户名',
            'group' => '所属部门',
            'password' => '登陆密码',
            'email' => '邮箱'
        ]);
        if ($request->has('id') && $request->id > 0) {
            $staff = Staff::find($request->id);
            abort_if(empty($staff), 404, '编辑的员工不存在！');
        } else {
            $staff = new Staff;
        }
        $staff->username = $request->username;
        $staff->group_id = $request->group;
        $staff->name = empty($request->name) ? '' : $request->name;
        $staff->password = $request->password;
        $staff->email = empty($request->email) ? '' : $request->email;
        $staff->mobilenumber = empty($request->mobilenumber) ? '' : $request->mobilenumber;
        $staff->title = empty($request->title) ? '' : $request->title;
        $staff->sex = intval($request->sex);
        $staff->identity = empty($request->identity) ? '' : $request->identity;
        $staff->status = empty($request->status) ? 0 : 1;
        $staff->save();
        return redirect()->route('admin.staff.index'); //返回到列表
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $staff = Staff::find($id);
        $staff->job;
        $this->data = $staff;
        $this->data['logs'] = \App\Models\Log\Log::where('staff_id', $staff->id)->orderBy('created_at', 'desc')->take(100)->get(); //操作记录
        $this->data['loginlogs'] = \App\Models\Staff\StaffLoginLog::where('staff_id', $staff->id)->orderBy('created_at', 'desc')->take(100)->get(); //登陆记录
        $this->data['todolist'] = \App\Models\System\TodoList::where('staff_id', $staff->id)->orderBy('created_at', 'desc')->take(100)->get(); //待办事项
        return $this->view('team.staff_show');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            $staff = Staff::find($request->id);
            throw_unless($staff, new \ErrorException('员工不存在~'));
            throw_if($staff->group_id == 1, new \ErrorException('不能对管理员操作~'));
            if (Auth::user()->isAdmin()) {
                $staff->status = empty($staff->status) ? 1 : 0;
                $staff->save();
                $this->result['status'] = 'success';
            } else {
                throw new \ErrorException('您无权处理当前数据~');
            }
        } catch (\Throwable $th) {
            $this->result['msg'] = $th->getMessage();
        }
        return $this->api_response();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $staff = Staff::find($id);
        abort_if(empty($staff), 404, '删除的员工不存在！');
        abort_if($staff->group_id == 1, 403, '请将管理员设置为其它部门后再操作！');
        abort_if($staff->status != 1, 403, '请先将员工设置为离职状态才能删除！');
        if (Auth::user()->isAdmin() && $staff->status == 1) {
            //1. 设置所属客户到回收站
            \App\Models\Company\Company::where('staff_id', $staff->id)->update(['staff_id' => null]);
            //2. 设置附件状态
            \App\Models\System\Attachment::where('staff_id', $staff->id)->update(['staff_id' => null]);
            $staff->delete();
        }
        return back();
    }
}
