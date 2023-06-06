<?php

namespace App\Http\Controllers\System;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /app/Http/Controllers/System/ExportController.php
 * Created Time: 2022-09-23 10:57:18
 * Last Edit Time: 2023-06-03 18:36:19
 * Description: 数据导出
 */

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company\Company;

class ExportController extends Controller
{
    public function __invoke()
    {
        $this->data = $this->fields(); //选择导出的字段
        $this->data['businesses'] = $this->businesses(); //所属行业
        $this->data['customkey'] = $this->custom_key(); //所有的自定义字段
        $this->data['countries'] = $this->countries(); //国家列表
        $this->data['checkes'] = ['name', 'url', 'telephone', 'is_vip', 'address', 'business', 'credit_id']; //默认选中字段
        return $this->view('tool.export');
    }
    //数据导出结果
    public function export(Request $request)
    {
        //dd($request);
        $oderarr = [
            'created' => 'created_at',
            'updated' => 'updated_at',
            'track' => 'track_at',
            'staff_id' => 'staff_id',
            'business' => 'business',
            'status' => 'status',
            'source' => 'source',
        ];

        $query = Company::select($request->has('columns') && is_array($request->columns) ? array_merge($request->columns, ['id']) : '*')
            ->when($request->has('business') && is_array($request->business), function ($query) use ($request) {
                return $query->whereIn('business', $request->business); //限制行业
            })
            ->when($request->has('country') && $request->country > 0, function ($query) use ($request) {
                return $query->where('country', $request->country); //限制国家
            })
            ->when($request->has('attribute') && is_array($request->attribute), function ($query) use ($request) {
                return $query->whereIn('attribute', $request->attribute); //限制客户类型
            })
            ->when($request->has('source') && is_array($request->source), function ($query) use ($request) {
                return $query->whereIn('data_source', $request->source); //限制数据来源
            })
            ->when($request->has('status') && is_array($request->status), function ($query) use ($request) {
                return $query->whereIn('status', $request->status); //限制数据状态
            })
            ->when($request->has('keywords'), function ($query) use ($request) {
                return $query->where('name', 'like', '%' . $request->keywords . '%'); //限制名称关键字
            })
            ->when($request->has('vip'), function ($query) {
                return $query->where('is_vip', 1); //限制VIP
            });

        $this->data['companies'] = $query->orderBy($oderarr[$request->odertype] ?? 'created_at', $request->oder == 'desc' ? 'desc' : 'asc')->get(); //排序


        $this->data['fields'] = $this->fields(); //可用字段
        $this->data['businesses'] = $this->businesses(); //所属行业
        $this->data['customkey'] = $this->custom_key(); //所有的自定义字段
        $this->data['countries'] = $this->countries(); //国家列表
        $this->data['has_followups'] = empty($request->followups) ? 0 : 1; //是否导出跟进记录

        foreach ($this->data['companies'] as $company) {
            $company->appointments; //预约跟进
            $company->followups; //跟进记录
            $company->contacts; //联系方式
        }

        //dd($this->data['companies']);
        $this->data['has_appointments'] = empty($request->appointments) ? 0 : 1; //是否导出预约记录
        $this->data['has_contacts'] = empty($request->contacts) ? 0 : 1; //是否导出联系人信息


        return $this->view('tool.export_data');
    }
    //行业分类
    private function businesses()
    {
        return \App\Models\Company\Business::orderBy('order')->get(); //所有行业
    }
    //可用字段
    private function fields()
    {
        return [
            'companies' => [
                'id' => '客户ID',
                'name' => '客户名称',
                'url' => '网址',
                'telephone' => '电话',
                'is_vip' => '是否VIP',
                'country' => '国家',
                'state' => '行政区域',
                'address' => '地址',
                'business' => '所属行业',
                'credit_id' => '统一信用代码',
                'description' => '备注',
                'introduction' => '简介',
                'attribute' => '客户属性', //属性(个人、公司、组织、政府等)
                'data_source' => '数据来源',
                'staff_name' => '所属员工'
            ],
            'contacts' => [
                'job' => '职位',
                'is_main' => '是否重要',
                'last_name' => '姓氏',
                'name' => '名字',
                'description' => '备注',
                'contact_type' => '类别',
                'contact' => '联系方式',
            ],
            'appointments' => [
                'message' => '预约内容',
                'track_at' => '预约时间'
            ],
            'followups' => [
                'message' => '跟进内容',
                'created_at' => '跟进时间'
            ]
        ];
    }
}
