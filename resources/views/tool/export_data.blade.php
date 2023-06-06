@extends('base')
{{--
* @Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
* @LICENSE : http://www.apache.org/licenses/LICENSE-2.0
* @[KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
* @FilePath: /resources/views/tool/export_data.blade.php
* @Created Time: 2022-04-16 11:32:36
* @Last Edit Time: 2023-06-03 18:47:04
* @Description: 数据导出
--}}
@push('head_mor')

@endpush
@section('content')
@include('blocks.content-header',['title' => '数据导出','note'=>'您可以对本系统里的客户按条件进行导出操作','fa'=>'fas fa-clipboard-list'])
<section class="content">
    <div class="container-fluid">
        @if($companies->isEmpty())
        <div class="alert alert-info alert-dismissible">
            <h5><i class="icon fas fa-info"></i> 未导出!</h5>
            根据您选择的条件，未找到所对应的数据，请重试！
        </div>
        @else
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    @foreach ($fields['companies'] as $item => $value)
                    @if(!empty($companies->first()[$item]))
                    <th class="{{$item}}">{{$value}}</th>
                    @endif
                    @endforeach
                    @if(!empty($has_followups))
                    <th class="followups">跟进记录</th>
                    @endif
                    @if(!empty($has_appointments))
                    <th class="appointments">预约记录</th>
                    @endif
                    @if(!empty($has_contacts))
                    <th class="contacts">联系人信息</th>
                    @endif
                </tr>
            </thead>
            <tbody class="text-gray">
                @foreach ($companies as $company)
                @foreach ($fields['companies'] as $item => $value)
                @if(!empty($company->$item))
                <td class="{{$item}}">
                    @switch($item)
                    @case('is_vip')
                    {{$company->is_vip?'是':'否'}}
                    @break
                    @case('country')
                    {{$countries[$company->country]['name']??'未知'}}
                    @break
                    @case('state')
                    {{$countries[$company->country]['state'][$company->state]??'未知'}}
                    @break
                    @case('business')
                    {{$businesses[$company->business]['name']??'未知'}}
                    @break
                    @case('attribute')
                    {{$customkey['customer_attribute'][$company->attribute]??'未知'}}
                    @break
                    @case('data_source')
                    {{$customkey['customer_source'][$company->data_source]??'未知'}}
                    @break
                    @default
                    {{$company->$item}}
                    @endswitch
                </td>
                @endif
                @endforeach
                @if(!empty($has_followups))
                <td class="followups">
                    <ol>
                        @foreach ($company->followups as $followup)
                        <li>{{$followup->message}}</li>
                        @endforeach
                    </ol>
                </td>
                @endif
                @if(!empty($has_appointments))
                <td class="appointments">
                    <ol>
                        @foreach($company->appointments as $appointment)
                        <li>{{$appointment->message}}</li>
                        @endforeach
                    </ol>
                </td>
                @endif
                @if(!empty($has_contacts))
                <th class="contacts">
                    @foreach($company->contacts as $person)
                    @if(!empty($person->status==0&&count($person->relation)>0))
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{$person->last_name.$person->name}} - {{$person->job}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($person->relation as $cts)
                            <tr>
                                <td>{{ $cts->contact_type}}:{{ $cts->contact}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                    @endforeach
                </th>
                @endif
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</section>
@endsection
@push('foot_mor')

@endpush