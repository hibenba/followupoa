@extends('base')
{{--
* @Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
* @LICENSE : http://www.apache.org/licenses/LICENSE-2.0
* @[KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
* @FilePath: /resources/views/index.blade.php
* @Created Time: 2022-04-16 11:32:36
* @Last Edit Time: 2023-06-03 18:53:19
* @Description: 后台首页模板
--}}
@push('head_mor')

@endpush
@section('content')
@include('blocks.content-header',['title' => '我的工作台','note'=>'通过数据统计，了解当前工作进度与安排！','fa'=>'fas fa-tachometer-alt'])
<section class="content">
    <div class="container-fluid">
        <p class="alert alert-info">欢迎登陆系统，这是您第{{$user->login_count}}次登陆，登陆时间：{{$user->login_at}}，现在请开始你的工作！</p>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">您的统计信息如下：</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-12">
                        <a href="{{route('companies.index')}}">
                            <div class="info-box">
                                <span class="info-box-icon bg-teal"><i class="fas fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">客户数</span>
                                    <span class="info-box-number">{{$user->customers_count}}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <a href="{{route('companies.index')}}">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-stream"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">联系人</span>
                                    <span class="info-box-number">{{$user->contacts_count}}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <a href="{{route('companies.index')}}">
                            <div class="info-box">
                                <span class="info-box-icon bg-indigo"><i class="fas fa-spinner"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">跟进数</span>
                                    <span class="info-box-number">{{$user->follows_count}}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <a href="{{route('companies.index')}}">
                            <div class="info-box">
                                <span class="info-box-icon bg-olive"><i class="fas fa-tty"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">预约数</span>
                                    <span class="info-box-number">{{$user->appointments_count}}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- TO DO List -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ion ion-clipboard mr-1"></i> 待办事项
                </h3>
                <div class="card-tools">
                    <a href="{{ route('todolist.index') }}" class="btn btn-xs btn-secondary">更多 <i class="far fa-ellipsis-h"></i></a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <ul class="todo-list" data-widget="todo-list">
                    @foreach ($todolist as $todo)
                    <li>
                        <span class="handle">
                            <i class="fas fa-ellipsis-v"></i>
                            <i class="fas fa-ellipsis-v"></i>
                        </span>
                        <span class="text">{{$todo->subject}}</span>
                        <small class="badge badge-danger"><i class="far fa-clock"></i> {{$todo->time}}</small>
                    </li>
                    @endforeach
                </ul>
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
                <form action="{{ route('todolist.store') }}" method="POST"> @csrf
                    <div class="form-inline">
                        <div class="input-group">
                            <input type="text" class="form-control" value="" name="subject" size="80" placeholder="请输入“待办事项”">
                            <div class="input-group-append"><button type="submit" class="btn btn-primary">提交</button></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.card -->

        @if($user->isAdmin())
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">系统运行信息<small>(仅管理员可见)</small> </h2>
            </div>
            <div class="card-body">
                <ul>
                    <li>操作系统: <strong>{{PHP_OS}} / PHP v{{ PHP_VERSION }}</strong></li>
                    <li>环境上传许可: <strong>{{$fileupload}}</strong>（系统设置：{{$limit_upload_size}}）</li>
                    <li>程序路径: <strong>{{base_path()}}</strong></li>
                    <li>剩余空间: <strong data-toggle="tooltip" title="显示的是网站所在的目录的可用空间(可能会被系统限制)！">{{$df}}</strong></li>
                    <li>数据库版本: <strong>{{$sql_version}}</strong></li>
                    <li>PHP 版本: <strong>{{ PHP_VERSION }}</strong></li>
                    <li>数据库尺寸: <strong>{{$dbsize}}</strong></li>
                    <li>数据表数量: <strong>{{$dbnum}} </strong>张表</li>
                    <li>附件尺寸: <strong>{!!$attachsize!!}</strong></li>
                    <li>本站创建于:<time data-toggle="tooltip" title="{{$app_make_date}}">{{$app_make_time}}</time> </li>
                </ul>
            </div>
        </div>
        @if(!empty($online))
        <h5>在线管理用户</h5>
        <div class="table-responsive">
            <table class="table table-bordered text-nowrap">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>登陆时间</th>
                        <th>上次活动</th>
                        <th>IP</th>
                        <th>停留</th>
                        <th class="text-center">操作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($online as $staff)
                    <tr @class([ 'bg-gradient-success'=> $staff->token === request()->session()->getId()])>
                        <td>{{$staff->staff_id}}</td>
                        <td>{{$staff->username}}</td>
                        <td>{{$staff->created_at}}</td>
                        <td>{{$staff->updated_at}}</td>
                        <td>{{$staff->ip}}</td>
                        <td>{{$staff->route}}</td>
                        <td class="text-center">
                            <a href="/?kill={{$staff->token}}" class="btn btn-xs btn-outline-warning">强制下线 <i class="fas fa-sign-out-alt"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        @endif
    </div>
</section>
@endsection
@push('foot_mor')

@endpush