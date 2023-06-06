<?php

namespace App\Http\Controllers\Company;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /app/Http/Controllers/Company/CompanyUpdateController.php
 * Created Time: 2022-04-10 19:36:45
 * Last Edit Time: 2023-05-09 19:46:45
 * Description: 客户单个修改控制器
 */

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company\Company; //客户表
use App\Models\Staff\Staff; //员工表
use Illuminate\Support\Facades\Auth;

class CompanyUpdateController extends Controller
{
    //更新客户信息
    public function __invoke(Request $request)
    {
        try {
            switch ($request->type) {
                case 'status': //更新客户状态
                    throw_unless(in_array($request->status, [0, 2]), new \ErrorException('客户状态不正确！'));
                    $data = ['status' => $request->status];
                    $this->company_update($request->id, $data);  //更新客户状态
                    break;
                case 'staff': //更新客户归属 
                    $staff = Staff::find($request->staff_id);
                    throw_unless($staff || $staff->status == 0, new \ErrorException('员工不存在~'));
                    $this->company_update($request->id, ['staff_id' => $staff->id, 'username' => $staff->username]);  //转移到新员工名下
                    break;
                case 'business': //更新客户行业
                    throw_if(empty(\App\Models\Company\Business::find($request->business)), new \ErrorException('客户行业不存在~'));
                    $this->company_update($request->id, ['business' => $request->business]);  //修改合作状态
                    break;
                default:
                    break;
            }
        } catch (\Throwable $th) {
            $this->result['msg'] = $th->getMessage();
        }
        return $this->api_response();
    }
    //更新数据库
    private function company_update($id, $update)
    {
        try {
            $company = Company::find($id);
            throw_unless($company, new \ErrorException('客户不存在~'));
            if (Auth::id() === $company->staff_id || Auth::user()->isAdmin()) {
                $company->update($update);
                $this->result['status'] = 'success';
            } else {
                throw new \ErrorException('您无权处理当前客户数据~');
            }
        } catch (\Throwable $th) {
            $this->result['msg'] = $th->getMessage();
        }
    }

    //设置为无效客户并输入无效原因
    public function set_invalid(Request $request)
    {
        $request->validate(['message' => 'required']);
        $this->company_update($request->id, ['status' => 1, 'invalid_cause' => $request->message]); //更新客户状态
        return back();
    }
    //增加客户与Tag的关联
    public function tags_add(Request $request)
    {
        try {
            $company = Company::find($request->id);
            throw_unless($company, new \ErrorException('客户不存在~'));
            $this->tags_insert($request->tagname, $company->id, 'company');
        } catch (\Throwable $th) {
            $this->result['msg'] = $th->getMessage();
            return back()->withErrors($this->result);
        }
        return back();
    }
    //移出客户与Tag的关联
    public function tags_remove(Request $request)
    {
        try {
            if (
                \App\Models\Tag\TagMap::where('tag_id', $request->tagid)
                ->where('taggable_id', $request->id)
                ->where('taggable_type', 'company')->delete()
            ) {
                $this->result['status'] = 'success';
            }
        } catch (\Throwable $th) {
            $this->result['msg'] = $th->getMessage();
        }
        return $this->api_response();
    }

    //表单检测客户信息
    public function check_company(Request $request)
    {
        if ($request->has('name')) {
            $unique = Company::withTrashed()->where('name', $request->company_name);
            if ($request->id > 0) {
                $unique->where('id', '<>', $request->id);
            }
            $unique->first();
            if (!empty($unique->id)) {
                return response()->json('【客户名称】已存在于数据库中(id:' . $unique->id . ')拥有人:' . $unique->username);
            }
        }
        return response()->json(true);
    }
}
