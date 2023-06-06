@extends('base')
{{--
* @Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
* @LICENSE : http://www.apache.org/licenses/LICENSE-2.0
* @[KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
* @FilePath: /resources/views/team/staff_create.blade.php
* @Created Time: 2022-04-16 11:32:36
* @Last Edit Time: 2023-06-06 20:28:25
* @Description: 职员编辑
--}}
@push('head_mor')

@endpush
@section('content')
@include('blocks.content-header',['title' => $title,'note'=>'您可以对本团队的职位信息进行编辑工作。','fa'=>'fas fa-id-card'])
<section class="content">
    @if ($errors->any())
    <div class="alert alert-danger">
        <h5 class="mb-3 text-warning">请处理以下错误后，重新提交：</h5>
        <ol>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ol>
    </div>
    @endif
    <form action="{{route('admin.staff.store')}}" class="container-fluid" class="p-2" method="POST"> @csrf
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">职员信息</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool">
                        <a href="{{route('admin.staff.index')}}" class="btn btn-info btn-xs m-1"><i class="fas fa-angle-double-left"></i> 返回员工列表</a>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <x-input_inline :title="'用 户 名:'" :name="'username'" :value="$username??''" :required="1" :placeholder="'用户名'" :note="'请输入登陆时使用的用户名。'" />
                <x-input_inline :title="'真实姓名:'" :name="'name'" :value="$name??''" :required="1" :placeholder="'员工姓名'" :note="'请输入员工的真实姓名。'" />

                <div class="form-inline mb-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">登陆密码:
                                @if(empty($id))
                                <strong class="text-danger ml-2" title="必填字段">*</strong>
                                @endif
                            </span>
                        </div>
                        <input name="password" autocomplete="off" placeholder="{{empty($id)?'':'保持原来的密码'}}" class=" form-control" type="password" size="80" value="">
                    </div>
                    <span class="text-muted ml-3">{{empty($id)?'请输入登陆时使用的密码。':'如需修改登陆密码请输入！'}}</span>
                </div>
                <x-input_inline :title="'常用邮箱:'" :name="'email'" :value="$email??''" :placeholder="'邮箱'" :note="'请输入员工使用的邮箱地址。'" />
                <x-input_inline :title="'手机号码:'" :name="'mobilenumber'" :value="$mobilenumber??''" :placeholder="'手机号码'" :note="'请输入员工的手机号码。'" />
                <x-input_inline :title="'头衔/昵称:'" :name="'title'" :value="$nickname??''" :placeholder="'个性化名字'" :note="'请输入头衔或者个性化的名称。'" />
                <div class="form-inline mb-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">性 别:</span>
                        </div>
                        <select name="sex" class="custom-select">
                            <option value="2" @selected(old('sex',$sex??'')==2)>未知</option>
                            <option value="0" @selected(old('sex',$sex??'')==0)> 女 </option>
                            <option value="1" @selected(old('sex',$sex??'')==1)> 男 </option>
                        </select>
                    </div><span class="text-muted ml-3">请选择员工的性别。</span>
                </div>
                <div class="form-inline mb-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">所属部门:</span>
                        </div>
                        <select name="group" class="custom-select">
                            <option value="" @selected(old('group',$group_id??'')==0)>请选择所属部门</option>
                            @foreach ($groups as $group)
                            <option value="{{$group->id}}" @selected(old('group',$group_id??'')==$group->id)>{{$group->name}}</option>
                            @endforeach
                        </select>
                    </div><span class="text-muted ml-3">请选择员工的性别。</span>
                </div>
                <x-input_inline :title="'身份证号:'" :name="'identity'" :value="$identity??''" :placeholder="'身份证号码'" :note="'请输入员工的身份证号码。'" />
                <div class="form-inline mb-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">在职状态:</span>
                        </div>
                        <select name="status" class="custom-select">
                            <option value="0" @selected(old('status',$status??'')==0)>在职</option>
                            <option value="1" @selected(old('status',$status??'')==1)>离职</option>
                        </select>
                    </div><span class="text-muted ml-3">设置为“离职”后，此用户请不能再次登陆到本系统。</span>
                </div>
            </div>
            <div class="card-footer text-center">
                <button type="submit" class="btn btn-light"> 提 交 保 存 </button>
                <input type="hidden" name="id" value="{{$id??0}}">
            </div>
        </div>
    </form>
</section>
@endsection

@push('foot_mor')

@endpush