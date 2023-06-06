<?php

namespace App\Http\Controllers\Company;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /app/Http/Controllers/Company/CompanyController.php
 * Created Time: 2022-04-10 19:36:45
 * Last Edit Time: 2023-06-03 18:00:40
 * Description: 客户资源控制器
 */

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company\Company;
use App\Models\Company\Feature; //产品特性表
use App\Models\Staff\Staff;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    /**
     * 客户列表 GET /company
     */
    public function index(Request $request)
    {
        //顶部菜单选项
        $this->data['tables'] = [
            'list' =>  $this->table_factory(Company::company('list')->count(), '客户总数', 'folder-open'),
            'deals' => $this->table_factory(Company::company('deals')->count(), '已成交', 'gem'),
            'useless' => $this->table_factory(Company::company('useless')->count(), '无效客户', 'folder-minus')
        ];
        //管理员才有权查看回收站
        if (Auth::user()->isAdmin()) {
            $this->data['tables']['trash'] = $this->table_factory(Company::company('trash')->count(), '回收站', 'trash');
        }
        $this->data['type'] = $request->type ?? 'list';
        $resource = Company::company($this->data['type']); //资源控制器
        if ($request->has('keywords') && !empty($request->keywords)) {
            $resource->where('name', 'like', '%' . $request->keywords . '%')->orWhere('description', 'like', '%' . $request->keywords . '%');
            $this->appends = ['keywords' => $request->keywords]; //搜索关键字
        }
        $this->appends['type'] = $this->data['type']; //类别
        $orders = ['track' => 'track_at', 'update' => 'updated_at', 'create' => 'id', 'type' => 'customer_type', 'grade' => 'customer_grade', 'cooperate' => 'cooperate']; //可排序项
        if (!empty($orders[$request->order])) {
            $order = $this->appends['order'] = $request->order; //排序
        } else {
            $order = 'track'; //跟进时间
        }
        //按公司ID显示
        if ($request->has('id')) {
            $where = ['id' => $request->id]; //正常客户
            if (!Auth::user()->isAdmin()) {
                $where['staff_id'] = Auth::id(); //非管理员限制员工ID
            }
            $this->data['items'] = Company::where($where)->paginate($this->perpage); //
        } else {
            $this->data['items'] = $resource->orderByDesc($orders[$order])->paginate($this->perpage)->appends($this->appends);
        }
        $this->data['businesses'] = $this->businesses(); //所属行业
        $this->data['customkey'] = $this->custom_key(); //所有的自定义字段
        $this->data['countries'] = $this->countries(); //国家列表
        foreach ($this->data['items'] as $item) {
            $item->appointments($this->app['appointment']); //预约跟进
            $item->followups($this->app['followup']); //跟进记录
            $item->attachments($this->app['attachment']); //上传的附件
            $item->tags; //标签
            $item->staff; //员工信息
            $item->contacts; //联系方式
        }
        $this->data['staffarr'] = Staff::where('status', 0)->get(); //所有在岗员工
        $this->data['tips'] = $this->tips(); //跟进时间区间提示
        $this->data['sidebar'] = true; //收缩侧边菜单项
        return $this->view('company.company');
    }

    //跟进时间区间提示
    private function tips()
    {
        return [
            'week' => '<td class="alert alert-success" colspan="4"><i class="fas fa-calendar-week"></i> 以下是本周已跟进的客户</td>',
            'half_month' => '<td class="alert alert-info" colspan="4"><i class="fas fa-window-restore"></i> 以下是上周跟进的客户</td>',
            'month' => '<td class="alert alert-warning" colspan="4"><i class="fas fa-exclamation-circle"></i> 以下是本月跟进的客户</td>',
            'month_mor' => '<td class="alert bg-danger" colspan="4"><i class="fas fa-exclamation"></i> 以下是超过30天没有跟进的客户 </td>',
            'no_followup' => '<td class="alert bg-maroon" colspan="4"><i class="fas fa-exclamation-triangle"></i> 以下是从来没有跟进过的客户</td>'
        ];
    }

    //行业分类
    private function businesses()
    {
        return \App\Models\Company\Business::orderBy('order')->get(); //所有行业
    }
    /**
     * 新增客户表单
     */
    public function create()
    {
        $this->data['customkey'] = $this->custom_key(); //所有的自定义字段
        $this->data['title'] = '新增客户';
        $this->data['note'] = '这里将新增属于您的客户！';
        $this->data['status'] = 0; //客户状态
        $this->data['data_source'] = 0; //客户来源
        $this->data['attribute'] = 0; //客户类型
        $this->data['country'] = 249; //默认为中国
        $this->data['state'] = 0; //默认为北京
        $this->data['multiple_feature'] = []; //客户特性
        $this->data['contacts'] = []; //联系人信息
        $this->data['countries'] = $this->countries(); //国家列表
        $this->data['businesses'] = $this->businesses(); //所属行业
        $this->data['action'] = route('companies.store');
        return $this->view('company.create');
    }
    /**
     * 接收客户新增
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:2|max:255|unique:App\Models\Company\Company',
            'country' => 'required|numeric',
        ], [], ['name' => '客户名称', 'country' => '国家/地区']);
        $this->save_company($request); //新增客户
        return redirect()->route('companies.index', ['order' => 'create']); //返回到更新列表(连续增加客户)
    }

    //将表单数据保存到客户公司数据库里
    private function save_company(Request $request, $id = 0)
    {
        if (empty($id)) {
            $company = new Company;
            $company->staff_id = Auth::id(); //登陆ID
            $company->staff_name = Auth::user()->name; //登陆人员名字
            $company->username = Auth::user()->username; //登陆人员用户名
        } else {
            $company = Company::find($id);
        }
        $company->name = $request->name;
        $company->url = $request->url ?? '';
        $company->telephone = $request->telephone ?? '';
        $company->address = $request->address ?? '';
        $company->credit_id = $request->credit_id ?? '';
        $company->attribute = empty($request->attribute) ? 0 : intval($request->attribute);
        $company->business = empty($request->business) ? 0 : intval($request->business);
        $company->data_source = intval($request->data_source);
        $company->is_vip = $request->is_vip ? 1 : 0;
        $company->country = intval($request->country);
        $company->state = empty($request->state) ? 0 : intval($request->state); //省份      
        $company->description = $request->description ?? '';
        $company->introduction = $request->introduction ?? '';
        $company->ip = $request->ip();
        $company->status = empty($request->status) ? 0 : intval($request->status);
        if ($company->isDirty()) {
            $company->save();
        }
        if (!empty($request->features) && is_array($request->features)) {
            $this->features($company->id, $request->features); //产品特性
        }
    }
    //公司产品特性处理
    private function features($id, $features)
    {
        Feature::where('company_id', $id)->delete(); //删除关联
        $features_arr = $this->custom_key('customer_features'); //产品特性（供多选）
        array_map(function ($feature) use ($id, $features_arr) {
            if (!empty($features_arr[$feature])) {
                Feature::create(['company_id' => $id, 'feature' => $feature, 'description' => $features_arr[$feature]]);
            }
        }, $features);
    }
    /**
     * 客户信息显示
     */
    public function show(string $id)
    {
        $this->data = Company::find($id);
        abort_if(empty($this->data), 404);
        if (!Auth::user()->isAdmin()) {
            abort_if($this->data->staff_id != Auth::id(), 403, '无权查看此客户');
        }
        $this->data['customkey'] = $this->custom_key(); //所有的自定义字段
        $this->data->country = $this->countries()[$this->data->country] ?? ['name' => '未知']; //所在国家
        $this->data->multiple_feature = $this->data->feature->map(function ($item) {
            return $item->description; //产品特点
        })->toArray();

        $this->data->status_text = (['跟进中', '无效客户', '已成交'])[$this->data->status] ?? '异常状态';
        $this->data->data_source_text = $this->data['customkey']['customer_source'][$this->data->data_source] ?? '未知';
        //行业信息
        foreach ($this->businesses() as $item) {
            if ($item->id == $this->data->business) {
                $this->data->business_text = $item->name;
                break;
            }
        }
        //联系人信息
        if (Auth::user()->isAdmin()) {
            $this->data->contacts = $this->data->contacts()->withTrashed()->get(); //管理员可看到回收站里的信息
        } else {
            $this->data->contacts = $this->data->contacts()->get();
        }
        return $this->view('company.show');
    }

    /**
     * 显示客户编辑表单
     */
    public function edit(string $id)
    {
        $this->data = Company::find($id);
        abort_if(empty($this->data), 404);
        if (!Auth::user()->isAdmin()) {
            abort_if($this->data->staff_id != Auth::id(), 403, '无权查看此客户');
        }
        $this->data['customkey'] = $this->custom_key(); //所有的自定义字段
        $this->data['title'] = '编辑客户《' . $this->data->name . '》';
        $this->data['note'] = '正在编辑《ID:' . $this->data->id . '》的相关信息！';
        $this->data['contacts'] = []; //联系人信息
        $this->data['countries'] = $this->countries(); //国家列表
        $this->data['multiple_feature'] = $this->data->feature->map(function ($item) {
            return $item->feature; //产品特点
        })->toArray();
        $this->data['businesses'] = $this->businesses(); //所属行业
        $this->data['action'] = route('companies.update', ['company' => $id]);
        return $this->view('company.create');
    }

    /**
     * 接收客户修改信息
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|min:2|max:255',
            'country' => 'required|numeric',
        ], [], ['name' => '客户名称',  'country' => '国家/地区']);
        $company = Company::find($id);
        abort_if(empty($company), 404);
        if (!Auth::user()->isAdmin()) {
            abort_if($company->staff_id != Auth::id(), 403, '无权修改此客户');
        }
        $unique = Company::withTrashed()->where('name', $request->name)->where('id', '<>', $id)->first();
        abort_if($unique, 403, '【客户名称】已存在于数据库中(id:' . ($unique->id ?? 0) . ')拥有人:' . ($unique->username ?? ''));
        $this->save_company($request, $company->id); //更新客户
        return redirect()->route('companies.show', ['company' => $id]); //返回到详细页(编辑后查看显示效果)
    }

    /**
     * 软删除客户
     */
    public function destroy(string $id)
    {
        $company = Company::find($id);
        abort_if(empty($company), 403, '客户不在存在~');
        if (!Auth::user()->isAdmin()) {
            abort_if($company->staff_id != Auth::id(), 403, '无权删除此客户');
        }
        $company->delete();
        return redirect()->back();
    }

    //移出回收站
    public function recover(int $id)
    {
        $company = Company::withTrashed()->find($id);
        abort_if(empty($company), 403, '客户不在存在~');
        if (!Auth::user()->isAdmin()) {
            abort_if($company->staff_id != Auth::id(), 403, '无权恢复此客户');
        }
        if ($company->trashed()) {
            $company->restore();
        }
        return redirect()->back();
    }

    //彻底删除
    public function forcedelete($id)
    {
        $company = Company::withTrashed()->find($id);
        abort_if(empty($company), 403, '客户不在存在~');
        if (Auth::user()->isAdmin()) {
            //1、删除联系人和联系方式
            $contacts = \App\Models\Company\Contact::where('company_id', $company->id)->get();
            //所有的联系方式
            foreach ($contacts as  $contact) {
                if (\App\Models\Company\ContactsMap::where('contact_id', $contact->id)->delete()) {
                    $contact->forceDelete();
                }
            }
            //2、删除预约
            \App\Models\Company\Appointment::where('company_id', $company->id)->forceDelete();
            //3、删除跟进
            \App\Models\Company\Followup::where('company_id', $company->id)->forceDelete();
            //4、删除附件
            $attachments = \App\Models\System\Attachment::where('company_id', $company->id)->get();
            foreach ($attachments as $attach) {
                if (\Illuminate\Support\Facades\Storage::delete($attach->file_path)) {
                    $attach->forceDelete(); //删除附件表关联内容
                }
            }
            //5、删除产品特性
            \App\Models\Company\Feature::where('company_id', $company->id)->delete();
            //6、删除关联的标签
            \App\Models\Tag\TagMap::where('taggable_id', $company->id)->delete();
            //最后：删除公司数据
            $company->forceDelete();
        }
        return redirect()->back();
    }
}
