@extends('base')
{{--
* @Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
* @LICENSE : http://www.apache.org/licenses/LICENSE-2.0
* @[KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
* @FilePath: /resources/views/system/settings.blade.php
* @Created Time: 2022-04-16 11:32:36
* @Last Edit Time: 2023-04-24 10:57:38
* @Description: 系统设置
--}}
@push('head_mor')
<link rel="stylesheet" href="{{ asset('daterangepicker/daterangepicker.css') }}">
<style type="text/css">


</style>
@endpush
@section('content')
@include('blocks.content-header',['title' => '系统设置','note'=>'您通过本页面可以对站点基本信息进行参数设定。','fa'=>'fas fa-cogs'])
<section class="content">
    <div class="container-fluid">
        <form method="post" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data"> @csrf @method('PUT')

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="far fa-tools"></i> 基本配置</h3>
                </div>
                <div class="card-body">
                    <x-input_inline :title="'系统名称:'" :name="'app_name'" :value="$app_name??''" :size="23" :required="1" :placeholder="'例如:重庆恩祖科技有限公司'" :note="'设置系统的名字，将会显示到导航、页面底部等位置。'" />
                    <div class="form-inline mb-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">分页显示:</span>
                            </div>
                            <input name="perpage" class="form-control" type="number" placeholder="设置每分显示多少条数据" value="{{old('perpage',$perpage??'')}}">
                            <div class="input-group-append">
                                <span class="input-group-text">条</span>
                            </div>
                        </div>
                        <span class="text-muted ml-3">当出现分页时，每页显示数量。</span>
                    </div>

                    <div class="form-inline mb-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">验证码:</span>
                            </div>
                            <div class="icheck-cyan mx-3">
                                <input type="radio" id="captcha1" name="captcha" value="1" @checked(old('captcha',$captcha)==1)>
                                <label for="captcha1">开启</label>
                            </div>
                            <div class="icheck-cyan">
                                <input type="radio" id="captcha0" name="captcha" value="0" @checked(old('captcha',$captcha)==0)>
                                <label for="captcha0">关闭</label>
                            </div>
                        </div>
                        <span class="text-muted ml-3">开启登陆时输入验证码功能。</span>
                    </div>

                    <div class="form-inline mb-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">联系信息:</span>
                            </div>
                            <div class="icheck-info mx-3">
                                <input type="radio" id="hideinfo1" name="hideinfo" value="1" @checked(old('hideinfo',$hideinfo)==1)>
                                <label for="hideinfo1">隐藏</label>
                            </div>
                            <div class="icheck-info">
                                <input type="radio" id="hideinfo0" name="hideinfo" value="0" @checked(old('hideinfo',$hideinfo)==0)>
                                <label for="hideinfo0">显示</label>
                            </div>
                        </div>
                        <span class="text-muted ml-3">将在客户列表隐藏客户部分信息(光标移上去才显示)。</span>
                    </div>

                    <div class="form-inline mb-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">跟进排序:</span>
                            </div>
                            <div class="icheck-indigo mx-3">
                                <input type="radio" id="followup_asc" name="followup_sort" value="asc" @checked(old('followup_sort',$followup_sort)=='asc' )>
                                <label for="followup_asc">顺序(最早)</label>
                            </div>
                            <div class="icheck-indigo">
                                <input type="radio" id="followup_desc" name="followup_sort" value="desc" @checked(old('followup_sort',$followup_sort)=='desc' )>
                                <label for="followup_desc">倒序(最新)</label>
                            </div>
                        </div>
                        <span class="text-muted ml-3">设置跟进在客户列表里的排序方式。</span>
                    </div>

                    <div class="form-inline mb-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">跟进显示:</span>
                            </div>
                            <input name="followup" class="form-control" size="2" type="number" placeholder="设置显示多少条跟进数据" value="{{old('followup',$followup??25)}}">
                            <div class="input-group-append">
                                <span class="input-group-text">条</span>
                            </div>
                        </div>
                        <span class="text-muted ml-3">设置跟进在客户列表里最多显示的数量。</span>
                    </div>




                    <div class="form-inline mb-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">预约显示:</span>
                            </div>
                            <input name="appointment" class="form-control" type="number" placeholder="设置显示多少条预约数据" value="{{old('appointment',$appointment??10)}}">
                            <div class="input-group-append">
                                <span class="input-group-text">条</span>
                            </div>
                        </div>
                        <span class="text-muted ml-3">设置预约在客户列表里最多显示的数量。</span>
                    </div>


                    <div class="form-inline mb-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">简介限制:</span>
                            </div>
                            <input name="introduction_limit" class="form-control" size="2" type="number" placeholder="设置简介字数限制" value="{{old('introduction_limit',$introduction_limit??500)}}">
                            <div class="input-group-append">
                                <span class="input-group-text">字</span>
                            </div>
                        </div>
                        <span class="text-muted ml-3">设置客户列表公司简介显示字数。</span>
                    </div>

                    <div class="form-inline my-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">登陆提醒:</span>
                            </div>
                            <div class="icheck-cyan mx-3">
                                <input type="radio" id="is_login_tips1" name="is_login_tips" value="1" @checked(old('is_login_tips',$is_login_tips)==1)>
                                <label for="is_login_tips1">开启</label>
                            </div>
                            <div class="icheck-cyan">
                                <input type="radio" id="is_login_tips0" name="is_login_tips" value="0" @checked(old('is_login_tips',$is_login_tips)==0)>
                                <label for="is_login_tips0">关闭</label>
                            </div>
                        </div>
                        <span class="text-muted ml-3">登陆时显示下面的登陆提醒内容。</span>
                    </div>
                    <div @class(['form-group','d-none'=> old('is_login_tips',$is_login_tips)==0]) id="login_rules">
                        <label class="h6">登陆提醒:</label>
                        <span class="text-muted mb-3 mx-3">(员工登陆时弹出的提示信息。)</span>
                        <textarea name="login_rules" class="form-control tinymce" rows="6" placeholder="请输入用户注册协议">{{old('login_rules',$login_rules??'')}}</textarea>
                    </div>

                </div>
            </div>

            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title"><i class="nav-icon fas fa-paperclip"></i> 附件配置</h3>
                </div>
                <div class="card-body">
                    <div class="form-inline mb-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">附件显示:</span>
                            </div>
                            <input name="attachment" class="form-control" type="number" size="10" placeholder="设置显示多少条附件数据" value="{{old('attachment',$attachment??'')}}">
                            <div class="input-group-append">
                                <span class="input-group-text">条</span>
                            </div>
                        </div>
                        <span class="text-muted ml-3">设置附件在客户列表里最多显示的数量。</span>
                    </div>

                    <div class="form-inline mb-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">上传限制:</span>
                            </div>
                            <input name="limit_upload_size" class="form-control" type="number" size="10" placeholder="上传文件大小限制" value="{{old('limit_upload_size',$limit_upload_size??'')}}">
                            <div class="input-group-append">
                                <span class="input-group-text">Kb</span>
                            </div>
                        </div>
                        <span class="text-muted ml-3">设置附件在客户列表里最多显示的数量。</span>
                    </div>
                    <div class="form-inline mb-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">可上传的扩展名:</span>
                            </div>
                            <input name="upload_file_types" class="form-control" type="text" size="80" placeholder="请输入限制的文件类型，如:.jpg,.gif" value="{{old('upload_file_types',$upload_file_types??'*')}}">

                        </div>
                        <span class="text-muted ml-3">请输入允许上传的文件扩展名。</span>
                    </div>
                </div>
                <div class="card-footer text-center"><button type="submit" class="btn btn-block btn-warning"> 提 交 保 存 </button></div>
            </div>
        </form>
    </div>
</section>
@endsection
@push('foot_mor')
<!-- tinymce -->
<script src="{{ asset('tinymce/tinymce.min.js') }}"></script>
<script>
    $(function () {
        tinymce.init({
            selector: 'textarea.tinymce',
            language: 'zh-Hans',
            branding: false,
            promotion: false,
            height: 320,
            plugins: 'autolink autosave link wordcount quickbars', 
            menubar: false,
            toolbar: 'undo redo | bold italic underline strikethrough | fontsize blocks ',
            quickbars_selection_toolbar: 'bold quicklink fontsize forecolor removeformat',
            quickbars_insert_toolbar: false,/* 'image media quicktable'关闭快速插入 */       
            contextmenu: false,
        });
        $(":input[name='is_login_tips']").click(function() {
            $(this).val()==0?$("#login_rules").addClass('d-none'):$("#login_rules").removeClass('d-none');
        });
    });
</script>

@endpush