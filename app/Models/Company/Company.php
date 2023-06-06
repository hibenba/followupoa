<?php

namespace App\Models\Company;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /app/Models/Company/Company.php
 * Created Time: 2023-03-16 11:15:59
 * Last Edit Time: 2023-06-03 13:31:56
 * Description: 客户公司信息Model
 */

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; //软删除
use Illuminate\Database\Eloquent\Relations\HasMany; //一对多
use Illuminate\Database\Eloquent\Relations\HasOne; //一对一
use App\Models\System\Attachment; //附件Model
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Casts\Attribute; //属性修改器
use Illuminate\Support\Str;

class Company extends Model
{
    use SoftDeletes; //开启软删除
    protected $fillable = [
        'name', //客户名称
        'url', //网址
        'telephone', //电话
        'is_vip', //是否VIP
        'country', //国家
        'state', //省
        'address', //地址
        'business', //所属行业
        'credit_id', //统一信用代码
        'description', //备注
        'introduction', //简介
        'ip',
        'attribute', //属性(个人、公司、组织、政府等)
        'data_source', //数据来源
        'status', //客户状态(自定义)
        'invalid_cause', //无效原因
        'staff_id',
        'username',
        'staff_name',
    ];

    //员工信息
    public function staff(): HasOne
    {
        return $this->hasOne(\App\Models\Staff\Staff::class, 'id', 'staff_id');
    }
    //跟进记录
    public function followups(int $limit = 10): HasMany
    {
        return $this->hasMany(Followup::class)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->select('id', 'company_id', 'staff_id', 'staff_name', 'username', 'message', 'created_at', 'updated_at'); //限制字段
    }
    //特性
    public function feature(): HasMany
    {
        return $this->hasMany(Feature::class);
    }
    //预约记录
    public function appointments(int $limit = 10): HasMany
    {
        return $this->hasMany(Appointment::class)
            ->orderByDesc('track_at')
            ->limit($limit)
            ->select('id', 'company_id', 'staff_id', 'staff_name', 'username', 'message', 'created_at', 'updated_at', 'track_at', 'status'); //限制字段
    }
    //上传的附件
    public function attachments(int $limit = 10): HasMany
    {
        return $this->hasMany(Attachment::class)
            ->orderByDesc('updated_at')
            ->limit($limit)
            ->select('id', 'company_id', 'staff_id', 'staff_name', 'username', 'file_path', 'file_type', 'file_size', 'file_name', 'description', 'is_image', 'created_at'); //限制字段
    }
    //联系人主体
    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class)
            ->orderByDesc('is_main') //重要联系人优先排前面
            ->orderByDesc('updated_at');
    }
    //获取文章TAGs(多对多多态)
    public function tags()
    {
        //与Tag标签表关联
        return $this->morphToMany(\App\Models\Tag\Tag::class, 'taggable', 'tags_maps') //关系名(类别字段名将以_type格式)中间表tags_maps,关联ID,关系键tag_id
            ->select('id', 'name', 'color'); //只需要标签ID，标签名
    }
    //公司列表资源控制器
    static public function company($type)
    {
        $where = ['status' => 0]; //正常客户
        if (!Auth::user()->isAdmin()) {
            $where['staff_id'] =  Auth::id(); //非管理员限制员工ID
        }
        switch ($type) {
            case 'deals':
                $where['status'] = 2; //成交
                break;
            case 'useless':
                $where['status'] = 1; //无效客户
                break;
            case 'trash':
                return Auth::user()->isAdmin() ? self::onlyTrashed() : self::onlyTrashed()->where('staff_id', Auth::id()); //回收站
            default:
                break;
        }
        return self::where($where);
    }
}
