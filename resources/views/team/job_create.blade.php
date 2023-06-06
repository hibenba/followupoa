@extends('base')
{{--
* @Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
* @LICENSE : http://www.apache.org/licenses/LICENSE-2.0
* @[KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
* @FilePath: /resources/views/team/job_create.blade.php
* @Created Time: 2022-04-16 11:32:36
* @Last Edit Time: 2023-06-06 10:05:15
* @Description: 职位编辑
--}}
@push('head_mor')

@endpush
@section('content')
@include('blocks.content-header',['title' => $title,'note'=>'您可以对本团队的职位信息进行编辑工作。','fa'=>'fas fa-people-arrows'])
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
    <form action="{{route('admin.job.store')}}" class="container-fluid" id="company_form" class="p-2" method="POST"> @csrf
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title">职位信息</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool">
                        <a href="{{route('admin.job.index')}}" class="btn btn-info btn-xs m-1"><i class="fas fa-angle-double-left"></i> 返回职位列表</a>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <x-input_inline :title="'职位名称:'" :name="'name'" :value="$name??''" :required="1" :placeholder="'部门主管'" :note="'请输入职位的名称。'" />
                <x-input_inline :title="'职责描述:'" :name="'description'" :value="$description??''" :required="1" :placeholder="'请输入职位的相关说明'" :note="'可解释说明职位可操作的一些权限。'" />
                <div class="form-inline my-3">
                    <div class="form-group text-primary">
                        <div class="icheck-primary d-inline">
                            <input type="checkbox" name="add_company" id="add_company" @checked(old('add_company',$add_company??''))>
                            <label for="add_company"> 新增客户权限 </label>
                        </div>
                    </div>

                    <div class="form-group mx-3 text-info">
                        <div class="icheck-info d-inline">
                            <input type="checkbox" name="edit_company" id="edit_company" @checked(old('edit_company',$edit_company??''))>
                            <label for="edit_company"> 编辑客户权限 </label>
                        </div>
                    </div>

                    <div class="form-group text-indigo">
                        <div class="icheck-indigo d-inline">
                            <input type="checkbox" name="is_admin" id="is_admin" @checked(old('edit_company',$edit_company??''))>
                            <label for="is_admin"> 管理员权限 </label>
                        </div>
                    </div>

                    <div class="input-group mx-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">显示颜色:</span>
                        </div>
                        <select name="color" class="custom-select bg-{{$color??'success'}}">
                            @foreach($colors as $clr)
                            <option value="{{$clr}}" @selected(old('color',$color??'')==$clr)>{{$clr}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-footer text-center">
                <button type="submit" class="btn btn-light"> 提 交 保 存 </button>
                <input type="hidden" name="id" value="{{$id??0}}">
            </div>
        </div>
    </form>
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