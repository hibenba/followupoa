@extends('base')
{{--
* @Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
* @LICENSE : http://www.apache.org/licenses/LICENSE-2.0
* @[KwokCMS] Ver 1.0 (C) 2022 Mr.Kwok
* @FilePath: /resources/views/company/create.blade.php
* @Created Time: 2022-04-16 11:32:36
* @Last Edit Time: 2023-05-21 17:14:19
* @Description: 新增客户
--}}
@push('head_mor')
<link rel="stylesheet" href="{{ asset('select2/css/select2.min.css') }}">
<style type="text/css">
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #007bff;
        border-color: #006fe6;
        color: #fff;
        padding: 0 10px;
        margin-top: 0.31rem;
    }
</style>
@endpush

@section('content')
@include('blocks.content-header',['title' => $title,'note'=>$note,'fa'=>'fas fa-user-plu','newitem'=>'返回客户列表','link'=>route('companies.index'),'target'=>false])
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
    <form action="{{$action}}" class="container-fluid" id="company_form" class="p-2" method="POST"> @csrf
        @if (!empty($id))@method('PUT')@endif
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">客户基本信息</h3>
            </div>
            <div class="card-body">
                <x-input_inline :title="'客户名称:'" :name="'name'" :value="$name??''" :required="1" :placeholder="'重庆恩祖科技有限公司'" :note="'请输入客户的名字（全称）。'" />
                <x-input_inline :title="'客户网址:'" :name="'url'" :value="$url??''" :placeholder="'例如:https://www.cqseo.net'" :note="'请输入客户官网的网址。'" />
                <x-input_inline :title="'客户电话:'" :name="'telephone'" :value="$telephone??''" :placeholder="'例如:023-6588**88'" :note="'请输入客户的联系电话。'" />
                <x-input_inline :title="'信用代码:'" :name="'credit_id'" :value="$credit_id??''" :placeholder="'例如:9150010657***123X'" :note="'标准规定统一社会信用代码用18位阿拉伯数字或大写英文字母表示。'" />

                <div class="form-inline mb-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">类型:
                                <a data-toggle="popover" class="ml-2" data-content="请管理员在【自定义字段】处修改【客户类型】项！" data-original-title="客户类型说明">
                                    <i class="fas fa-question-circle text-success"></i>
                                </a>
                            </span>
                        </div>
                        <select name="attribute" class="custom-select">
                            @foreach($customkey['customer_attribute'] as $order=>$value)
                            <option value="{{$order}}" @selected(old('attribute',$attribute)==$order)>{{$value}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="input-group mx-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">所属行业:
                                <a data-toggle="popover" class="ml-2" data-content="请管理员在【行业分类】处编辑此项！" data-original-title="所属行业说明">
                                    <i class="fas fa-question-circle text-success"></i>
                                </a>
                            </span>
                        </div>
                        <select name="business" class="custom-select">
                            <option value="">请选择客户所在的行业</option>
                            @foreach($businesses as $value)
                            <option value="{{$value->id}}" @selected(old('business',$business??0)==$value->id)>{{$value->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">客户状态:
                                <a data-toggle="popover" class="ml-2" data-content="请管理员在【自定义字段】处修改【客户状态】项！" data-original-title="客户状态说明">
                                    <i class="fas fa-question-circle text-success"></i>
                                </a>
                            </span>
                        </div>
                        <select name="status" class="custom-select">
                            @foreach($customkey['customer_status'] as $order=>$value)
                            <option value="{{$order}}" @selected(old('status',$status)==$order)>{{$value}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="input-group mx-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">客户来源:
                                <a data-toggle="popover" class="ml-2" data-content="请管理员在【自定义字段】处修改【客户来源】项！" data-original-title="客户来源说明">
                                    <i class="fas fa-question-circle text-success"></i>
                                </a>
                            </span>
                        </div>
                        <select name="data_source" class="custom-select">
                            @foreach($customkey['customer_source'] as $order=>$value)
                            <option value="{{$order}}" @selected(old('data_source',$data_source)==$order)>{{$value}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="icheck-danger mx-3">
                        <input type="checkbox" @checked(old('is_vip',$is_vip??false)) value="1" name="is_vip" id="is_vip">
                        <label for="is_vip" class="text-purple">VIP客户</label>
                    </div>
                </div>

                <div class="form-inline mb-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">国家/地区: <strong class="text-danger ml-2" title="必填字段">*</strong></span>
                        </div>
                        <select name="country" class="custom-select">
                            <option value="">请选择客户所在的国家</option>
                            @foreach($countries as $order=>$value)
                            <option value="{{$order}}" @selected(old('country',$country)==$order)>{{$value['pinyin']}}-{{$value['name']}}</option>
                            @endforeach
                        </select>
                        <select name="state" class="custom-select">
                            <option value="">请选择州省</option>
                            @foreach($countries[$country]['state'] as $order=>$value)
                            <option value="{{$order}}" @selected(old('state',$state)==$order)>{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <x-input_inline :title="'地址:'" :name="'address'" :value="$address??''" :placeholder="'重庆市沙坪坝区磁器口***22#3-1'" :note="'请输入客户的办公地址。'" />
                <div class="form-group mb-3">
                    <label>客户特性:
                        <a data-toggle="popover" data-content="请管理员在【自定义字段】处修改【客户特性】项，可让客户更细分的排序！" data-original-title="特性说明">
                            <i class="fas fa-question-circle text-success"></i>
                        </a>
                    </label>
                    <select name="features[]" class="multiple-select w-75" multiple="multiple" data-placeholder="请选择客户的特性">
                        @foreach($customkey['customer_features'] as $order=>$value)
                        <option value="{{$order}}" @selected(in_array($order,old('features',$multiple_feature)))>{{$value}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="card card-secondary" id="information">
            <div class="card-header">
                <h3 class="card-title">详细信息</h3>
            </div>
            <div class="card-body">
                <div class="form-group mb-3">
                    <label class="h5 my-3 mx-2">客户简介: <span class="text-muted mx-3 font-weight-normal small">输入客户的详细介绍</span></label>
                    <textarea class="form-control" rows="10" cols="56" placeholder="如：改客户拥有自主研发各种管理系统能力，可以定制各种网站、内部系统、手机APP等软件开发！" name="introduction">{{old('introduction',$introduction??'')}}</textarea>
                </div>

                <div class="form-group mb-3">
                    <label class="h5 my-3 mx-2">备注信息: <span class="text-muted mx-3 font-weight-normal small">输入客户的备注信息及一些额外的描述</span></label>
                    <textarea class="form-control" rows="10" cols="56" placeholder="如：具有一定的市场引导能力，有部分定价权" name="description">{{old('description',$description??'')}}</textarea>
                </div>
            </div>
        </div>
        <div class="pb-3 text-center"><button type="submit" class="btn btn-block btn-warning"> 提 交 保 存 </button></div>
    </form>
</section>
@endsection
@push('foot_mor')
<script src="{{ asset('select2/js/select2.full.min.js') }}"></script>
<!-- tinymce -->
<script src="{{ asset('tinymce/tinymce.min.js') }}"></script>
<!-- jquery-validation -->
<script src="{{asset('jquery-validation/jquery.validate.min.js')}}"></script>
<script>
    $(function () {
        $('.multiple-select').select2();
        tinymce.init({
            selector: 'textarea',
            language: 'zh-Hans',
            branding: false,
            promotion: false,
            height: 320,
            plugins: 'autolink autosave link wordcount quickbars', 
            menubar: false,
            toolbar: 'undo redo | bold italic underline strikethrough | fontsize blocks ',
            quickbars_selection_toolbar: 'bold quicklink fontsize forecolor removeformat',
            quickbars_insert_toolbar: false,/* 'image media quicktable'关闭快速插入 */       
            contextmenu: false,
        }); 
        /* 输入表单验证 */      
@if (empty($id))const id = 0;@else const id = $id;@endif         
         $('#company_form').validate({
            rules: {
                name: {
                    required:true,
                    maxlength: 255,
                    remote: {
                        type: 'post',
                        url: '{{route('company.checkdata')}}',
                        dataType: "json",
                        data: {
                            _token: '{{csrf_token()}}',
                            id,
                            'name':function(){return $("input[name='name']").val()}
                        }
                    }
                }
            },
            messages: {
                name: {required: "请输入客户名称",maxlength: "客户名称长度不应该超过255个字符",remote: "客户已存在于数据库中"}
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

<script>
    const countries = {!!json_encode($countries)!!};
    $(document).ready(function() {
        /*国家与城市的选择*/
        $("select[name='country']").bind("change", function() {
            var thisState = $("select[name='state']");
            var typeId = $(this).val();           
            var  thisOption = countries[typeId].state;           
            thisState.html('<option value="0">请选择州省</option>');
            if (thisOption) {
                for (var i = 0; i < thisOption.length; i++) {
                    thisState.append('<option value="' + (i + 1) + '">' + thisOption[i] + '</option>');
                }
            }
        });
    });
</script>
@endpush