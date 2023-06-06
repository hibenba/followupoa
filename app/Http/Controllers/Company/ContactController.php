<?php

namespace App\Http\Controllers\Company;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /app/Http/Controllers/Company/ContactController.php
 * Created Time: 2023-04-03 10:00:06
 * Last Edit Time: 2023-05-21 17:44:13
 * Description: 联系人资源控制器
 */

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company\Contact;
use App\Models\Company\ContactsMap;
use Illuminate\Support\Facades\Auth;
use App\Models\Company\Company;

class ContactController extends Controller
{
    /**
     * 新建联系人表单
     */
    public function create(Request $request)
    {
        $company = Company::find($request->id);
        abort_if(empty($company), 404, '此客户已不存在~');
        if (Auth::user()->isAdmin() || Auth::id() === $company->staff_id) {
            $this->data['title'] = '新增联系人';
            $this->data['note'] = '为当前客户新增一个联系人';
            $this->data['status'] = 0; //联系人状态
            $this->data['is_main'] = 0; //重要联系人
            $this->data['company_id'] =  $company->id; //返回客户详情
            $this->data['contacts'] = $this->custom_key('customer_contacts'); //可用联系方式
            $this->data['relation'] = [];
            return $this->view('contact.create');
        } else {
            abort(403, '您无权增加此客户的联系人~');
        }
    }

    /**
     *  存储联系人
     */
    public function store(Request $request)
    {
        // dd($request);
        $company = Company::find($request->company_id);
        abort_if(empty($company), 404, '此客户已不存在~');
        if (Auth::user()->isAdmin() || Auth::id() === $company->staff_id) {
            //联系人主体处理
            if ($request->id > 0) {
                $contact = Contact::find($request->id); //编辑模式
                abort_if(empty($contact), 404, '此联系人已不存在~');
            } else {
                $contact = new Contact; //新增模式
                $contact->company_id = $company->id; //公司ID
                $contact->staff_name = Auth::user()->name; //登陆人员名字
            }
            $contact->ip = $request->ip();
            $contact->job = $request->job ?? '';
            $contact->is_main = $request->is_main ? 1 : 0;
            $contact->last_name = $request->last_name ?? '';
            $contact->name = $request->name ?? '';
            $contact->description = $request->description ?? '';
            $contact->status = $request->status ? 1 : 0;
            $contact->save();
            //联系信息处理
            ContactsMap::where('contact_id', $contact->id)->delete(); //删除联系信息
            //遍历联系类别
            foreach ($request->relation_contact_type as $item => $value) {
                if (empty($request->relation_contact[$item])) {
                    break;
                } else {
                    $ctt = $request->relation_contact[$item];
                }
                //写入到联系表
                ContactsMap::create([
                    'contact_id' => $contact->id,
                    'contact_type' => $value,
                    'contact' => $ctt,
                    'staff_name' => Auth::user()->name,
                    'owner' => $request->relation_owner[$item] ?? 0,
                    'loacl_contact' => $request->loacl_contact[$item] ?? '',
                    'description' => $request->relation_description[$item] ?? '',
                    'status' => $request->relation_status[$item] ?? 0,
                    'invalid_reasons' => $request->invalid_reasons[$item] ?? ''
                ]);
            }
        } else {
            abort(403, '您无权修改此客户的联系人~');
        }
        return redirect()->route('companies.show', ['company' => $company->id]); //返回到详细页(编辑后查看显示效果)
    }

    //编辑联系人表单
    public function edit($id)
    {
        $contact = Contact::withTrashed()->find($id);
        abort_if(empty($contact), 404, '联系人不存在~');
        abort_if($contact->trashed(), 403, '联系人已被删除请恢复后再编辑~');
        $this->data = $contact;
        $this->data->relation = $this->data->relation()->get(); //联系方式
        $this->data['title'] = '编辑联系人';
        $this->data['note'] = '编辑当前的联系人信息';
        $this->data['contacts'] = $this->custom_key('customer_contacts'); //可用联系方式
        return $this->view('contact.create');
    }

    //更新联系人信息
    public function update(Request $request)
    {
        try {
            $contact = Contact::withTrashed()->find($request->id);
            throw_unless($contact, new \ErrorException('联系人不存在~'));
            //权限验证
            if (Auth::user()->isAdmin() || Auth::id() === $contact->staff_id) {
                switch ($request->type) {
                    case 'setmain': //更新客户状态
                        $contact->is_main = $request->ismain ? 1 : 0;
                        break;
                    case 'recover': //恢复联系人
                        if (Auth::user()->isAdmin() && $contact->trashed()) {
                            $contact->restore();
                        } else {
                            throw new \ErrorException('联系人未被删除或者您没有权限恢复~');
                        }
                        break;
                }
                if ($contact->isDirty()) {
                    $contact->save();
                }
                $this->result['status'] = 'success';
            }
        } catch (\Throwable $th) {
            $this->result['msg'] = $th->getMessage();
        }
        return $this->api_response();
    }

    /**
     * 删除联系人
     */
    public function destroy(Request $request)
    {
        try {
            if (Auth::user()->isAdmin()) {
                $contact = Contact::withTrashed()->find($request->id); //管理员查询回收站
            } else {
                $contact = Contact::find($request->id);
            }
            throw_unless($contact, new \ErrorException('联系人记录不存在~'));
            //权限验证
            if (Auth::id() === $contact->staff_id || Auth::user()->isAdmin()) {
                if ($contact->trashed()) {
                    ContactsMap::where('contact_id', $contact->id)->delete(); //删除联系信息
                    $contact->forceDelete(); //永久删除
                } else {
                    //软删除
                    if (!$contact->delete()) {
                        throw new \ErrorException('删除失败~');
                    }
                }
                $this->result['status'] = 'success';
            } else {
                throw new \ErrorException('没有删除权限~');
            }
        } catch (\Throwable $th) {
            $this->result['msg'] = $th->getMessage();
        }
        return $this->api_response();
    }
}
