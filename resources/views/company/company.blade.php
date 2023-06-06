@extends('base')
{{--
* @Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
* @LICENSE : http://www.apache.org/licenses/LICENSE-2.0
* @[KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
* @FilePath: /resources/views/company/company.blade.php
* @Created Time: 2022-04-16 11:32:36
* @Last Edit Time: 2023-05-22 19:15:37
* @Description: 客户列表
--}}
@push('head_mor')
<link rel="stylesheet" href="{{ asset('daterangepicker/daterangepicker.css') }}">
<style type="text/css">
    .companyname {
        width: 310px
    }

    .companyname .opt {
        font-size: 90%
    }

    .followups {
        width: 580px;
    }

    td.followups {
        font-size: 85%;
    }

    .appointments {
        width: 460px;
    }

    td.appointments {
        font-size: 85%;
    }
</style>
@if ($settings['hideinfo'])
<style>
    .contacts small,
    .contacts small a,
    .companyname small a,
    .dim a {
        text-shadow: #111 0 0 5px;
        box-shadow: 0 0 10px 4px white;
        text-shadow: 0 0 10px black;
        opacity: 0.8;
        color: rgba(0, 0, 0, 0);
    }

    tr:hover small,
    tr:hover small a,
    tr:hover .dim a {
        text-shadow: none;
        box-shadow: none;
        text-shadow: none;
        opacity: 1;
        color: #000
    }
</style>
@endif

@endpush

@section('content')
@include('blocks.content-header',['title' => '客户列表','note'=>'这里将分页显示当前所拥有的客户列表！','fa'=>'fas fa-tachometer-alt','newitem'=>'增加客户','link'=>route('companies.create'),'target'=>false])
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="card-title text-gray">
                    <div class="tables">
                        @foreach ($tables as $key => $table)
                        @php $checked = $type == $key @endphp
                        <a href="{{route('companies.index',['type'=>$key])}}" @class(['btn','btn-sm'=> !$checked,'text-gray-dark' => !$checked,'text-'.$colors[$loop->index] => $checked,'font-weight-bold' => $checked])>
                            {!!$table['font']!!} {{$table['name']}}
                            <small class="badge bg-{{$colors[$loop->index]}}">{{$table['count']}}</small>
                        </a>
                        @endforeach
                    </div>
                </div>
                <form action="{{ route('companies.index') }}" class="card-tools pt-1" method="get">
                    <div class="input-group">
                        <input type="search" name="keywords" class="form-control float-right" placeholder="搜索跟进与预约">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <table class="table table-striped table-bordered table-hover table-head-fixed">
                    <thead>
                        <tr>
                            <th class="companyname"><a href="?order=create" data-toggle="tooltip" title="切换为新增时间排序方式"><i class="far fa-building"></i> 客户信息</a></th>
                            <th class="followups"><i class="fas fa-ellipsis-h"></i> 跟进情况(显示最新)</th>
                            <th class="appointments"><i class="fas fa-bookmark"></i> 预约下次跟进(显示最新)</th>
                            <th class="contacts"><i class="far fa-comment-dots"></i> 联系方式</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray">
                        @if ($items->isEmpty())
                        <tr>
                            <td colspan="4"> 没有相关数据 </td>
                        </tr>
                        @else
                        @foreach ($items as $item)

                        <!-- 跟进时间提示 -->
                        @if($tips['no_followup'] && empty($item->track_at))
                        <tr class="text-center">{!!$tips['no_followup']!!}</tr>
                        @php $tips['no_followup']=false;@endphp
                        @else
                        <tr class="text-center">
                            @if($tips['week'] && $item->track_at > now()->startOfWeek())
                            {!!$tips['week']!!}
                            @php $tips['week']=false;@endphp
                            @elseif($tips['half_month'] && $item->track_at > now()->subWeeks(2)&&now()->startOfWeek() > $item->track_at)
                            {!!$tips['half_month']!!}
                            @php $tips['half_month']=false;@endphp
                            @elseif($tips['month'] && now()->subWeeks(2) > $item->track_at && $item->track_at > now()->subDays(30))
                            {!!$tips['month']!!}
                            @php $tips['month']=false;@endphp
                            @elseif($tips['month_mor'] && now()->subDays(30) > $item->track_at)
                            {!!$tips['month_mor']!!}
                            @php $tips['month_mor']=false;@endphp
                            @endif
                        </tr>
                        @endif

                        <tr id="customer{{$item->id}}" data-cid="{{$item->id}}">

                            <!-- 客户信息 -->
                            <td class="companyname position-relative">
                                @if($item->is_vip)
                                <div class="ribbon-wrapper">
                                    <div class="ribbon bg-warning">VIP</div>
                                </div>
                                @endif
                                <div class="row">
                                    <div class="col-1">
                                        @if(is_file(public_path(asset('flags/'.($countries[$item->country]['abbr']??0).'.png'))))
                                        <img src="{{asset('flags/'.$countries[$item->country]['abbr'].'.png')}}">
                                        @endif
                                    </div>
                                    <div class="col-11">
                                        <h6 class="p-0 m-0 text-truncate" style="width:300px">
                                            <a class="text-dark" href="{{ route('companies.show',['company'=>$item->id]) }}" target="_blank">{{$item->name}}</a>
                                        </h6>
                                        @if($item->url)
                                        <small class="text-truncate d-block" style="width:300px"><a href="{{$item->url}}" target="_blank">{{$item->url}}</a></small>
                                        @endif
                                    </div>
                                </div>
                                <!-- 可操作项 -->
                                <div class="opt my-2">

                                    <a href="{{ route('companies.edit',['company'=>$item->id]) }}" class="btn btn-outline-info btn-xs m-1"><i class="far fa-edit"></i> 编辑</a>

                                    @if($item->trashed())
                                    <!-- 已删除状态 -->
                                    <a href="{{route('admin.company.recover',['company'=>$item->id])}}" class="btn btn-outline-success btn-xs m-1"><i class="fas fa-redo"></i> 恢复</a>
                                    <form action="{{route('admin.company.forcedelete',['company'=>$item->id])}}" class="d-inline" method="POST"> @csrf @method('delete')
                                        <button type="submit" class="btn btn-outline-danger btn-xs" onclick="return confirm('您将彻底删除本客户，单击“确定”继续。单击“取消”停止。')"><i class="far fa-trash-alt"></i> 彻底删除</button>
                                    </form>
                                    @else
                                    <!-- 未删除状态 -->
                                    <form action="{{route('companies.destroy',['company'=>$item->id])}}" class="d-inline" method="POST"> @csrf @method('delete')
                                        <button type="submit" class="btn btn-outline-danger btn-xs" onclick="return confirm('您将删除本客户，单击“确定”继续。单击“取消”停止。')"><i class="far fa-trash"></i> 删除</button>
                                    </form>
                                    @endif
                                    <!-- 转移业务员 -->
                                    <div class="form-inline my-3">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">客户归属:</span>
                                            </div>
                                            <select name="staff_id" class="custom-select">
                                                <option value="0">请选择接收员工</option>
                                                @foreach($staffarr as $value)
                                                <option value="{{$value->id}}" @selected($item->staff_id==$value->id) @disabled($item->staff_id==$value->id)>{{$value->username}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <!-- 客户状态 -->
                                    <div class="form-inline my-3">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">客户状态:</span>
                                            </div>
                                            <select name="status" class="custom-select">
                                                @foreach($customkey['customer_status'] as $order=>$value)
                                                <option value="{{$order}}" @selected($item->status==$order)>{{$value}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- 无效原因 -->
                                @if($item->status == 1&&$item->invalid_cause )
                                <div class="card card-maroon my-3">
                                    <div class="card-header">
                                        <h4 class="card-title">无效原因:</h4>
                                    </div>
                                    <div class="card-body text-maroon h5">{!!$item->invalid_cause!!}</div>
                                </div>
                                @endif

                                <div class="form-inline my-3">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">所属行业:</span>
                                        </div>
                                        <select name="business" class="custom-select">
                                            @foreach($businesses as $business)
                                            <option value="{{$business->id}}" @selected($item->business==$business->id)>{{$business->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- 国家与地区 -->
                                @if(isset($countries[$item->country]['name']))
                                <hr class="my-3">
                                <span class="h5" data-toggle="tooltip" title="当地时间：{{now()->setTimezone($countries[$item->country]['timezone'])->toDateTimeString()}}">
                                    {{$countries[$item->country]['name']}}
                                    @if(!empty($countries[$item->country]['state'][$item->state])) &bull; {{$countries[$item->country]['state'][$item->state]}}@endif
                                    {{$countries[$item->country]['area_code']}}</span>
                                @endif
                                <hr class="my-3">
                                <!-- 客户标签 -->
                                <div class="tags">
                                    @if($item->tags->isNotEmpty())
                                    @foreach($item->tags as $tag)
                                    <h3><span class="badge bg-{{$tag->color}}">{{$tag->name}}</span>
                                        <a href="javascript:tagremove({{$item->id}},{{$tag->id}});" class="text-danger ml-2"><i class="small fas fa-trash-alt"></i></a>
                                    </h3>
                                    @endforeach
                                    <hr>
                                    @endif
                                    <form action="{{ route('company.tags.add') }}" method="POST"> @csrf
                                        <div class="form-inline">
                                            <div class="input-group">
                                                <input name="tagname" autocomplete="off" class="form-control" type="text" size="30" placeholder="输入标签" value="" />
                                                <input name="id" autocomplete="off" type="hidden" value="{{$item->id}}" />
                                                <div class="input-group-prepend">
                                                    <input type="submit" name="addtagsubmit" value="增加" class="btn btn-info submit">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </td>


                            <td class="followups">
                                <!-- 跟进记录开始 -->
                                <div class="card direct-chat direct-chat-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">跟进记录</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool morfollows" data-cid="{{$item->id}}" data-toggle="tooltip" title="查看所有跟进记录">
                                                <i class="fas fa-comments"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        @if($item->followups->isNotEmpty())
                                        <div class="direct-chat-messages h-auto" id="followup{{$item->id}}">
                                            {{-- 跟进记录二次排序 --}}
                                            @php $followups = $settings['followup_sort']=='asc'?$item->followups->sort():$item->followups;@endphp
                                            @foreach($followups as $followup)
                                            @php $isStaff = $followup->staff_id!=$item->staff_id; @endphp
                                            <div id="followupmsg{{$followup->id}}" data-fid="{{$followup->id}}" @class(['direct-chat-msg', 'right'=> $isStaff])>
                                                <div class="direct-chat-infos clearfix">
                                                    <span @class(['direct-chat-name', 'float-right'=> $isStaff,'float-left'=> !$isStaff])>{{$followup->username}}</span>
                                                    <span @class(['direct-chat-timestamp', 'float-right'=> !$isStaff,'float-left'=> $isStaff]) data-toggle="tooltip" title="{{$followup->created_at}}">{{now()->parse($followup->created_at)->toDateString()}}</span>
                                                </div>
                                                <img class="direct-chat-img" src="{{$followup->staff->avatar_url}}" data-toggle="tooltip" title="{{$followup->staff_name}}(工号：{{$followup->staff_id}})">
                                                <div class="direct-chat-text">{{$followup->message}} <a href="javascript:delete_followup({{$followup->id}})" class="text-danger"><i class="fas fa-trash-alt"></i></a></div>
                                            </div>
                                            @endforeach
                                        </div>
                                        @else
                                        <div class="p-4">没有任何跟进记录~</div>
                                        @endif
                                    </div>
                                    <!-- /.card-body -->
                                    <div class="card-footer">
                                        <form action="{{ route('followup.store') }}" method="POST"> @csrf
                                            <div class="form-inline">
                                                <div class="input-group">
                                                    <input type="text" name="message" size="80" placeholder="请输入跟进信息..." class="form-control">
                                                    <input name="id" autocomplete="off" type="hidden" value="{{$item->id}}" />
                                                    <div class="input-group-prepend">
                                                        <input type="submit" value="发送" class="btn btn-primary submit">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- /.card-footer-->
                                </div>
                                <!-- 跟进记录结束 -->
                            </td>

                            <td class="appointments">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">预约提醒</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool morappointments" data-cid="{{$item->id}}" data-toggle="tooltip" title="查看更多预约提醒">
                                                <i class="fas fa-ellipsis-h"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body p-1" id="appointment{{$item->id}}">
                                        @if($item->appointments->isEmpty())
                                        <div class="p-4">没有任何预约提醒~</div>
                                        @else
                                        <ul class="todo-list" data-widget="todo-list">
                                            @foreach($item->appointments as $appointment)
                                            <li id="appointmentmsg{{$appointment->id}}" @class(['text-danger'=> now()->lt($appointment->track_at), 'font-weight-bold' => now()->lt($appointment->track_at)])>
                                                <div @class(['d-inline', 'ml-2' ,'icheck-success'=> now()->lt($appointment->track_at),'icheck-gray'=> now()->gt($appointment->track_at)]) >
                                                    <input type="checkbox" name="check_todo" value="{{$appointment->id}}" id="appt{{$appointment->id}}" @checked($appointment->status)>
                                                    <label for="appt{{$appointment->id}}"></label>
                                                </div>
                                                <span class="text">({{$appointment->staff_name}}):{{$appointment->message}}</span>
                                                <span @class(['badge','badge-success'=> now()->lt($appointment->track_at),'badge-secondary'=> now()->gt($appointment->track_at)]) title="{{$appointment->track_at}}">
                                                    <i class="far fa-clock"></i>
                                                    <time>{{now()->parse($appointment->track_at)->diffForHumans()}}</time>
                                                </span>
                                                <div class="tools">
                                                    <i class="fas fa-trash-alt" onclick="delete_appointment({{$appointment->id}})"></i>
                                                </div>
                                            </li>
                                            @endforeach
                                        </ul>
                                        @endif
                                    </div>
                                    <!-- /.card-body -->
                                    <div class="card-footer clearfix">
                                        <button type="button" onclick="next_appointment({{$item->id}})" class="btn btn-primary btn-sm float-right"><i class="fas fa-plus"></i> 增加一个提醒</button>
                                    </div>
                                </div>
                                @if(!empty($item->introduction))
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">公司介绍</h3>
                                        <div class="card-tools">
                                            <a href="{{ route('companies.edit',['company'=>$item->id]) }}#information" class="mx-2"><i class="fas fa-pencil-alt"></i></a>
                                        </div>
                                    </div>
                                    <div class="card-body" id="introduction{{$item->id}}">
                                        {!!Str::limit($item->introduction, $settings['introduction_limit'])!!}
                                    </div>
                                </div>
                                @endif

                                @if($item->attachments->isNotEmpty())
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">文件列表</h3>
                                    </div>
                                    <div class="card-body">
                                        <ul class="products-list product-list-in-card files">
                                            @foreach($item->attachments as $file)
                                            <li class="item" id="file-{{$file->id}}">
                                                <div class="product-img h3 pl-2 pt-1">
                                                    <a href="{{route('attachment.downloads',['id'=>$file->id])}}"><i class="fas fa-download" data-toggle="tooltip" title="下载本文件"></i></a>
                                                </div>
                                                <div class="product-info">
                                                    <span class="product-title">
                                                        {{$file->file_name}}
                                                        <a href="javascript:delete_attachment({{$file->id}})" class="text-danger"><i class="fas fa-trash-alt"></i></a>
                                                    </span>
                                                    <span class="product-description">
                                                        {{$file->username}}:{{$file->created_at}}
                                                    </span>
                                                </div>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                @endif
                                <div class="position-relative">
                                    <span class="btn btn-secondary position-absolute">
                                        <i class="fas fa-plus"></i>
                                        <span>上传报价、客户信息等各种文件</span>
                                    </span>
                                    <input type="file" name="fileupload" accept="{{$settings['upload_file_types']}}" data-fid="{{$item->id}}" style="cursor:pointer" class="custom-file-input h-200" />
                                </div>
                            </td>
                            <td class="contacts position-relative" id="accordion{{$item->id}}">
                                @if(!empty($item->contacts))
                                @php $allemail='';@endphp
                                @foreach($item->contacts as $person)
                                @if(!empty($person->status==0&&count($person->relation)>0))
                                <div class="card card-{{$colors[$loop->index]??'secondary'}} card-outline">
                                    @if(!empty($person->is_main))
                                    <div class="ribbon-wrapper">
                                        <div class="ribbon bg-{{$colors[$loop->index]??'secondary'}}">重要</div>
                                    </div>
                                    @endif
                                    <a class="d-block w-100" data-toggle="collapse" href="#collapse{{$person->id}}">
                                        <div class="card-header">
                                            <h4 class="card-title w-100 h6">{{$person->last_name.$person->name}} - {{$person->job}}</h4>
                                        </div>
                                    </a>
                                    <div id="collapse{{$person->id}}" class="collapse" data-parent="#accordion{{$item->id}}">
                                        <div class="card-body">
                                            <ul class="nav nav-pills flex-column">
                                                @foreach($person->relation as $cts)
                                                @switch($cts->contact_type)
                                                @case('email')
                                                <li class="nav-item py-2 text-truncate">
                                                    <small data-toggle="tooltip" title="邮件{{empty($cts->loacl_contact)?'':'('.$cts->loacl_contact.')'}}">
                                                        <span class="btn bg-gradient-primary btn-xs mx-2"><i class="fas fa-envelope"></i></span>
                                                        <a href="mailto:{{$cts->contact}}">{{$cts->contact}}</a>
                                                    </small>
                                                </li>
                                                @php $allemail.=$cts->contact.';';@endphp
                                                @break
                                                @case('wechat')
                                                <li class="nav-item py-2 text-truncate"><small data-toggle="tooltip" title="微信{{empty($cts->loacl_contact)?'':'('.$cts->loacl_contact.')'}}"><span class="btn bg-olive btn-xs mx-2"><i class="fab fa-weixin"></i></span> {{$cts->contact}}</small></li>
                                                @break
                                                @case('whatsapp')
                                                <li class="nav-item py-2 text-truncate">
                                                    <small data-toggle="tooltip" title="whatsapp{{empty($cts->loacl_contact)?'':'('.$cts->loacl_contact.')'}}"><span class="btn bg-gradient-success btn-xs mx-2">
                                                            <i class="fab fa-whatsapp"></i></span>
                                                        <a target="_blank" href="https://api.whatsapp.com/send/?phone={{str_replace(['+00','+0','+','-',' '],'',trim($cts->contact))}}">{{Str::of($cts->contact)->mask('*', 5,-4)}}</a>
                                                    </small>
                                                </li>
                                                @break
                                                @case('phone')
                                                <li class="nav-item py-2 text-truncate"><small data-toggle="tooltip" title="电话{{empty($cts->loacl_contact)?'':'('.$cts->loacl_contact.')'}}"><span class="btn bg-gray btn-xs mx-2"><i class="fas fa-phone"></i></span>
                                                        <a href="tel:{{$cts->contact}}">{{$cts->contact}}</a>
                                                    </small></li>
                                                @break
                                                @case('mobilephone')
                                                <li class="nav-item py-2 text-truncate"><small data-toggle="tooltip" title="手机{{empty($cts->loacl_contact)?'':'('.$cts->loacl_contact.')'}}"><span class="btn bg-dark btn-xs mx-2"><i class="fas fa-mobile-alt"></i></span>
                                                        <a href="tel:{{$cts->contact}}">{{$cts->contact}}</a>
                                                    </small></li>
                                                @break
                                                @case('twitter')
                                                <li class="nav-item py-2 text-truncate"><small data-toggle="tooltip" title="twitter{{empty($cts->loacl_contact)?'':'('.$cts->loacl_contact.')'}}"><span class="btn bg-lightblue btn-xs mx-2"><i class="fab fa-twitter"></i></span> <a href="{{$cts->contact}}" target="_blank">查看Twitter</a></small></li>
                                                @break
                                                @case('facebook')
                                                <li class="nav-item py-2 text-truncate"><small data-toggle="tooltip" title="脸书{{empty($cts->loacl_contact)?'':'('.$cts->loacl_contact.')'}}"><span class="btn btn-primary btn-xs mx-2"><i class="fab fa-facebook"></i></span> <a href="{{$cts->contact}}" target="_blank">查看Facebook</a></small></li>
                                                @break
                                                @case('instagram')
                                                <li class="nav-item py-2 text-truncate"><small data-toggle="tooltip" title="instagram{{empty($cts->loacl_contact)?'':'('.$cts->loacl_contact.')'}}"><span class="btn bg-fuchsia btn-xs mx-2"><i class="fab fa-instagram"></i></span> <a href="{{$cts->contact}}" target="_blank">查看Instagram</a></small></li>
                                                @break
                                                @case('skype')
                                                <li class="nav-item py-2 text-truncate"><small data-toggle="tooltip" title="skype{{empty($cts->loacl_contact)?'':'('.$cts->loacl_contact.')'}}"><span class="btn bg-teal btn-xs mx-2"><i class="fab fa-skype"></i></span> <a href="callto://{{$cts->contact}}/" target="_blank" rel="noopener noreferrer">{{$cts->contact}}</a> </small></li>
                                                @break
                                                @default
                                                <li class="nav-item py-2 text-truncate"><small data-toggle="tooltip" title="其它联系方式{{empty($cts->loacl_contact)?'':'('.$cts->loacl_contact.')'}}:{{$cts->contact_type}}"><span class="btn bg-navy btn-xs mx-2"><i class="far fa-comment-dots"></i></span> {{$cts->contact}}</small></li>
                                                @endswitch
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                                <a href="{{ route('companies.show',['company'=>$item->id]) }}#contacts" class="btn btn-sm btn-info"><i class="fas fa-address-book"></i> 管理</a>
                                @if(!empty($allemail))
                                <a href="mailto:{{$allemail}}" data-toggle="tooltip" title="点击批量发送给下列邮箱：{{$allemail}}" class="mx-2 btn btn-sm btn-success"><i class="fas fa-mail-bulk"></i> 批量发送邮件</a>
                                @endif
                                @else
                                <a href="{{ route('companies.show',['company'=>$item->id]) }}#contacts" class="btn btn-info"><i class="far fa-address-card"></i> 增加联系人</a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            @if ($items->hasPages())
            <div class="card-footer pt-4">
                <div class="card-tools">{{ $items->links('blocks.pagination')}}</div>
            </div>
            @endif
        </div>
    </div>
</section>


<!-- 所有记录(预约与跟进)模态 -->
<div class="modal fade" id="show_items_list">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">详细记录 <i class="fas fa-ellipsis-h"></i> </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
<!-- 输入无效原因 -->
<div class="modal fade" id="input_invalid">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">请输入客户无效原因 <i class="far fa-keyboard"></i> </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('company.invalid') }}" method="POST"> @csrf
                <div class="modal-body">
                    <div class="input-group">
                        <textarea class="form-control" rows="10" placeholder="请详细说明客户无效的原因..." name="message"></textarea>
                        <input type="hidden" name="id" value="0">
                    </div>
                </div>
                <div class="modal-footer"><input type="submit" value=" 提 交 保 存 " class="btn btn-info submit"></div>
            </form>
        </div>
    </div>
</div>
<!-- 预约下次跟进 -->
<div class="modal fade" id="next_appointment">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">创建一个预约提醒 <i class="far fa-lightbulb"></i> </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('appointment.store') }}" method="POST"> @csrf
                <div class="modal-body">
                    <div class="form-inline my-3">
                        <div class="input-group" data-toggle="tooltip" title="请选择需要提醒的时间">
                            <div class="input-group-prepend">
                                <span class="input-group-text">请于</span>
                            </div>
                            <input type="text" name="next_appointment_time" id="reservationtime" value="{{now()->addDays(1)}}" readonly class="form-control">
                            <div class="input-group-append">
                                <span class="input-group-text">提醒我:</span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group">
                        <textarea class="form-control" rows="10" placeholder="请输入预约提醒的内容..." name="message"></textarea>
                        <input type="hidden" name="id" value="0">
                    </div>
                </div>
                <div class="modal-footer"><input type="submit" value=" 提 交 保 存 " class="btn btn-info submit"></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="theappointments">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- 模态框头部 -->
            <div class="modal-header">
                <h4 class="modal-title">您有以下预约需要处理：</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- 模态框主体 -->
            <div class="modal-body"></div>
        </div>
    </div>
</div>

@endsection
@push('foot_mor')
<!-- daterangepicker -->
<script src="{{ asset('moment/moment.min.js') }}"></script>
<script src="{{ asset('daterangepicker/daterangepicker.js') }}"></script>
<script>
    $(function() {
        @if(count($_GET)===0)
        /* 预约提醒弹出(路由有参数时不弹) */
        $.ajax({
            url:'{{route('appointment.tips')}}',type:'GET',
            success: function (result){
                if(result.status == 'success'){
                    var html = '<ol>';
                    $.each(result.data,function(index, value){
                        html += '<li class="my-3"><strong>'+value.company.name+'</strong>';
                        html += value.track_at;
                        html += ':' + value.message;
                        html += '(' + value.staff_name + ')';
                        html += ' <a href="?id='+ value.company_id +'" class="ml-2 btn-sm btn btn-success">立即处理</a></li>';
                    });
                    html += '</ol>';
                    $('#theappointments').modal({show:true}).find('.modal-body').html(html);
                }
            }
        });
        @endif

        /* 文件上传 */        
        $('input[name="fileupload"]').change(function(){
            if($(this).val().length === 0){return}
            var formData = new FormData();
            formData.append("_token", '{{csrf_token()}}');
            formData.append("file", this.files[0]);
            formData.append("id", $(this).data('fid'));
            $.ajax({
                url : '{{route('uploads')}}',
                type : 'POST',
                data : formData,
                cache: false, 
                processData: false,
                contentType: false,
                success : function(result) {
                    if(result.status == 'success'){
                        location.replace(location.href);
                        console.log(result);
                    }else{
                        toast('bg-danger',result.msg,'出现一个错误')
                    }
                }
            });
            $(this).val('');
        });
        /* 所有跟进记录 */
        $('.morfollows').click(function(){
            const id = $(this).data('cid');
            const data = {_token: '{{csrf_token()}}',id};
            $.ajax({
                url:'{{route('followup.show')}}',type:'POST',data,
                success: function (result){
                    if(result.status == 'success'){
                        var html = '';
                        $.each(result.data,function(index, value){
                            html += '<p class="border-bottom pb-2"><time class="font-weight-bold">'+value.created_at+'</time>';
                            if(value.deleted_at){
                                html += '<del data-toggle="tooltip" title="此条跟进记录已被放入回收站">('+value.username+'):'+value.message+'</del>';
                                html += ' <a data-toggle="tooltip" title="恢复此条跟进记录" href="javascript:recycle_followup('+value.id+')" class="text-success mx-2"><i class="fas fa-recycle"></i></a> ';
                            }else{
                                html += '('+value.username+'):'+value.message;
                            }
                            html += ' <a href="javascript:delete_followup('+value.id+')" class="text-danger"><i class="fas fa-trash-alt"></i></a>';
                            html += '</p>';
                            //console.log(value);
                        });
                        $('#show_items_list').modal({show:true}).find('.modal-body').html(html);
                    }else{
                        toast('bg-danger',result.msg,'出现一个错误')
                    }
                }
            });            
        });
        /* 所有跟进记录 */
        $('.morappointments').click(function(){
            const id = $(this).data('cid');
            const data = {_token: '{{csrf_token()}}',id};
            $.ajax({
                url:'{{route('appointment.show')}}',type:'POST',data,
                success: function (result){
                    if(result.status == 'success'){
                        var html = '';
                        $.each(result.data,function(index, value){
                            html += '<p class="border-bottom pb-2"><time class="font-weight-bold">'+value.created_at+'</time>';
                            if(value.deleted_at){
                                html += '<del data-toggle="tooltip" title="此条跟进记录已被放入回收站">('+value.username+'):'+value.message+'</del>';
                                html += ' <a data-toggle="tooltip" title="恢复此条跟进记录" href="javascript:recycle_followup('+value.id+')" class="text-success mx-2"><i class="fas fa-recycle"></i></a> ';
                            }else{
                                html += '('+value.username+'):'+value.message;
                            }
                            html += ' <a href="javascript:delete_appointment('+value.id+')" class="text-danger"><i class="fas fa-trash-alt"></i></a>';
                            html += '</p>';
                            //console.log(value);
                        });
                        $('#show_items_list').modal({show:true}).find('.modal-body').html(html);
                    }else{
                        toast('bg-danger',result.msg,'出现一个错误')
                    }
                }
            });            
        });
        /* 客户状态修改 */
        $('select[name="status"]').change(function(){          
            const id = $(this).parents('tr').data('cid'); 
            if($(this).val()==1){
                $('#input_invalid').modal({show:true}).find('input[name="id"]').val(id);
                return;
            }
            var items = {'status':$(this).val()};
            const url = '{{route('company.update',['type'=>'status'])}}';
            company_update(id,url,items,'客户状态');
        });

        /* 客户行业修改 */
        $('select[name="business"]').change(function(){          
            const id = $(this).parents('tr').data('cid'); 
            var items = {'business':$(this).val()};
            const url = '{{route('company.update',['type'=>'business'])}}';
            company_update(id,url,items,'所属行业');
        });
        

        @if($user->isAdmin())
        /* 客户归属修改 */
        $('select[name="staff_id"]').change(function(){
            const items = {'staff_id' : $(this).val()};
            const url = '{{route('company.update',['type'=>'staff'])}}';
            company_update($(this).parents('tr').data('cid'),url,items,'客户归属');
        });        
        @endif
        /* 预约状态修改 */
        $('input[name="check_todo"]').click(function(){
            const data = {_token: '{{csrf_token()}}',id:$(this).val(),status:$(this).prop("checked")?1:0};
            $.ajax({
            url:'{{route('appointment.status')}}',type:'PUT',data,
            success: function (result){
                return result.status == 'success'? toast('bg-success','预约状态已更新~'):toast('bg-danger',result.msg,'出现了一个错误：')                
            }
        });
        });
        /* 时间选择 */
        $('#reservationtime').daterangepicker({
            "singleDatePicker": true,
            "minYear": {{date("Y")}},
            "minDate": '{{now()}}',
            "maxDate": '{{now()->addYears(1)}}',
            "timePicker": true,
            "timePicker24Hour": true,
            locale: {
              "format": "YYYY-MM-DD HH:mm:ss",
              "applyLabel": "确定",
              "cancelLabel": "取消",
              "daysOfWeek": ["周日","一","二","三","四","五","六"],
              "monthNames": ["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],
            },
        });
    });
    /* 通过Ajax更新客户信息 */
    function company_update(id,url,items,tips){
        var data = {_token: '{{csrf_token()}}',id};         
        $.each(items,function(key, value){data[key] = value});
        $.ajax({
            url,type:'PUT',data,
            success: function (result){return result.status == 'success'?toast('bg-success',tips+'修改成功~'):alert(tips+'设置失败，原因：' + result.msg)}
        });
    }
    @if($user->isAdmin())
    /* 恢复此条跟进记录 */
    function recycle_followup(id){
        const data = {_token: '{{csrf_token()}}',id};
        $.ajax({
            url:'{{route('followup.recycle')}}',type:'PUT',data,
            success: function (result){
                return result.status == 'success'? location.replace(location.href):toast('bg-danger',result.msg,'恢复过程中出现了一个错误：')                
            }
        });
    }
    @endif
     /* 移出ID与tagid的关联 */
    function tagremove(id,tagid){
        if(confirm('请确认是否真的要【删除】本标签与客户的关联？')){
            const data = {_token: '{{csrf_token()}}',id,tagid};
            $.ajax({
                url:'{{route('company.tags.remove')}}',type:'DELETE',data,
                success: function (result){
                    return result.status == 'success'?$('.tags h3').remove():toast('bg-danger',result.msg,'标签删除不成功')
                }
            });
        }
    }
    /* 删除跟进日志 */
    function delete_followup(id){
        if(confirm('请确认是否真的要【删除】本条跟进日志？')){
            const data = {_token: '{{csrf_token()}}',id};
            $.ajax({
                url:'{{route('followup.destroy')}}',type:'DELETE',data,
                success: function (result){
                    if(result.status == 'success'){
                        $('#followupmsg'+id).remove();
                        $('#show_items_list').modal('hide');
                    }else{
                        toast('bg-danger',result.msg,'删除过程中出现了一个错误：')
                    }
                }
            });
        }
    }
    /* 删除预约记录 */
    function delete_appointment(id){
        if(confirm('请确认是否真的要【删除】本条预约记录？')){
            const data = {_token: '{{csrf_token()}}',id};
            $.ajax({
                url:'{{route('appointment.destroy')}}',type:'DELETE',data,
                success: function (result){
                    if(result.status == 'success'){
                        $('#appointmentmsg'+id).remove();
                        $('#show_items_list').modal('hide');
                    }else{
                        toast('bg-danger',result.msg,'删除过程中出现了一个错误：')
                    }
                }
            });
        }
    }
    /* 删除附件 */
    function delete_attachment(id){
        if(confirm('请确认是否真的要【删除】本文件？')){
            const data = {_token: '{{csrf_token()}}',id};
            $.ajax({
                url:'{{route('attachment.destroy')}}',type:'DELETE',data,
                success: function (result){
                    if(result.status == 'success'){
                        $('#file-'+id).remove();
                    }else{
                        toast('bg-danger',result.msg,'删除过程中出现了一个错误：')
                    }
                }
            });
        }
    }
    
    
    /* 预约提醒 */
    function next_appointment(id){
        $('#next_appointment').modal('show').find('input[name="id"]').val(id);;
    }
</script>
@endpush