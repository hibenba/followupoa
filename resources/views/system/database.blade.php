@extends('base')
{{--
* @Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
* @LICENSE : http://www.apache.org/licenses/LICENSE-2.0
* @[KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
* @FilePath: /resources/views/system/database.blade.php
* @Created Time: 2022-04-16 11:32:36
* @Last Edit Time: 2023-05-23 15:07:16
* @Description: 自定义字段列表
--}}
@push('head_mor')
<style type="text/css">


</style>
@endpush
@section('content')
@include('blocks.content-header',['title' => '数据库维护','note'=>'您通过本页面对数据库进行维护操作。','fa'=>'fas fa-database','newitem'=>'备份全部数据','link'=>route('admin.database.backups'),'target'=>false])
<section class="content">
    <div class="container-fluid">
        @if(session('message'))<div class="alert alert-success">{{ session('message') }}</div>@endif
        <div class="card">
            <div class="card-body p-0">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center"><i class="fas fa-table"></i> 数据表</th>
                            <th class="text-center"><i class="fas fa-grip-vertical"></i> 数据量</th>
                            <th class="text-center"><i class="fas fa-hashtag"></i> 碎片量</th>
                            <th class="text-center"><i class="fas fa-tools"></i> 说明</th>
                            <th class="text-center"><i class="fas fa-tools"></i> 操作</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray">
                        @foreach ($tables as $table)
                        <tr>
                            <td class="text-center">{{$table->Name}}</td>
                            <td class="text-center">
                                <span class="badge bg-info">{{$table->Rows}} 行</span>
                            </td>

                            <td class="text-center">
                                @if($table->Data_free>0)
                                <h5><span class="badge bg-danger">{{$table->free_size}}</span></h5>
                                @else
                                -
                                @endif
                            </td>
                            <td class="text-center">{{$table->Comment}}</td>
                            <td class="text-center">
                                @if($table->Data_free>0)
                                <form action="{{route('admin.database.datafree',['table'=>$table->Name])}}" class="d-inline" method="POST"> @csrf
                                    <button type="submit" class="btn btn-outline-success btn-xs"><i class="fas fa-compress-arrows-alt"></i> 优化</button>
                                </form>
                                <form action="{{route('admin.database.repair',['table'=>$table->Name])}}" class="d-inline" method="POST"> @csrf
                                    <button type="submit" class="btn btn-outline-info btn-xs mx-2"><i class="fas fa-toolbox"></i> 修复</button>
                                </form>
                                @endif
                                <form action="{{route('admin.database.backup',['table'=>$table->Name])}}" class="d-inline" method="POST"> @csrf
                                    <button type="submit" class="btn btn-secondary btn-xs btn-xs"><i class="fas fa-database"></i> 备份</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</section>
@endsection
@push('foot_mor')


@endpush