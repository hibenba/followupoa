@extends('base')
{{--
* @Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
* @LICENSE : http://www.apache.org/licenses/LICENSE-2.0
* @[KwokCMS] Ver 1.0 (C) 2022 Mr.Kwok
* @FilePath: /resources/views/company/business/index.blade.php
* @Created Time: 2022-04-16 11:32:36
* @Last Edit Time: 2023-04-25 12:20:18
* @Description: 客户所属行业列表
--}}
@push('head_mor')
<style type="text/css">
    .subcat {
        display: none
    }

    b.subline {
        display: inline-block;
        border-bottom: 1px solid #ccc;
        border-left: 1px solid #ccc;
        width: 1.3rem;
        height: 1.6rem;
        position: absolute;
        left: .9rem;
        top: .3rem;
    }
</style>
@endpush
@section('content')
@include('blocks.content-header',['title' => '行业管理','note'=>'修改/编辑客户所属行业。','fa'=>'fas fa-user-plu','newitem'=>'新增行业','link'=>route('business.create'),'target'=>false])
<section class="content">
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-striped projects">
                <thead>
                    <tr>
                        <th style="width:1%">#</th>
                        <th style="width:15%"><i class="far fa-list-alt"></i> 行业名称</th>
                        <th style="width:50%"><i class="fas fa-file-alt"></i> 行业说明</th>
                        <th style="width:6%" class="text-center"><i class="fas fa-sort-numeric-down"></i> 排序</th>
                        <th style="width:15%" class="text-center"><i class="fas fa-tools"></i> 管理</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($businesses as $business)
                    <tr class="business" data-cid="{{$business->id}}" id="business{{$business->id}}">
                        <td>{{$business->id}}</td>
                        <td>
                            <a href="javascript:showsub({{$business->id}})" id="toggleclass{{$business->id}}" class="pr-2 fas fa-caret-right"></a>
                            <h6 class="d-inline">{{$business->name}}</h6>
                        </td>
                        <td>
                            <input name="description" class="form-control" type="text" size="50" placeholder="输入行业说明并会自动更新" value="{{$business->description}}">
                        </td>
                        <td class="project-order">
                            <input name="order" class="form-control" type="number" min="0" size="3" value="{{$business->order}}">
                        </td>
                        <td class="project-actions text-center">
                            <a class="btn btn-info btn-sm" href="{{route('business.edit',['business'=>$business->id])}}"><i class="fas fa-pencil-alt"></i> 修改</a>
                            <a class="btn btn-danger btn-sm" href="javascript:deltet_cat({{$business->id}});"><i class="fas fa-trash"></i> 删除</a>
                        </td>
                    </tr>
                    <!--子分类/增加子分类-->
                    @if ($business->businesses->isNotEmpty())
                    @foreach ($business->businesses as $subcat)
                    <tr class="subcat sub{{$business->id}}" data-cid="{{$subcat->id}}" id="business{{$subcat->id}}">
                        <td class="text-gray">{{$subcat->id}}</td>
                        <td class="position-relative pl-5"><b class="subline"></b> {{$subcat->name}}</td>
                        <td>
                            <input name="description" class="form-control" type="text" size="50" placeholder="输入行业说明并会自动更新" value="{{$subcat->description}}">
                        </td>
                        <td class="project-order">
                            <input name="order" class="form-control" type="number" min="0" size="3" value="{{$subcat->order}}">
                        </td>
                        <td class="project-actions text-center">
                            <a class="btn btn-info btn-sm" href="{{route('business.edit',['business'=>$subcat->id])}}"><i class="fas fa-pencil-alt"></i> 修改</a>
                            <a class="btn btn-danger btn-sm" href="javascript:deltet_cat({{$subcat->id}});"><i class="fas fa-trash"></i> 删除</a>
                        </td>
                    </tr>
                    @endforeach
                    @endif

                    <tr class="subcat sub{{$business->id}}">
                        <td class="table-catid"> </td>
                        <td colspan="6" class="position-relative pl-5">
                            <b class="subline"></b>
                            <a type="button" href="{{route('business.create',['upid'=>$business->id])}}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-plus"></i> 添加下级行业</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
</section>
@endsection
@push('foot_mor')
<script>
    $(function(){
        $('input[name="description"]').change(function(){
            const id =  $(this).parents('tr').data('cid');
            const description =  $(this).val();
            const data = {_token: '{{csrf_token()}}',description,id};
            $.ajax({
                url:'{{route('business.api.update')}}',type:'PUT',data,
                success: function (result){
                    return result.status == 'success'?toast('bg-success','行业描述已更新~'):toast('bg-danger',result.msg,'出现一个错误');                    
                }
            });
        });
        $('input[name="order"]').change(function(){
            const id =  $(this).parents('tr').data('cid');
            const order =  $(this).val();
            const data = {_token: '{{csrf_token()}}',order,id};
            $.ajax({
                url:'{{route('business.api.update')}}',type:'PUT',data,
                success: function (result){
                    return result.status == 'success'?toast('bg-success','排序已更新~请刷新页面查看。'):toast('bg-danger',result.msg,'出现一个错误');                    
                }
            });   
        });
    });
    /* 分类单个展开/收缩 */
    function showsub(catid) {
        $('.sub' + catid).toggle();
        $('#toggleclass' + catid).toggleClass("fa-caret-down").toggleClass("fa-caret-right");
    }
    /* 删除分类 */
    function deltet_cat(id){
        if(confirm('请确认是否真的要【删除】本行业？')){         
            $.ajax({
                url: "{{route('business.destroy',['business'=>0])}}",
                type: 'DELETE',
                data: {_token: '{{csrf_token()}}',id: id},
                success: function (result){
                    if (result.status == 'success') {
                        toast('bg-success','行业已成功删除！')
                        $('#business' + id).remove();
                    } else {
                        alert('删除失败，原因：' + result.msg);
                    }
                },
                error:function(xhr){console.log(xhr.responseText)}
            });           
        }
    }
</script>
@endpush