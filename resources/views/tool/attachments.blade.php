@extends('base')
{{--
* @Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
* @LICENSE : http://www.apache.org/licenses/LICENSE-2.0
* @[KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
* @FilePath: /resources/views/tool/attachments.blade.php
* @Created Time: 2022-04-16 11:32:36
* @Last Edit Time: 2023-05-31 10:05:55
* @Description: 附件列表模板
--}}
@push('head_mor')

@endpush
@section('content')
@include('blocks.content-header',['title' => '附件管理','note'=>'您可以对附件编辑、删除、下载等操作','fa'=>'fas fa-paperclip'])
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <div class="tables">
                        <a href="{{route('admin.attachment')}}" @class(['btn','btn-sm'=> !empty($_GET['type']),'text-gray-dark' => !empty($_GET['type']),'text-success' => empty($_GET['type']),'font-weight-bold' => empty($_GET['type'])])>
                            <i class="fas fa-file-signature"></i> 附件数
                            <small class="badge bg-success">{{$count}}</small>
                        </a>
                        <a href="{{route('admin.attachment',['type'=>'trash'])}}" @class(['btn','btn-sm'=> empty($_GET['type']),'text-gray-dark' => empty($_GET['type']),'text-success' => !empty($_GET['type']),'font-weight-bold' => !empty($_GET['type'])])>
                            <i class="fas fa-trash-alt"></i> 回收站
                            <small class="badge bg-gradient-gray">{{$trashcount}}</small>
                        </a>
                    </div>
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 160px"><i class="far fa-clock"></i> 文件信息</th>
                            <th><i class="fas fa-user"></i> 所属公司</th>
                            <th class="text-center" style="width: 150px"><i class="fas fa-map-marker-alt"></i> 用户</th>
                            <th class="text-center"><i class="far fa-eye"></i> 文件大小</th>
                            <th class="text-center" style="width:200px"><i class="fas fa-tools"></i> 操作</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray">
                        @foreach ($attachments as $att)
                        <tr>
                            <td>
                                @if(empty($att->is_image))
                                <a class="btn btn-app"><i class="far fa-paperclip"></i> {{$att->file_type}}</a>
                                @else
                                <div class="overflow-hidden" style="max-height:100px"><img src="{{Storage::url($att->file_path)}}?{{Str::random(8)}}" class="btn product-image-thumb p-1" /></div>
                                @endif
                            </td>
                            <td class="align-middle">
                                <a href="{{route('companies.index',['id'=>$att->company_id])}}"> {{$att->company->name}} </a>
                            </td>
                            <td class="text-center align-middle"><span title="{{$att->ip}}">{{$att->staff_name}} </span></td>
                            <td class="text-center align-middle">{{$att->size}}</td>
                            <td class="text-center align-middle">
                                @if(empty($att->trashed()))
                                <a href="javascript:delete_attachment({{$att->id}})" class="text-danger"> <i class="fas fa-recycle"></i> 移入回收站</a>
                                @else
                                <form action="{{route('admin.attachment.delete',['id'=>$att->id])}}" class="d-inline" method="POST"> @csrf @method('delete')
                                    <button type="submit" class="btn btn-outline-danger btn-xs" onclick="return confirm('您将彻底删除本文件，单击“确定”继续。单击“取消”停止。')"><i class="far fa-trash"></i> 删除</button>
                                </form>
                                <form action="{{route('admin.attachment.delete',['id'=>$att->id])}}" class="d-inline mx-3" method="POST"> @csrf @method('put')
                                    <button type="submit" class="btn btn-outline-success btn-xs"><i class="fas fa-recycle"></i> 恢复</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
            @if ($attachments->hasPages())
            <div class="card-footer pt-4">
                <div class="card-tools">{{$attachments->links('blocks.pagination')}}</div>
            </div>
            @endif
        </div>
    </div>
</section>
@endsection
@push('foot_mor')

<script>
    /* 删除附件 */
    function delete_attachment(id){
        if(confirm('请确认是否真的要请本文件移入回收站？')){
            const data = {_token: '{{csrf_token()}}',id};
            $.ajax({
                url:'{{route('attachment.destroy')}}',type:'DELETE',data,
                success: function (result){
                    if(result.status == 'success'){                        
                        location.replace(location.href);
                    }else{
                        toast('bg-danger',result.msg,'删除过程中出现了一个错误：')
                    }
                }
            });
        }
    }

</script>

@endpush