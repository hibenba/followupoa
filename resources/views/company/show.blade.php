@extends('base')
{{--
* @Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
* @LICENSE : http://www.apache.org/licenses/LICENSE-2.0
* @[KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
* @FilePath: /resources/views/company/show.blade.php
* @Created Time: 2022-04-16 11:32:36
* @Last Edit Time: 2023-05-21 17:40:54
* @Description: 客户详情查看
--}}
@push('head_mor')
<link rel="stylesheet" href="{{ asset('daterangepicker/daterangepicker.css') }}">
<style type="text/css">


</style>
@endpush
@section('content')
@include('blocks.content-header',['title' => $name,'note'=>'NO.'.$id,'fa'=>'fas fa-street-view','newitem'=>'返回客户列表','link'=>route('companies.index',['id'=>$id]),'target'=>false])
<section class="content">
    <div class="container-fluid">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">客户基本信息</h3>
                <div class="card-tools">
                    <a href="{{ route('companies.edit',['company'=>$id]) }}"><i class="far fa-edit"></i> 编辑客户信息</a>
                </div>
            </div>
            <div class="card-body">
                <div class="position-relative">
                    @if($is_vip==1)
                    <div class="ribbon-wrapper">
                        <div class="ribbon bg-danger">VIP</div>
                    </div>
                    @endif
                    <table class="table table-bordered table-striped ">
                        <tbody>
                            <tr>
                                <td>公司网址：<a href="{{$url}}" target="_blank" rel="noopener noreferrer">{{$url}}</a></td>
                                <td>客户类型：{{$customkey['customer_attribute'][$attribute]??'-'}}</td>
                                <td>所在国家：{{$country['name']}} @if(!empty($country['state'][$state])) &bull; {{$country['state'][$state]}}@endif</td>
                                <td>信用代码: {{$credit_id}} </td>
                            </tr>
                            <tr>
                                <td colspan="2">产品特性：@foreach ($multiple_feature as $item) <span class="badge bg-{{$colors[$loop->index]??'secondary'}}">{{$item}}</span> @endforeach</td>
                                <td>{{$status_text}}</td>
                                <td>数据来源：{{$data_source_text}}</td>
                            </tr>
                            @if(!empty($address)&&!empty($telephone))
                            <tr>
                                <td colspan="2">所属行业：{{$business_text??'未知'}}</td>
                                <td colspan="2"><i class="fas fa-map-marked-alt ml-2"></i> {{$address}} <i class="fas fa-tty ml-2"></i> {{$telephone}}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            @if($status==1&&!empty($invalid))
            <div class="card-footer text-danger font-weight-bolder">
                无效原因：{{$invalid}}
            </div>
            @endif
        </div>

        <div class="card card-success" id="contacts">
            <div class="card-header">
                <h3 class="card-title">联系人信息</h3>
            </div>
            <div class="card-body">
                @php
                $status_arr =['在职','离职','联系不上'];
                $owner_arr =['否','是','未知'];
                @endphp
                @foreach($contacts as $person)
                <div class="card card-{{$colors[$loop->index]??'secondary'}} card-outline position-relative">
                    @if(!empty($person->is_main)&&!$person->trashed())
                    <div class="ribbon-wrapper ribbon-lg">
                        <div class="ribbon bg-{{$colors[$loop->index]??'secondary'}}">重要联系人</div>
                    </div>
                    @endif
                    @if($person->trashed())
                    <div class="ribbon-wrapper ribbon-lg">
                        <div class="ribbon bg-danger">已删除</div>
                    </div>
                    @endif
                    <div class="card-header">
                        <h4 class="card-title w-100 h6">{{$person->lastname.' '.$person->name}}</h4>
                    </div>
                    <div @class(['card-body', 'bg-pink'=> $person->trashed()])>
                        <table @class(['table',' table-bordered','bg-dark'=> $person->status]) >
                            <tbody>
                                <tr>
                                    <td>状态：{{$status_arr[$person->status]??'异常'}}</td>
                                    <td>职位：{{empty($person->job)?'-':$person->job}}</td>
                                    <td>创建者：{{$person->staff_name}}</td>
                                    <td>更新时间：{{$person->updated_at}}</td>
                                    <td>备注：{{empty($person->description)?'-':$person->description}}</td>
                                </tr>
                                @foreach($person->relation as $cts)
                                <tr @class(['bg-gray'=> $cts->status])>
                                    <td><span class="btn btn-info mr-2"><i class="far fa-id-badge"></i> {{$cts->contact_type}}</span> {{Str::of($cts->contact)->mask('*', 5,-4)}}</td>
                                    <td><span class="btn bg-gradient-indigo btn-sm mr-2">拥有者</span>{{$owner_arr[$cts->owner]??'异常'}}</td>
                                    <td><span class="btn bg-gradient-gray btn-xs mr-2">本地联系</span>{{empty($cts->loacl_contact)?'-':$cts->loacl_contact}}</td>
                                    <td>{{empty($cts->description)?'-':$cts->description}}</td>
                                    @if($cts->status)
                                    <td class="text-center" data-toggle="tooltip" title="无效原因 :{{empty($cts->invalid_reasons)?'未填写':$cts->invalid_reasons}}"><span class="btn bg-gradient-danger">联系方式无效</span></td>
                                    @else
                                    <td class="text-center"><span class="btn bg-gradient-success">可正常联系</span></td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <a href="{{route('contact.edit',['contact'=>$person->id])}}" class="btn btn-sm bg-blue "><i class="fas fa-user-edit"></i> 编辑联系人</a>
                        @if($person->trashed())
                        <a href="javascript:contact_recover({{$person->id}})" class="btn btn-sm btn-success ml-3"><i class="fas fa-redo"></i> 恢复联系人</a>
                        <a href="javascript:contact_delete({{$person->id}})" class="btn btn-sm btn-warning mx-3"><i class="fas fa-user-times"></i> 彻底删除</a>
                        @else
                        <a href="javascript:contact_delete({{$person->id}})" class="btn btn-sm btn-warning mx-3"><i class="fas fa-user-times"></i> 删除联系人</a>
                        @endif

                        @if(empty($person->is_main))
                        <a href="javascript:set_main({{$person->id}},1)" class="btn btn-sm btn-danger"><i class="fas fa-user-shield"></i> 设为重要联系人</a>
                        @else
                        <a href="javascript:set_main({{$person->id}},0)" class="btn btn-sm bg-gradient-olive"><i class="fas fa-user-shield"></i> 设为普通联系人</a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            <div class="card-footer clearfix">
                <a href="{{route('contact.create',['id'=>$id])}}" class="btn btn-info float-right"><i class="fas fa-plus mr-2"></i> 增加联系人</a>
            </div>
        </div>

        <div class="card card-indigo">
            <div class="card-header">
                <h3 class="card-title">详细信息</h3>
            </div>

            <div class="card-body">
                @if(!empty($introduction))
                <div class="card-header">
                    <h5>公司介绍</h5>
                </div>
                <div class="card-body">
                    {!!$introduction!!}
                </div>
                @endif
                @if(!empty($description))
                <div class="card-header">
                    <h5>备注信息</h5>
                </div>
                <div class="card-body">
                    {!!$description!!}
                </div>
                @endif
            </div>
        </div>

        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">其它信息</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box shadow">
                            <span class="info-box-icon bg-success"><i class="fas fa-user-plus"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text h6">创建者</span>
                                <span class="info-box-content">{{$staff_name??$username}}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box shadow" data-toggle="tooltip" title="{{now()->parse($created_at)->toDateTimeString()}}">
                            <span class="info-box-icon bg-info"><i class="fas fa-clock"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">创建时间</span>
                                <span class="info-box-content">{{now()->parse($created_at)->diffForHumans()}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box shadow" data-toggle="tooltip" title="{{now()->parse($updated_at)->toDateTimeString()}}">
                            <span class="info-box-icon bg-warning"><i class="far fa-clock"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">更新时间</span>
                                <span @class(['info-box-content','font-weight-bold'=> $updated_at!=$created_at])>{{now()->parse($updated_at)->diffForHumans()}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box shadow" data-toggle="tooltip" title="{{empty($track_at)?'-':now()->parse($track_at)->toDateTimeString()}}">
                            <span class="info-box-icon bg-danger"><i class="fas fa-business-time"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">跟进时间</span>
                                <span class="info-box-content">{{empty($track_at)?'-':now()->parse($track_at)->diffForHumans()}}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box shadow">
                            <span class="info-box-icon bg-fuchsia"><i class="fas fa-user-check"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">客户归属</span>
                                <span class="info-box-content">{{$username}}(工号:{{$staff_id}})</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box shadow">
                            <span class="info-box-icon bg-blue"><i class="fas fa-map-marker-alt"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">操作者IP</span>
                                <span class="info-box-content">{{$ip}}</span>
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

<script>
    /* 设置为重要联系人 */
    function set_main(id,ismain){
        const data = {_token: '{{csrf_token()}}',id,ismain};
        const url = '{{route('contact.update',['contact'=>0,'type'=>'setmain'])}}';        
        $.ajax({
            url,type:'PUT',data,
            success: function (result){return result.status == 'success'?location.replace(location.href):alert('重要联系人设置失败，原因：' + result.msg)}
        });
    }
    /* 删除联系人 */
    function contact_delete(id){
        if(confirm('请确认是否真的要【删除】本联系人？')){
            const data = {_token: '{{csrf_token()}}',id};
            const url = '{{route('contact.destroy',['contact'=>0])}}';        
            $.ajax({
                url,type:'DELETE',data,
                success: function (result){return result.status == 'success'?location.replace(location.href):alert('联系人删除失败，原因：' + result.msg)}
            });
        }
    }
    /* 恢复联系人 */
    function contact_recover(id){
        const data = {_token: '{{csrf_token()}}',id};
        const url = '{{route('contact.update',['contact'=>0,'type'=>'recover'])}}';        
        $.ajax({
            url,type:'PUT',data,
            success: function (result){return result.status == 'success'?location.replace(location.href):alert('联系人恢复失败，原因：' + result.msg)}
        });
    }
    
</script>
@endpush