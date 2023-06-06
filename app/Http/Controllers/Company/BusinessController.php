<?php

namespace App\Http\Controllers\Company;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /app/Http/Controllers/Company/BusinessController.php
 * Created Time: 2022-04-10 19:36:45
 * Last Edit Time: 2023-04-25 12:23:00
 * Description: 客户资源控制器
 */

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company\Business;

class BusinessController extends Controller
{
    /**
     * 行业列表 GET /companies/business
     */
    public function index()
    {
        $this->data['businesses'] = Business::where('upid', 0)->orderBy('order')->get(); //顶级行业
        foreach ($this->data['businesses'] as $business) {
            $business->businesses = Business::where('upid', $business->id)->orderBy('order')->get(); //子行业
        }
        return $this->view('company.business.index');
    }

    /**
     * 新增行业表单
     */
    public function create(Request $request)
    {
        $this->data['title'] = '新增行业';
        $this->data['note'] = '针对您的客户行业特性增加相应的所属行业！';
        $this->data['action'] = route('business.store');
        $this->data['upid'] = $request->upid ?? 0; //默认为顶级行业
        $this->data['businesses'] = Business::where('upid', 0)->get(); //上级分类
        $this->data['status'] = 1; //可选
        return $this->view('company.business.create');
    }
    /**
     * 接收新增
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(['name' => 'required|min:2|max:255|unique:App\Models\Company\Business',], [], ['name' => '行业名称']);
        // 创建新行业
        $business = new Business();
        $business->name = $validatedData['name'];
        $business->upid = empty($request->upid) ? 0 : intval($request->upid); //上级分类
        $business->description = $request->description ?? '';
        $business->order = empty($request->order) ? 0 : intval($request->order); //排序
        $business->items_count = 0;
        $business->status = $request->status ? 1 : 0; //是否可选
        $business->save(); // 保存新行业到数据库
        return redirect()->route('business.index'); //返回到列表
    }


    /**
     * 显示行业编辑表单
     */
    public function edit(string $id)
    {
        $this->data = Business::find($id);
        abort_if(empty($this->data->id), 404, '编辑的分类不存在');
        $this->data['title'] = '编辑《' . $this->data->name . '》';
        $this->data['note'] = '针对您的客户行业特性增加相应的所属行业！';
        $this->data['action'] = route('business.update', ['business' => $this->data->id]);
        $this->data['businesses'] = Business::where('upid', 0)->get(); //上级分类        
        return $this->view('company.business.create');
    }

    /**
     * 接收行业修改信息
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate(['name' => 'required|min:2|max:255',], [], ['name' => '行业名称']);
        $business = Business::find($id);
        abort_if(empty($business->id), 404, '编辑的分类不存在');
        $business->name = $validatedData['name'];
        $business->upid = empty($request->upid) ? 0 : intval($request->upid); //上级分类
        $business->description = $request->description ?? '';
        $business->order = empty($request->order) ? 0 : intval($request->order); //排序
        $business->status = $request->status ? 1 : 0; //是否可选
        $business->save(); // 保存
        return redirect()->route('business.index'); //返回到列表
    }

    /**
     * 删除行业
     */
    public function destroy(Request $request)
    {
        try {
            $request->validate(['id' => 'required|numeric']);
            throw_if(!empty(Business::where('upid', $request->id)->first()), new \ErrorException('存在下级行业，请移除所有子行业后再尝试删除！'));
            $business = Business::find($request->id); //资源器
            throw_if(empty($business->id), new \ErrorException('当前行业不存在！'));
            \App\Models\Company\Company::where('business', $business->id)->update(['business' => 0]); //设计客户行业为0
            if ($business->delete()) {
                $this->result['status'] =  'success';
            }
        } catch (\Throwable $th) {
            $this->result['msg'] = $th->getMessage();
        }
        return $this->api_response();
    }

    /**
     * 接收API行业修改信息
     */
    public function api_update(Request $request)
    {
        try {
            $business = Business::find($request->id);
            throw_if(empty($business->id), new \ErrorException('当前行业不存在！'));
            if ($request->has('description')) {
                $business->description = empty($request->description) ? '' : $request->description;
            }
            if ($request->has('order')) {
                throw_if($request->order < 0, new \ErrorException('请输入大于0的整数'));
                $business->order = empty($request->order) ? 0 : abs(intval($request->order)); //排序
            }
            $business->save();
            $this->result['status'] =  'success';
        } catch (\Throwable $th) {
            $this->result['msg'] = $th->getMessage();
        }
        return $this->api_response();
    }
}
