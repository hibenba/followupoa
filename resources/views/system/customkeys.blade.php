@extends('base')
{{--
* @Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
* @LICENSE : http://www.apache.org/licenses/LICENSE-2.0
* @[KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
* @FilePath: /resources/views/system/customkeys.blade.php
* @Created Time: 2022-04-16 11:32:36
* @Last Edit Time: 2023-05-22 19:08:19
* @Description: 自定义字段列表
--}}
@push('head_mor')
<style type="text/css">


</style>
@endpush
@section('content')
@include('blocks.content-header',['title' => '自定义字段','note'=>'您通过本页面可以对自定义的字段进行修改编辑。','fa'=>'fas fa-key','newitem'=>'增加字段','link'=>route('admin.customkey.create'),'target'=>false])
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <div class="tables">
                        <span class="text-info font-weight-bold">
                            <i class="fas fa-file-signature"></i> 字段数
                            <small class="badge bg-success">{{$count}}</small>
                        </span>
                    </div>
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center"><i class="fas fa-ellipsis-h"></i> 字段名字</th>
                            <th style="width: 60%"><i class="fas fa-grip-vertical"></i> 字段内容</th>
                            <th class="text-center"><i class="fas fa-hashtag"></i> 字段Key</th>
                            <th class="text-center"><i class="fas fa-tools"></i> 操作</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray">
                        @foreach ($customkeys as $key)
                        <tr>
                            <td class="text-center" style="vertical-align: middle">
                                <h4><span class="badge bg-{{$colors[$loop->index]??'secondary'}}">{{$key->note}}</span></h4>
                            </td>
                            <td style="vertical-align: middle">{{ Arr::join($key->value, '，')}}</td>
                            <td class="text-center" style="vertical-align: middle">
                                <h6><span class="badge bg-{{$colors[$loop->index]??'secondary'}}">{{$key->key}}</span></h6>
                            </td>
                            <td class="text-center" style="vertical-align: middle">
                                <a href="{{route('admin.customkey.edit',['key'=>$key->key])}}" class="btn btn-info btn-xs m-1 edit"><i class="far fa-edit"></i> 编辑</a>
                                @if($key->type != 0)
                                <form action="{{route('admin.customkey.delete',['key'=>$key->key])}}" class="d-inline" method="POST"> @csrf @method('delete')
                                    <button type="submit" class="btn btn-outline-danger btn-xs" onclick="return confirm('您将彻底删除本字段，单击“确定”继续。单击“取消”停止。')"><i class="far fa-trash"></i> 删除</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
            @if ($customkeys->hasPages())
            <div class="card-footer pt-4">
                <div class="card-tools">{{$customkeys->links('blocks.pagination')}}</div>
            </div>
            @endif
        </div>
    </div>
</section>
@endsection
@push('foot_mor')


@endpush