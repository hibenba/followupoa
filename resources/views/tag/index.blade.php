@extends('base')
{{--
* @Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
* @LICENSE : http://www.apache.org/licenses/LICENSE-2.0
* @[KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
* @FilePath: /resources/views/tag/index.blade.php
* @Created Time: 2022-04-16 11:32:36
* @Last Edit Time: 2023-06-06 09:52:05
* @Description: 标签列表模板
--}}
@push('head_mor')

@endpush
@section('content')
@include('blocks.content-header',['title' => '标签管理','note'=>'管理用户新增的标签','fa'=>'fas fa-tags'])
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <div class="tables">
                        <span class="btn text-success font-weight-bold">
                            <i class="fas fa-user-tag"></i> 标签总数 <small class="badge bg-success">{{$count}}</small>
                        </span>
                    </div>
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th style="width:50px"><i class="fas fa-hashtag"></i></th>
                            <th><i class="fas fa-tag"></i> 标签名</th>
                            <th><i class="fas fa-globe"></i> 使用比</th>
                            <th class="text-center" style="width:250px"><i class="fas fa-tools"></i> 操作</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray">
                        @foreach ($tags as $tag)
                        <tr>
                            <td>{{$tag->id}}</td>
                            <td>
                                <h4><span class="badge bg-{{$tag->color}}">{{$tag->name}}</span></h4>
                            </td>
                            <td>
                                <div class="progress mt-2" data-toggle="tooltip" title="使用了{{$tag->tag_map->count()}}次">
                                    <div class="progress-bar progress-bar-danger" style="width:{{$tag->tag_map->count()}}%"></div>
                                </div>
                            </td>
                            <td class="text-center" data-tagid="{{$tag->id}}">
                                <a href="{{route('tags.edit',['tag'=>$tag->id])}}" class="btn btn-info btn-xs m-1 edit"><i class="far fa-edit"></i> 编辑</a>
                                <form action="{{route('tags.destroy',['tag'=>$tag->id])}}" class="d-inline" method="POST"> @csrf @method('delete')
                                    <button type="submit" class="btn btn-outline-danger btn-xs" onclick="return confirm('您将彻底删除本标签，单击“确定”继续。单击“取消”停止。')"><i class="far fa-trash"></i> 删除</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
            @if ($tags->hasPages())
            <div class="card-footer pt-4">
                <div class="card-tools">{{ $tags->links('blocks.pagination')}}</div>
            </div>
            @endif
        </div>
    </div>
</section>
@endsection
@push('foot_mor')

@endpush