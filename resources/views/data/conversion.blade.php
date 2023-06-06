@extends('base')
@section('content')
<!-- 内容头 -->
@include('blocks.content-header',['title' => '数据转换','note'=>'数据处理','fa'=>'fas fa-calendar-check'])
<section class="content">



    <a href="?page={{$nextpage}}">处理下一页</a>


</section>
@endsection

@push('foot_mor')


@endpush