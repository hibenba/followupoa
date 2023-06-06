@extends('base')
{{--
* @Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
* @LICENSE : http://www.apache.org/licenses/LICENSE-2.0
* @[KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
* @FilePath: /resources/views/team/job.blade.php
* @Created Time: 2022-04-16 11:32:36
* @Last Edit Time: 2023-06-06 09:44:51
* @Description: 职位管理
--}}
@push('head_mor')

@endpush
@section('content')
@include('blocks.content-header',['title' => '职位管理','note'=>'您可以对本团队的职位信息进行管理工作。','fa'=>'fas fa-people-arrows','newitem'=>'增加职位','link'=>route('admin.job.create'),'target'=>false])
<section class="content">
    <div class="container-fluid">
        @foreach ($jobs as $job)
        <div class="card card-{{$job->color}} card-outline">
            <div class="card-header">
                <h3 class="card-title text-{{$job->color}}">
                    <i class="fas fa-user-tag"></i> {{$job->name}}
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool">
                        <a href="{{route('admin.job.edit',['job'=>$job->id])}}" class="btn btn-info btn-xs m-1 edit"> <i class="fas fa-user-edit"></i> 编辑</a>
                    </button>
                    @if($job->type != -1)
                    <form action="{{route('admin.job.destroy',['job'=>$job->id])}}" class="d-inline" method="POST"> @csrf @method('delete')
                        <button type="submit" class="btn btn-xs" onclick="return confirm('您将彻底删除本职位，单击“确定”继续。单击“取消”停止。')"><i class="far fa-trash"></i> 删除</button>
                    </form>
                    @endif
                </div>
            </div>
            <div class="card-body" data-cid="{{$job->id}}">
                <div class="form-inline">
                    <div class="form-group text-primary">
                        <div class="icheck-primary d-inline">
                            <input type="checkbox" name="add_company" id="add_company{{$job->id}}" @checked($job->add_company)>
                            <label for="add_company{{$job->id}}"> 新增客户权限 </label>
                        </div>
                    </div>

                    <div class="form-group mx-3 text-info">
                        <div class="icheck-info d-inline">
                            <input type="checkbox" name="edit_company" id="edit_company{{$job->id}}" @checked($job->edit_company)>
                            <label for="edit_company{{$job->id}}"> 编辑客户权限 </label>
                        </div>
                    </div>

                    <div class="form-group text-indigo">
                        <div class="icheck-indigo d-inline">
                            <input type="checkbox" name="is_admin" id="is_admin{{$job->id}}" @checked($job->is_admin)>
                            <label for="is_admin{{$job->id}}"> 管理员权限 </label>
                        </div>
                    </div>

                    <div class="input-group mx-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">类型:</span>
                        </div>
                        <select class="custom-select" disabled>
                            <option @selected($job->type==-1)>系统</option>
                            <option @selected($job->type!=-1)>自定义</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-footer">{{$job->description}}</div>
        </div>
        @endforeach
    </div>
</section>
@endsection

@push('foot_mor')
<script>
    $(function() {
        /* 新增客户权限 */
        $('input[name="add_company"]').change(function(){
            job_update($(this).parents('.card-body').data('cid'),'add_company');
        });
        /* 编辑客户权限 */
         $('input[name="edit_company"]').change(function(){
            job_update($(this).parents('.card-body').data('cid'),'edit_company');
        });
        /* 管理员权限 */
        $('input[name="is_admin"]').change(function(){
            job_update($(this).parents('.card-body').data('cid'),'is_admin');
        });
        
    })
    /* 通过Ajax更新职位信息 */
    function job_update(id,type){
        var data = {_token: '{{csrf_token()}}',id,type};
        $.ajax({
            url:'{{route('admin.job.update',['job'=>'0'])}}',
            type:'PUT',
            data,
            success: function (result){return result.status == 'success'?toast('bg-success','权限修改成功~'):alert('权限设置失败，原因：' + result.msg)}
        });
    }
</script>
@endpush