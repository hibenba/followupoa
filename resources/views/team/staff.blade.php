@extends('base')
{{--
* @Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
* @LICENSE : http://www.apache.org/licenses/LICENSE-2.0
* @[KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
* @FilePath: /resources/views/team/staff.blade.php
* @Created Time: 2022-04-16 11:32:36
* @Last Edit Time: 2023-06-06 20:17:20
* @Description: 职员管理
--}}
@push('head_mor')

@endpush
@section('content')
@include('blocks.content-header',['title' => '职员管理','note'=>'您可以对本公司的职员信息进行管理工作。','fa'=>'fas fa-user-friends','newitem'=>'增加员工','link'=>route('admin.staff.create'),'target'=>false])
<section class="content">
    <div class="container-fluid">
        <div class="row">
            @foreach ($employees as $employee)
            <div class="col-md-4">
                <div class="card card-widget widget-user-2 shadow-sm position-relative">
                    <div class="ribbon-wrapper ribbon-lg">
                        <div class="ribbon bg-primary">
                            {{$employee->job->name}}
                        </div>
                    </div>
                    <div class="widget-user-header bg-{{$employee->status==1?'gray': $employee->job->color}}">
                        <div class="widget-user-image">
                            <img class="img-circle elevation-2" src="{{$employee->avatar_url}}">
                        </div>
                        <h3 class="widget-user-username">{{$employee->username}}</h3>
                        <h5 class="widget-user-desc">{{$employee->name}} {{$employee->status==1?'(已离职)':''}}</h5>
                    </div>
                    <div class="card-footer p-0">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    登陆次数 <span class="float-right badge bg-primary">{{$employee->login_count}}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    预约数 <span class="float-right badge bg-info">{{$employee->appointments_count}}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    客户数 <span class="float-right badge bg-success">{{$employee->customers_count}}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    跟进数 <span class="float-right badge bg-danger">{{$employee->follows_count}}</span>
                                </a>
                            </li>

                            <li class="nav-item text-center py-3">
                                <a href="{{route('admin.staff.edit',['staff'=>$employee->id])}}" class="btn btn-info btn-xs edit">
                                    <i class="fas fa-user-edit"></i> 编辑</a>
                                @if($employee->status == 1)
                                <button type="button" class="btn btn-xs btn-danger working mx-3" data-cid="{{$employee->id}}"><i class="fas fa-laptop-code"></i> 设为在职</button>
                                <form action="{{route('admin.staff.destroy',['staff'=>$employee->id])}}" class="d-inline" method="POST"> @csrf @method('delete')
                                    <button type="submit" class="btn btn-xs btn-outline-danger" onclick="return confirm('您将彻底删除本员工信息，单击“确定”继续。单击“取消”停止。')"><i class="far fa-trash"></i> 删除</button>
                                </form>
                                @else
                                <button type="button" class="btn btn-xs working mx-3 btn-secondary" data-cid="{{$employee->id}}"><i class="fas fa-heart-broken"></i> 设为离职</button>
                                @endif
                                <a href="{{route('admin.staff.show',['staff'=>$employee->id])}}" class="btn btn-info btn-xs edit">
                                    <i class="far fa-id-card"></i> 查看详情</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- /.widget-user -->
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection

@push('foot_mor')
<script>
    $(function() {
        /* 设置离职状态 */
        $('.working').click(function(){
            const id = $(this).data('cid');
            var data = {_token: '{{csrf_token()}}',id};
            $.ajax({
                url:'{{route('admin.staff.update',['staff'=>'0'])}}',
                type:'PUT',
                data,
                success: function (result){
                    if(result.status == 'success'){
                        window.location.reload()
                    }else{
                        alert('状态设置失败，原因：' + result.msg)
                    }
                }
            });
        });
    })
</script>

@endpush