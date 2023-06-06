@extends('base')
{{--
* @Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
* @LICENSE : http://www.apache.org/licenses/LICENSE-2.0
* @[KwokCMS] Ver 1.0 (C) 2022 Mr.Kwok
* @FilePath: /resources/views/company/business/create.blade.php
* @Created Time: 2022-04-16 11:32:36
* @Last Edit Time: 2023-05-09 19:53:38
* @Description: 新增行业
--}}
@push('head_mor')

@endpush

@section('content')
@include('blocks.content-header',['title' => $title,'note'=>$note,'fa'=>'fas fa-user-plu','newitem'=>'返回行业列表','link'=>route('business.index'),'target'=>false])
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
                <h3 class="card-title">{{$title}}</h3>
            </div>
            <div class="card-body">
                <x-input_inline :title="'行业名称:'" :name="'name'" :value="$name??''" :required="1" :placeholder="'例如:互联网科技'" :note="'请输入行业名称。'" />

                <div class="form-inline mb-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">排列顺序:</span>
                        </div>
                        <input name="order" size="3" class="form-control" style="max-width:200px" type="number" value="{{old('order',$order??0)}}">
                    </div>
                    <span class="text-muted ml-3">输入的数字最小，排序更靠前。</span>
                </div>

                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">上级行业:</span>
                    </div>
                    <select name="upid" class="custom-select" style="max-width:200px">
                        <option value="0" @selected(old('upid',$upid)==0)>做为顶级行业分类</option>
                        @foreach($businesses as $value)
                        <option value="{{$value->id}}" @selected(old('upid',$upid)==$value->id)>{{$value->name}}</option>
                        @endforeach
                    </select>
                    <span class="text-muted ml-3">请选择行业所属的层级。</span>
                </div>

                <div class="form-inline my-3">
                    <div class="input-group">
                        <div class="input-group-text">是否可选:</div>
                        <div class="icheck-cyan mx-3">
                            <input type="radio" id="status1" name="status" value="1" @checked(old('status',$status)==1)>
                            <label for="status1">开启</label>
                        </div>
                        <div class="icheck-cyan">
                            <input type="radio" id="status0" name="status" value="0" @checked(old('status',$status)==0)>
                            <label for="status0">关闭</label>
                        </div>
                    </div>
                    <span class="text-muted ml-3">推荐顶级分类为不可选，并设置其子分类为可选！</span>
                </div>

                <div class="form-group mb-3">
                    <label class="h6 my-3 mx-2">行业介绍: <span class="text-muted mx-3 font-weight-normal small">请输入行业描述/介绍信息。</span></label>
                    <textarea class="form-control" rows="5" cols="60" placeholder="行业的简单介绍" name="description">{{old('description',$description??'')}}</textarea>
                </div>
            </div>
        </div>

        <div class="pb-3 text-center"><button type="submit" class="btn btn-block btn-warning"> 提 交 保 存 </button></div>
    </form>
</section>
@endsection
@push('foot_mor')

@endpush