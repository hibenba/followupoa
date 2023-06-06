<?php

namespace App\Http\Controllers\Team;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2023: Mr.Kwok
 * FilePath: /app/Http/Controllers/Team/JobController.php
 * Created Time: 2023-06-05 17:51:54
 * Last Edit Time: 2023-06-06 09:42:09
 * Description: 职位管理
 */

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staff\StaffGroup;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->data['jobs'] = StaffGroup::orderByDesc('id')->get();
        return $this->view('team.job');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->data['title'] = '新增职位';
        return $this->view('team.job_create');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->data = StaffGroup::find($id);
        abort_if(empty($this->data), 404, '编辑的职位不存在！');
        $this->data['title'] = '编辑《' .  $this->data->name . '》';
        return $this->view('team.job_create');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|min:1|max:180'], [], ['name' => '职位名称']);
        if ($request->has('id') && $request->id > 0) {
            $job = StaffGroup::find($request->id);
            abort_if(empty($job), 404, '编辑的职位不存在！');
        } else {
            $job = new StaffGroup;
        }
        $job->name = $request->name;
        $job->color = $request->color;
        $job->description = empty($request->description) ? '' : $request->description;
        $job->add_company = empty($request->add_company) ? 0 : 1;
        $job->edit_company = empty($request->edit_company) ? 0 : 1;
        $job->is_admin = empty($request->is_admin) ? 0 : 1;
        $job->type = empty($job->type) ? 0 : -1;
        $job->save();
        return redirect()->route('admin.job.index'); //返回到列表
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            $job = StaffGroup::find($request->id);
            throw_unless($job, new \ErrorException('职位不存在~'));
            if (Auth::user()->isAdmin()) {
                switch ($request->type) {
                    case 'add_company':
                        $job->add_company = empty($job->add_company) ? 1 : 0;
                        break;
                    case 'edit_company':
                        $job->edit_company = empty($job->edit_company) ? 1 : 0;
                        break;
                    case 'is_admin':
                        $job->is_admin = empty($job->is_admin) ? 1 : 0;
                        break;
                    default:
                        break;
                }
                $job->save();
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
        $job = StaffGroup::find($id);
        if (Auth::user()->isAdmin() && $job->type != -1) {
            $job->delete();
        }
        return back();
    }
}
