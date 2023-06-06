@extends('base')
{{--
* @Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
* @LICENSE : http://www.apache.org/licenses/LICENSE-2.0
* @[KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
* @FilePath: /resources/views/system/country_create.blade.php
* @Created Time: 2022-04-16 11:32:36
* @Last Edit Time: 2023-05-23 18:12:27
* @Description: 国家修改表单
--}}
@push('head_mor')
<style type="text/css">

</style>
@endpush
@section('content')
@include('blocks.content-header',['title' => $title,'note'=>$note,'fa'=>'fas fa-user-plu','newitem'=>'返回列表','link'=>route('admin.country.index'),'target'=>false])
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
            @if (!empty($id))@method('PUT')@endif
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">{{$note}}</h3>
                </div>
                <div class="card-body">
                    <div class="form-inline mb-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">国家/地区名称:</span>
                            </div>
                            <input name="name" autocomplete="off" class="form-control" type="text" placeholder="如：中国" size="30" value="{{old('name',$name??'')}}">
                        </div>
                        <span class="text-muted ml-3">请输国家/地区名称中文名字。</span>
                    </div>
                    <div class="form-inline mb-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">英文缩写:</span>
                            </div>
                            <input name="abbr" autocomplete="off" class="form-control" type="text" size="30" value="{{old('abbr',$abbr??'')}}">
                        </div>
                        <span class="text-muted ml-3">请输入英文缩写。</span>
                    </div>
                    <div class="form-inline mb-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">拼音首字母:</span>
                            </div>
                            <input name="pinyin" autocomplete="off" class="form-control" type="text" placeholder="如：Z" size="30" value="{{old('pinyin',$pinyin??'')}}">
                        </div>
                        <span class="text-muted ml-3">请输国家/地区名称拼音的首字母。</span>
                    </div>

                    <div class="form-inline mb-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">电话区号:</span>
                            </div>
                            <input name="area_code" autocomplete="off" class="form-control" type="text" placeholder="如：+86" size="30" value="{{old('area_code',$area_code??'')}}">
                        </div>
                        <span class="text-muted ml-3">请输国家/地区电话号码的国际区号。</span>
                    </div>

                    <div class="form-inline mb-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">所在时区:</span>
                            </div>
                            <select name="timezone" class="custom-select">
                                <option value="">请选择客户所在的行业</option>
                                @foreach($timeoffset as $timezone => $name)
                                <option value="{{$timezone}}" @selected(old('timezone',$timezone??0)==$timezone)>{{$name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend ml-3">
                                <span class="input-group-text">夏令时:</span>
                            </div>
                            <select name="daylight" class="custom-select">
                                <option value="1" @selected(old('daylight',$daylight)==1)>是</option>
                                <option value="0" @selected(old('daylight',$daylight)==0)>否</option>
                            </select>
                        </div>
                        <span class="text-muted ml-3">请选择所属时区。</span>
                    </div>



                    <div class="form-group" id="key_warp">
                        <span class="input-group-text">主要城市:</span>
                        @foreach(json_decode($state, true) as $city)
                        <div class="input-group my-3">
                            <input name="cities[]" autocomplete="off" class="form-control" type="text" size="30" value="{{$city}}">
                            <button type="button" class="btn bg-info btn-sm input-group-append remove">
                                <i class="fas fa-times pt-2"></i>
                            </button>
                        </div>
                        @endforeach
                    </div>
                    <span id="key_add" class="btn btn-info float-right"><i class="fas fa-plus mr-2"></i> 增加一个城市</span>
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