@extends('base')
{{--
* @Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
* @LICENSE : http://www.apache.org/licenses/LICENSE-2.0
* @[KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
* @FilePath: /resources/views/tag/create.blade.php
* @Created Time: 2022-04-16 11:32:36
* @Last Edit Time: 2023-06-06 09:51:25
* @Description: 标签列表模板
--}}
@push('head_mor')

@endpush
@section('content')
@include('blocks.content-header',['title' => '编辑标签《'.$name.'》','note'=>'ID：'.$id,'fa'=>'fas fa-tags','newitem'=>'返回标签列表','link'=>route('tags.index'),'target'=>false])
<section class="content">
    @if ($errors->any())
    <div class="alert alert-danger">
        <h5 class="mb-3 text-warning">请处理以下错误后，重新提交：</h5>
        <ol>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ol>
    </div>
    @endif
    <div class="container-fluid">
        <div class="card">
            <div class="card-body p-0">
                <form action="{{route('tags.update',['tag'=>$id])}}" class="d-inline" method="POST"> @csrf @method('put')
                    <div class="modal-body">
                        <div class="form-inline my-3">
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">标签:</span></div>
                                <input name="name" class="form-control" type="text" size="30" value="{{old('name',$name)}}">
                                <select name="color" class="custom-select bg-{{$color??''}}">
                                    <option value="">不使用颜色</option>
                                    @foreach($colors as $clr)
                                    <option value="{{$clr}}" @selected(old('color',$color??'')==$clr)>{{$clr}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <span class="text-muted m-3">将修改标签及与客户关联的信息。</span>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="input-group">
                            <input type="submit" value=" 提 交 修 改 " class="btn btn-info submit">
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</section>
@endsection
@push('foot_mor')
<script>
    $(function() {
        $('select[name="color"]').change(function(){
            $(this).removeClass().addClass('custom-select').addClass('bg-' + $(this).val());
        });
    })
</script>
@endpush