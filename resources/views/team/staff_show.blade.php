@extends('base')
{{--
* @Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
* @LICENSE : http://www.apache.org/licenses/LICENSE-2.0
* @[KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
* @FilePath: /resources/views/team/staff_show.blade.php
* @Created Time: 2022-04-16 11:32:36
* @Last Edit Time: 2023-06-06 21:00:39
* @Description: 职员编辑
--}}
@push('head_mor')

@endpush
@section('content')
@include('blocks.content-header',['title' => $username,'note'=>$title,'fa'=>'fas fa-address-card'])
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle" src="{{$avatar_url}}">
                        </div>
                        <h3 class="profile-username text-center">{{$name}}</h3>
                        <p class="text-muted text-center">{{$job['name']}}</p>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>登陆次数</b> <span class="float-right badge bg-primary">{{$login_count}}</span>
                            </li>
                            <li class="list-group-item">
                                <b>预约数</b> <span class="float-right badge bg-info">{{$appointments_count}}</span>
                            </li>
                            <li class="list-group-item">
                                <b>客户数</b> <span class="float-right badge bg-success">{{$customers_count}}</span>
                            </li>
                            <li class="list-group-item">
                                <b>跟进数</b> <span class="float-right badge bg-danger">{{$follows_count}}</span>
                            </li>
                        </ul>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#logs" data-toggle="tab">操作记录</a></li>
                            <li class="nav-item"><a class="nav-link" href="#loginlogs" data-toggle="tab">登陆记录</a></li>
                            <li class="nav-item"><a class="nav-link" href="#todolist" data-toggle="tab">待办事项</a></li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="active tab-pane" id="logs">
                                <ol>
                                    @foreach ($logs as $log)
                                    <li>
                                        <span class=" badge bg-{{$colors[$loop->index%count($colors)]}}">{{$log->url}}</span>
                                        <span class="float-right badge bg-info">{{$log->ip}}</span>
                                        <span class="float-right badge"> {{$log->created_at}}</span>
                                    </li>
                                    @endforeach
                                </ol>

                            </div>
                            <!-- 登陆记录 -->
                            <div class="tab-pane" id="loginlogs">
                                <ol>
                                    @foreach ($loginlogs as $login)
                                    <li>
                                        <span class=" badge bg-{{$colors[$loop->index%count($colors)]}}">{{$login->created_at}}</span>
                                        <span class="float-right badge bg-info">{{$login->ip}}</span>
                                    </li>
                                    @endforeach
                                </ol>
                            </div>
                            <!-- 待办事项 -->
                            <div class="tab-pane" id="todolist">
                                @foreach ($todolist as $todo)
                                <li>
                                    <span class=" badge bg-{{$colors[$loop->index%count($colors)]}}">{{$todo->subject}}</span>
                                    <span class="float-right badge bg-info">{{$todo->created_at}}</span>
                                </li>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('foot_mor')

@endpush