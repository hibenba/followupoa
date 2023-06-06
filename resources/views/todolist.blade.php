@extends('base')
@section('content')
<!-- 内容头 -->
@include('blocks.content-header',['title' => '待办事项','note'=>'管理需要处理的事项','fa'=>'fas fa-calendar-check'])
<section class="content">
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-pills card-title">
                <li class="nav-item">
                    <a @class(['nav-link', 'active'=> empty($status)]) href="{{route('todolist.index')}}">待办事项 <small class="badge bg-info">{{$count}}</small></a>
                </li>
                <li class="nav-item">
                    <a @class(['nav-link', 'active'=> !empty($status)]) href="{{route('todolist.index',['todo'=>1])}}">已完成项 <small class="badge bg-success">{{$completed}}</small></a>
                </li>
            </ul>
            <div class="card-tools"><button type="button" id="additem" class="btn btn-secondary btn-sm"><i class="fas fa-plus"></i> 增加一个事项</button></div>
        </div>
        <div class="card-body" id="todo-list">
            <ul class="todo-list" data-widget="todo-list">
                @foreach ($todolist as $todo)
                <li id="todo{{$todo->id}}">
                    <div class="icheck-primary d-inline ml-2">
                        <input type="checkbox" value="{{$todo->id}}" name="todo[]" id="todoCheck{{$todo->id}}" @checked(!empty($todo->complete_at))>
                        <label for="todoCheck{{$todo->id}}"></label>
                    </div>
                    <!-- todo text -->
                    <span class="text">{{$todo->subject}}</span>
                    <!-- Emphasis label -->
                    <small class="badge badge-danger"><i class="far fa-clock"></i> {{$todo->time}}</small>
                    <!-- General tools such as edit or delete-->
                    <div class="tools">
                        <i class="fas fa-trash" data-tid="{{$todo->id}}"></i>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            @if ($todolist->hasPages())
            <div class="card-tools">{{ $todolist->links('blocks.pagination')}}</div>
            @endif
        </div>
    </div>
</section>

<!-- 模态 -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- 模态框头部 -->
            <div class="modal-header">
                <h4 class="modal-title">增加一个事项</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- 模态框主体 -->
            <form action="{{ route('todolist.store') }}" method="POST"> @csrf
                <div class="modal-body text-center">
                    <div class="form-inline">
                        <div class="input-group">
                            <input type="text" class="form-control" value="" name="subject" size="80" placeholder="请输入“待办事项”">
                            <div class="input-group-append"><button type="submit" class="btn btn-primary">提交</button></div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('foot_mor')
<script>
    $(function(){       
        $('#additem').click(function(){$('#myModal').modal({show:true})});
        $(':input[name="todo[]"]').click(function(){
            $.ajax({
                url: "{{route('todolist.update',['todolist'=>0])}}",
                type: 'PUT',
                data: {_token: '{{csrf_token()}}',id:$(this).val(),checked:$(this).is(":checked")?1:0},
                success: function (result){                   
                    toast('bg-'+result.status,result.status == 'success'?'设置事项状态成功~':'处理失败：' + result.msg)                  
                },
                error:function(xhr){console.log(xhr.responseText)}
            });          
        });
        $('.fa-trash').click(function(){
            if(confirm('请确认是否真的要【删除】本事项？')){    
                var id =$(this).data('tid');
                $.ajax({
                    url: "{{route('todolist.destroy',['todolist'=>0])}}",
                    type: 'DELETE',
                    data: {_token: '{{csrf_token()}}',id:id},
                    success: function (result){                    
                        if (result.status == 'success') {
                            toast('bg-success','本事项已成功删除！')
                            $('#todo' + id).remove();
                        } else {
                            alert('删除失败，原因：' + result.msg);
                        }
                    },
                    error:function(xhr){console.log(xhr.responseText)}
                });
            }
        });
    });
</script>
@endpush