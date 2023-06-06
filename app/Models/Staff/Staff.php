<?php

namespace App\Models\Staff;

/**
 * Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
 * LICENSE : http://www.apache.org/licenses/LICENSE-2.0
 * [KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
 * FilePath: /app/Models/Staff/Staff.php
 * Created Time: 2022-04-24 21:42:37
 * Last Edit Time: 2023-06-06 16:04:50
 * Description: 员工表Model(Staff复数没有s)
 */

use Illuminate\Database\Eloquent\Factories\HasFactory; //模型工厂
use Illuminate\Foundation\Auth\User as Authenticatable; //用户验证
use Illuminate\Notifications\Notifiable; //消息报告
use Laravel\Sanctum\HasApiTokens; //API Tokens
use Illuminate\Database\Eloquent\Casts\Attribute; //字段修改器
use Illuminate\Support\Str; //字符处理
use Illuminate\Support\Facades\Storage; //文件处理
use Illuminate\Database\Eloquent\Relations\HasOne; //一对一
class Staff extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    public $timestamps = false; //不维护时间
    protected $appends = ['avatar_url'];    //追加字段（虚拟字段访问器里的内容）
    //头像链接
    public function getAvatarUrlAttribute()
    {
        return empty($this->attributes['avatar']) ?
            asset('user' . $this->attributes['sex'] . '.jpg') : //通过sex生成头像
            Storage::url($this->attributes['avatar']);
    }
    // 定义模型的修改器（Mutator）
    public function setPasswordAttribute($value)
    {
        // 在设置 password 字段值时的处理逻辑
        $this->attributes['password'] = bcrypt($value);
    }
    /**
     * 允许通过Model操作的字段
     * @var array<int, string>
     */
    protected $fillable = [
        'group_id',
        'username',
        'name',
        'password',
        'email',
        'mobilenumber',
        'title',
        'sex',
        'last_login_ip',
        'avatar',
        'avatar_at',
        'created_at',
        'updated_at',
        'login_at',
        'identity',
        'remember_token',
        'status'
    ];

    /**
     * 序列化时(如：Json），需要隐藏的字段
     * @var array<int, string>
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    //处理email写入转为小写
    protected function email(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => Str::lower($value),
        );
    }
    //职位信息
    public function job(): HasOne
    {
        return $this->hasOne(\App\Models\Staff\StaffGroup::class, 'id', 'group_id');
    }
    /**
     * 说明: 当前用户是否为管理员
     * @param string: void
     * @return: void
     * @version Release: 1.0
     */
    public function isAdmin()
    {
        return $this->group_id == 1;
    }
}
