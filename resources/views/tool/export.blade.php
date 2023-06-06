@extends('base')
{{--
* @Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
* @LICENSE : http://www.apache.org/licenses/LICENSE-2.0
* @[KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
* @FilePath: /resources/views/tool/export.blade.php
* @Created Time: 2022-04-16 11:32:36
* @Last Edit Time: 2023-06-03 17:26:32
* @Description: 数据导出
--}}
@push('head_mor')

@endpush
@section('content')
@include('blocks.content-header',['title' => '数据导出','note'=>'您可以对本系统里的客户按条件进行导出操作','fa'=>'fas fa-clipboard-list'])
<section class="content">
    <div class="container-fluid">
        <form action="{{route('admin.export.post')}}" method="POST"> @csrf
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">客户信息</h3>
                </div>
                <div class="card-body">
                    <div class="input-group">
                        <label class="btn input-group-text mr-2">选择导出字段</label>
                        @foreach ($companies as $item => $value)
                        <div class="custom-control custom-checkbox m-2">
                            <input name="columns[{{$item}}]" type="checkbox" class="custom-control-input" @checked(in_array($item,$checkes)) id="columns{{$item}}" value="{{$item}}">
                            <label class="custom-control-label" for="columns{{$item}}">{{$value}}</label>
                        </div>
                        @endforeach
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    全选/不选则导出所有的字段
                </div>
            </div>
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">导出条件</h3>
                </div>
                <div class="card-body">

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">导出跟进记录:</span>
                        </div>
                        <div class="icheck-cyan mx-3">
                            <input type="radio" id="followups1" name="followups" value="1">
                            <label for="followups1">是</label>
                        </div>
                        <div class="icheck-cyan">
                            <input type="radio" id="followups0" name="followups" checked="" value="0">
                            <label for="followups0">否</label>
                        </div>
                    </div>

                    <div class="input-group my-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">导出预约记录:</span>
                        </div>
                        <div class="icheck-cyan mx-3">
                            <input type="radio" id="appointments1" name="appointments" value="1">
                            <label for="appointments1">是</label>
                        </div>
                        <div class="icheck-cyan">
                            <input type="radio" id="appointments0" name="appointments" checked="" value="0">
                            <label for="appointments0">否</label>
                        </div>
                    </div>

                    <div class="input-group my-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">导出联系人:</span>
                        </div>
                        <div class="icheck-cyan mx-3">
                            <input type="radio" id="contacts1" name="contacts" value="1">
                            <label for="contacts1">是</label>
                        </div>
                        <div class="icheck-cyan">
                            <input type="radio" id="contacts0" name="contacts" checked="" value="0">
                            <label for="contacts0">否</label>
                        </div>
                    </div>

                    <div class="input-group my-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">只导出重要客户:</span>
                        </div>
                        <div class="icheck-cyan mx-3">
                            <input type="radio" id="vip1" name="vip" value="1">
                            <label for="vip1">是</label>
                        </div>
                        <div class="icheck-cyan">
                            <input type="radio" id="vip0" name="vip" checked="" value="0">
                            <label for="vip0">否</label>
                        </div>
                    </div>


                    <div class="input-group"><label class="btn input-group-text mr-2">所属行业</label>
                        @foreach ($businesses as $value)
                        <div class="custom-control custom-checkbox m-2">
                            <input name="business[]" type="checkbox" class="custom-control-input" id="business{{$value['id']}}" value="{{$value['id']}}">
                            <label class="custom-control-label" for="business{{$value['id']}}">{{$value['name']}}</label>
                        </div>
                        @endforeach
                    </div>

                    <div class="input-group my-3">
                        <label class="btn input-group-text mr-2">所属国家</label>
                        <select class="form-control" name="country">
                            <option value="0">请选择要限定的国家</option>
                            @foreach ($countries as $country)
                            <option value="{{$country['id']}}">{{$country['pinyin']}} - {{$country['name']}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="input-group my-3">
                        <label class="btn input-group-text mr-2">客户类型</label>
                        @foreach ($customkey['customer_attribute'] as $item=>$value)
                        <div class="custom-control custom-checkbox m-2">
                            <input name="attribute[]" type="checkbox" class="custom-control-input" id="attribute{{$item}}" value="{{$item}}">
                            <label class="custom-control-label" for="attribute{{$item}}">{{$value}}</label>
                        </div>
                        @endforeach
                    </div>

                    <div class="input-group my-3">
                        <label class="btn input-group-text mr-2">客户来源</label>
                        @foreach ($customkey['customer_source'] as $item=>$value)
                        <div class="custom-control custom-checkbox m-2">
                            <input name="source[]" type="checkbox" class="custom-control-input" id="source{{$item}}" value="{{$item}}">
                            <label class="custom-control-label" for="source{{$item}}">{{$value}}</label>
                        </div>
                        @endforeach
                    </div>

                    <div class="input-group my-3">
                        <label class="btn input-group-text mr-2">客户状态</label>
                        @foreach ($customkey['customer_status'] as $item=>$value)
                        <div class="custom-control custom-checkbox m-2">
                            <input name="status[]" type="checkbox" class="custom-control-input" id="status{{$item}}" value="{{$item}}">
                            <label class="custom-control-label" for="status{{$item}}">{{$value}}</label>
                        </div>
                        @endforeach
                    </div>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <label class="btn input-group-text">按关键字:</label>
                        </div>
                        <input name="keywords" autocomplete="off" class="form-control" type="text" placeholder="输入客户名称的“关键字”" size="10" value="">
                    </div>
                    <div class="form-inline my-3">
                        <label class="input-group-text">数据排序按:</label>
                        <div class="input-group-prepend mx-3">
                            <select class="form-control" name="odertype">
                                <option value="created">发布时间</option>
                                <option value="updated">修改时间</option>
                                <option value="track">跟进时间</option>
                                <option value="staff_id">员工ID</option>
                                <option value="business">所在行业</option>
                                <option value="status">客户状态</option>
                                <option value="source">客户来源</option>
                            </select>
                        </div>
                        <div class="input-group-append">
                            <select class="form-control" name="oder">
                                <option value="desc" selected="">倒序</option>
                                <option value="asc">正序</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-block btn-warning"> 导 出 数 据 </button>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@push('foot_mor')

@endpush