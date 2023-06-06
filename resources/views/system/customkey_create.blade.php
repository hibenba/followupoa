@extends('base')
{{--
* @Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
* @LICENSE : http://www.apache.org/licenses/LICENSE-2.0
* @[KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
* @FilePath: /resources/views/system/customkey_create.blade.php
* @Created Time: 2022-04-16 11:32:36
* @Last Edit Time: 2023-05-23 17:00:07
* @Description: 自定义字段列表
--}}
@push('head_mor')
<style type="text/css">

</style>
@endpush
@section('content')
@include('blocks.content-header',['title' => $title,'note'=>$note,'fa'=>'fas fa-user-plu','newitem'=>'返回字段列表','link'=>route('admin.customkeys'),'target'=>false])
<section class="content">
    <div class="container-fluid">
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
            @if (!empty($key))@method('PUT')@endif
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">{{$note}}</h3>
                </div>
                <div class="card-body">
                    <div class="form-inline mb-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">字段名字:</span>
                            </div>
                            <input name="note" @disabled($type==0) autocomplete="off" class="form-control" type="text" placeholder="用中文描述字段" size="30" value="{{old('note',$note_text??'')}}">
                        </div>
                        <span class="text-muted ml-3">请输入字段的中文名字。</span>
                    </div>
                    <div class="form-inline mb-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">字段调用Key:</span>
                            </div>
                            <input name="key" @disabled($type==0) autocomplete="off" class="form-control" type="text" placeholder="key" size="30" value="{{old('key',$key??'')}}">
                        </div>
                        <span class="text-muted ml-3">请输入字段的Key,仅支持(a~z和_)。</span>
                    </div>
                    <div class="form-group" id="key_warp">
                        <span class="input-group-text">字段内容:</span>
                        @foreach($value as $i=>$v)
                        <div class="input-group my-3">
                            <input name="value[{{$i}}]" autocomplete="off" class="form-control" type="text" size="30" value="{{$v}}">
                            <button type="button" class="btn bg-info btn-sm input-group-append remove">
                                <i class="fas fa-times pt-2"></i>
                            </button>
                        </div>
                        @endforeach
                    </div>
                    <span id="key_add" class="btn btn-info float-right"><i class="fas fa-plus mr-2"></i> 增加一个字段</span>
                </div>
                <div class="card-footer text-center"><button type="submit" class="btn btn-block btn-warning"> 提 交 保 存 </button></div>
            </div>
        </form>
    </div>
</section>
@endsection
@push('foot_mor')
<div id="base_key" class="d-none">
    <div class="input-group my-3">
        <input name="value[]" autocomplete="off" class="form-control" type="text" size="30" value="">
        <button type="button" class="btn bg-info btn-sm input-group-append remove">
            <i class="fas fa-times pt-2"></i>
        </button>
    </div>
</div>
<script>
    $(function () {
        $('#key_warp').on('click', ".remove", function(){
            $(this).parent().remove();
        });
        /*增加字段*/
        $('#key_add').click(function(){
            $('#key_warp').append($('#base_key').html());
        });
    });
</script>
@endpush