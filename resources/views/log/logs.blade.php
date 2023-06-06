@extends('base')
{{--
* @Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
* @LICENSE : http://www.apache.org/licenses/LICENSE-2.0
* @[KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
* @FilePath: /resources/views/log/logs.blade.php
* @Created Time: 2022-04-16 11:32:36
* @Last Edit Time: 2023-04-04 19:38:56
* @Description: 日志记录模板
--}}
@push('head_mor')

@endpush
@section('content')
@include('blocks.content-header',['title' => '访问日志','note'=>'可查看每个员工的后台访问记录','fa'=>'fas fa-clipboard-list'])
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <div class="tables">
                        <span class="text-info font-weight-bold">
                            <i class="fas fa-file-signature"></i> 日志数
                            <small class="badge bg-success">{{$count}}</small>
                        </span>
                    </div>
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center"><i class="far fa-clock"></i> 时间</th>
                            <th class="text-center"><i class="fas fa-user"></i> 访问者</th>
                            <th class="text-center"><i class="fas fa-map-marker-alt"></i> ip</th>
                            <th><i class="fas fa-link"></i> 访问地址</th>
                            <th><i class="fas fa-network-wired"></i> 客户端</th>
                            <th class="text-center"><i class="far fa-keyboard"></i> 请求内容</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray">
                        @foreach ($logs as $log)
                        <tr>
                            <td class="text-center">{{$log->created_at}}</td>
                            <td class="text-center">{{$log->username}}</td>
                            <td class="text-center">{{$log->ip}}</td>
                            <td>{{$log->url}}</td>
                            <td>{{$log->user_agent}}</td>
                            <td class="text-center"><i class="fas fa-laptop-code"></i></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
            @if ($logs->hasPages())
            <div class="card-footer pt-4">
                <div class="card-tools">{{$logs->links('blocks.pagination')}}</div>
            </div>
            @endif
        </div>
    </div>
</section>
@endsection
@push('foot_mor')

@endpush