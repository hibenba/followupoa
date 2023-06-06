@extends('base')
{{--
* @Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
* @LICENSE : http://www.apache.org/licenses/LICENSE-2.0
* @[KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
* @FilePath: /resources/views/log/loginlogs.blade.php
* @Created Time: 2022-04-16 11:32:36
* @Last Edit Time: 2023-04-04 15:25:29
* @Description: 登陆日志记录模板
--}}
@push('head_mor')

@endpush
@section('content')
@include('blocks.content-header',['title' => '登陆日志','note'=>'可查看登陆成功与失败的记录','fa'=>'fas fa-clipboard-list'])
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
                            <th><i class="far fa-clock"></i> 时间</th>
                            <th><i class="fas fa-user"></i> 用户名</th>
                            <th><i class="fas fa-map-marker-alt"></i> 登陆ip</th>
                            <th class="text-center"><i class="far fa-eye"></i> 状态</th>
                            <th class="text-center"><i class="fas fa-key"></i> 尝试密码</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray">
                        @foreach ($logs as $log)
                        <tr>
                            <td>{{$log->created_at}}</td>
                            <td>{{$log->username}}</td>
                            <td>{{$log->ip}}</td>
                            <td class="h5 text-center">
                                @if(empty($log->password))
                                <span class="badge badge-success">成功</span>
                                @else
                                <span class="badge badge-danger">失败</span>
                                @endif
                            </td>
                            <td class="text-center">{{Str::of($log->password)->mask('*', 2,-2)}}</td>
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