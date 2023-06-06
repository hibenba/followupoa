@extends('base')
{{--
* @Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
* @LICENSE : http://www.apache.org/licenses/LICENSE-2.0
* @[KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
* @FilePath: /resources/views/contact/create.blade.php
* @Created Time: 2022-04-16 11:32:36
* @Last Edit Time: 2023-05-21 17:41:35
* @Description: 新增联系方式
--}}
@push('head_mor')
<link rel="stylesheet" href="{{ asset('select2/css/select2.min.css') }}">
<style type="text/css">


</style>
@endpush

@section('content')
@include('blocks.content-header',['title' => $title,'note'=>$note,'fa'=>'fas fa-user-plu','newitem'=>'返回客户详情','link'=>route('companies.show',['company' => $company_id]),'target'=>false])
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
    <form action="{{route('contact.store')}}" class="container-fluid" id="company_form" class="p-2" method="POST"> @csrf
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">联系人主体信息</h3>
            </div>
            <div class="card-body">
                <div class="form-inline mb-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-file-signature mr-2"></i> 姓氏：</span>
                        </div>
                        <input name="last_name" autocomplete="off" class="form-control" type="text" placeholder="姓氏(Last name)" size="20" value="{{old('last_name',$last_name??'')}}">
                        <div class="input-group-prepend input-group-append">
                            <span class="input-group-text"><i class="fas fa-signature mr-2"></i> 名字：</span>
                        </div>
                        <input name="name" autocomplete="off" class="form-control w-50" type="text" placeholder="输入联系人的名(First name)" size="40" value="{{old('name',$name??'')}}">
                    </div>
                </div>

                <div class="form-inline mb-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user-ninja mr-2"></i> 担任职务:</span>
                        </div>
                        <input name="job" autocomplete="off" class="form-control" type="text" placeholder="输入联系人的职位，如：经理" size="30" value="{{old('job',$job??'')}}">
                    </div>

                    <div class="input-group mx-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">当前状态:</span>
                        </div>
                        <select name="status" class="custom-select">
                            <option value="0" @selected(old('status',$status)==0)>在职</option>
                            <option value="1" @selected(old('status',$status)==1)>离职</option>
                            <option value="2" @selected(old('status',$status)==2)>联系不上</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">重要联系人:</span>
                        </div>
                        <select name="is_main" class="custom-select">
                            <option value="0" @selected(old('is_main',$is_main)==0)>否</option>
                            <option value="1" @selected(old('is_main',$is_main)==1)>是</option>
                        </select>
                    </div>
                </div>
                <input name="id" type="hidden" value="{{$id??0}}">
                <input name="company_id" type="hidden" value="{{$company_id}}">
                <x-input_inline :title="'备注信息:'" :name="'description'" :value="$description??''" :placeholder="'请输入联系人的备注信息'" :note="'对联系人备注一些其它情况~'" />
            </div>
        </div>

        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">联系方式</h3>
            </div>
            <div class="card-body" id="contacts_warp">

                @foreach($relation as $cts)
                <div class="card bg-light p-2 mb-3 ">
                    <div class="card-body">
                        <button type="button" class="btn bg-info btn-sm float-right remove">
                            <i class="fas fa-times"></i>
                        </button>
                        <div class="form-inline mb-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">类别:</span>
                                </div>
                                <select name="relation_contact_type[]" class="custom-select">
                                    <option>请选择联系类别</option>
                                    @foreach ($contacts as $item => $value)
                                    <option value="{{$item}}" @selected($cts->contact_type==$item)>{{$value}}</option>
                                    @endforeach
                                </select>
                                <input name="relation_contact[]" autocomplete="off" class="form-control w-50" type="text" placeholder="输入联系方式" size="50" value="{{$cts->contact}}">
                            </div>
                            <div class="input-group mx-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">归属:</span>
                                </div>
                                <select name="relation_owner[]" class="custom-select">
                                    <option>选择联系方式归属</option>
                                    <option value="0" @selected($cts->owner==0)>属于个人</option>
                                    <option value="1" @selected($cts->owner==1)>属于公司</option>
                                    <option value="2" @selected($cts->owner==2)>未知归属</option>
                                </select>
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">本地联系:</span>
                                </div>
                                <input name="loacl_contact[]" autocomplete="off" class="form-control" type="text" placeholder="联系客户使用的设备信息，如:138***1503" size="30" value="{{$cts->loacl_contact}}">
                            </div>
                        </div>

                        <div class="form-inline">
                            <div class="input-group">
                                <select name="relation_status[]" class="custom-select">
                                    <option value="0" @selected($cts->status==0)>有效</option>
                                    <option value="1" @selected($cts->status==1)>无效</option>
                                </select>
                                <input name="invalid_reasons[]" autocomplete="off" class="form-control w-50" type="text" placeholder="如果无效请输入无效原因" size="60" value="{{$cts->invalid_reasons}}">
                            </div>
                            <div class="input-group mx-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">备注:</span>
                                </div>
                                <input name="relation_description[]" autocomplete="off" class="form-control" type="text" placeholder="输入备注" size="70" value="{{$cts->description}}">
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="card-footer clearfix">
                <span id="contact_add" class="btn btn-info float-right"><i class="fas fa-plus mr-2"></i> 增加联系方式</span>
            </div>
        </div>
        <div class="pb-3 text-center"><button type="submit" class="btn btn-block btn-warning"> 提 交 保 存 </button></div>
    </form>
</section>

<div id="base_contact" class="d-none">
    <div class="card bg-light p-2 mb-3 ">
        <div class="card-body">
            <button type="button" class="btn bg-info btn-sm float-right remove">
                <i class="fas fa-times"></i>
            </button>
            <div class="form-inline mb-3">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">类别:</span>
                    </div>
                    <select name="relation_contact_type[]" class="custom-select">
                        <option value="">请选择联系类别</option>
                        @foreach ($contacts as $item => $value)
                        <option value="{{$item}}">{{$value}}</option>
                        @endforeach
                    </select>
                    <input name="relation_contact[]" autocomplete="off" class="form-control w-50" type="text" placeholder="输入联系方式" size="50" value="">
                </div>
                <div class="input-group mx-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">归属:</span>
                    </div>
                    <select name="relation_owner[]" class="custom-select">
                        <option>选择联系方式归属</option>
                        <option value="0">属于个人</option>
                        <option value="1">属于公司</option>
                        <option value="2" selected>未知归属</option>
                    </select>
                </div>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">本地联系:</span>
                    </div>
                    <input name="loacl_contact[]" autocomplete="off" class="form-control" type="text" placeholder="联系客户使用的设备信息，如:138***1503" size="30" value="">
                </div>
            </div>

            <div class="form-inline">
                <div class="input-group">
                    <select name="relation_status[]" class="custom-select">
                        <option value="0">有效</option>
                        <option value="1">无效</option>
                    </select>
                    <input name="invalid_reasons[]" autocomplete="off" class="form-control w-50" type="text" placeholder="如果无效请输入无效原因" size="60" value="">
                </div>
                <div class="input-group mx-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">备注:</span>
                    </div>
                    <input name="relation_description[]" autocomplete="off" class="form-control" type="text" placeholder="输入备注" size="70" value="">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('foot_mor')
<!-- jquery-validation -->
<script src="{{asset('jquery-validation/jquery.validate.min.js')}}"></script>
<script>
    $(function () {
        @if(empty($id))
        $('#contacts_warp').append($('#base_contact').html());
        @endif
        /*增加联系方式*/
        $('#contact_add').click(function(){
            $('#contacts_warp').append($('#base_contact').html());
        });
        $('#contacts_warp').on('click', ".remove", function(){
            $(this).parent().parent().remove();
        });
        $("#company_form").validate({
            rules: {
                name: {required:true},
                "relation_contact_type[]": {required:true},
                "relation_contact[]": {required:true},
                
            },
            messages: {
                name: "请输入联系人的名字",
                "relation_contact_type[]": "请选择联系方式的类别",
                "relation_contact[]": "请输入联系方式",
            },
            errorElement: 'p',
            errorPlacement: function (error, element) {
                $(element).attr("data-original-title",error.text()).tooltip('show');             
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass){                
                $(element).removeClass('is-invalid').attr("data-original-title",'');                              
            }
        });
    });    
</script>
@endpush