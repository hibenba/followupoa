@extends('base')
{{--
* @Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
* @LICENSE : http://www.apache.org/licenses/LICENSE-2.0
* @[KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
* @FilePath: /resources/views/system/country.blade.php
* @Created Time: 2022-04-16 11:32:36
* @Last Edit Time: 2023-05-23 16:52:16
* @Description: 自定义字段列表
--}}
@push('head_mor')
<style type="text/css">


</style>
@endpush
@section('content')
@include('blocks.content-header',['title' => '国家地区','note'=>'您通过本页面对国家地区进行管理操作。','fa'=>'fas fa-globe','newitem'=>'增加','link'=>route('admin.country.create'),'target'=>false])
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <div class="tables">
                        <span class="text-info font-weight-bold">
                            <i class="fas fa-file-signature"></i> 国家数
                            <small class="badge bg-success">{{count($countries)}}</small>
                        </span>
                    </div>
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center"><i class="far fa-flag"></i> 国家/地区</th>
                            <th><i class="fas fa-grip-vertical"></i> 英文缩写</th>
                            <th class="text-center"><i class="fas fa-phone"></i> 电话区号</th>
                            <th style="width: 60%" class="text-center"><i class="far fa-city"></i> 主要城市</th>
                            <th class="text-center"><i class="fas fa-tools"></i> 操作</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray">
                        @foreach ($countries as $country)
                        <tr>
                            <td class="text-center">{{$country->name}}</td>
                            <td class="text-center">{{$country->abbr}}</td>
                            <td class="text-center">{{$country->area_code}}</td>
                            <td>{{$country->state}}</td>
                            <td class="text-center" style="vertical-align: middle">
                                <a href="{{route('admin.country.edit',['country'=>$country->id])}}" class="btn btn-info btn-xs m-1 edit"><i class="far fa-edit"></i> 编辑</a>
                                <form action="{{route('admin.country.destroy',['country'=>$country->id])}}" class="d-inline" method="POST"> @csrf @method('delete')
                                    <button type="submit" class="btn btn-outline-danger btn-xs" onclick="return confirm('您将彻底删除本数据，单击“确定”继续。单击“取消”停止。')"><i class="far fa-trash"></i> 删除</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
@push('foot_mor')


@endpush