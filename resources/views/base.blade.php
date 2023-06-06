{{--
* @Copyright (C) 2022 : Chongqing Enzu Technology Co., LTD(cqseo.net)
* @LICENSE : http://www.apache.org/licenses/LICENSE-2.0
* @[KwokCMS] Ver 1.0 (C) 2022: Mr.Kwok
* @FilePath: /resources/views/base.blade.php
* @Created Time: 2022-04-16 11:32:36
* @Last Edit Time: 2023-06-06 21:01:51
* @Description: 后台管理基础模板
--}}
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta content="IE=Edge,chrome=1" http-equiv="X-UA-Compatible" />
    <title>【{{$settings['app_name']}}】{{env('APP_NAME')}}</title>
    <link href="{{ asset('fontawesome/css/fontawesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminlte/adminlte.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('icheck-bootstrap/icheck-bootstrap.min.css')}}">
    @stack('head_mor')
</head>

<body @class(['hold-transition', 'sidebar-mini' ,'sidebar-collapse'=> $sidebar??false])>
    <div class="wrapper">
        <!-- 导航区域 -->
        <nav class="main-header navbar navbar-expand navbar-dark">
            <!-- 左顶导航链接 -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button" title="收起/展开右侧菜单栏"><i class="fas fa-bars"></i></a>
                </li>
                @if($user->isAdmin())
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('admin.flush') }}" title="清空所有的缓存数据(包括查看数与投票等)" class="nav-link"><i class="far fa-trash-alt"></i> 清理缓存</a>
                </li>
                @if (file_exists(base_path().DIRECTORY_SEPARATOR.'bootstrap'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'config.php'))
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('admin.clearcache') }}" title="清理缓存的加载项(配置文件、模板、路由等)" class="nav-link"><i class="far fa-trash"></i> 清理预加载</a>
                </li>
                @else
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('admin.cached') }}" title="将模板编译成缓存，合并多个配置项与路由为缓存" class="nav-link"><i class="far fa-spinner"></i> 优化预加载</a>
                </li>
                @endif
                @endif
            </ul>
            <!-- 右上 导航 链接 -->
            <ul class="navbar-nav ml-auto">

                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->
        <!-- 左侧边栏 -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- 商标 Logo -->
            <a class="brand-link" href="/">
                <img class="brand-image img-circle elevation-3" src="{{ asset('logo.svg') }}" style="opacity: .8">
                <span class="brand-text font-weight-light">{{$settings['app_name']}}</span>
            </a>
            <!-- 侧边栏 -->
            <div class="sidebar">
                <!-- 当前登陆用户 -->
                <div class="user-panel d-flex mt-3 mb-3 pb-3">
                    <div class="image">
                        <img alt="用户头像" class="img-circle elevation-2" src="{{$user->avatar_url}}">
                    </div>
                    <div class="info">
                        <a class="mr-2" href="{{ $user['url'] }}">{{ $user['username'] }}</a>
                        <form action="{{ route('logout') }}" class="float-sm-right" method="POST"> @csrf
                            <button type="submit" class="btn btn-xs btn-secondary">退出</button>
                        </form>
                    </div>
                </div>

                <!-- 侧边栏菜单 -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-accordion="false" data-widget="treeview" role="menu">
                        <li class="nav-item menu-open">
                            <a class="nav-link" href="#">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>常用功能 <i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <x-treeview :name="'我的工作台'" :active="$active=='index'" :fa="'fas fa-chart-line'" :link="'/'" />
                                <x-treeview :name="'待办事项'" :active="$active=='todolist'" :fa="'fas fa-check-square'" :link="route('todolist.index')" />

                                @if($user->isAdmin())
                                <x-treeview :name="'标签管理'" :active="$active=='tag.index'" :fa="'fas fa-tags'" :link="route('tags.index')" />
                                @endif
                            </ul>
                        </li>

                        <li @class(['nav-item','menu-open'=>Str::startsWith($active, 'company.')])>
                            <a @class(['nav-link','active'=>Str::startsWith($active, 'company.')]) href="#">
                                <i class="nav-icon fas fa-city"></i>
                                <p>客户管理 <i class="fas fa-angle-left right"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <x-treeview :name="'客户列表'" :active="$active=='company.company'" :fa="'fas fa-users'" :link="route('companies.index')" />
                                <x-treeview :name="'新增客户'" :active="$active=='company.create'" :fa="'fas fa-user-plus'" :link="route('companies.create')" />
                                <x-treeview :name="'行业分类'" :active="Str::contains($active, 'business')" :fa="'fas fa-project-diagram'" :link="route('business.index')" />
                            </ul>
                        </li>


                        @if($user->isAdmin())
                        <li @class(['nav-item','menu-open'=>Str::startsWith($active, 'team.')])>
                            <a @class(['nav-link','active'=>Str::startsWith($active, 'team.')]) href="#">
                                <i class="nav-icon far fa-building"></i>
                                <p>公司架构 <i class="fas fa-angle-left right"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <x-treeview :name="'职位管理'" :active="Str::contains($active, 'team.job')" :fa="'fas fa-users-cog'" :link="route('admin.job.index')" />
                                <x-treeview :name="'员工管理'" :active="$active=='team.staff'" :fa="'fas fa-user-tie'" :link="route('admin.staff.index')" />
                                <x-treeview :name="'新增员工'" :active="$active=='team.staff_create'" :fa="'fas fa-user-check'" :link="route('admin.staff.create')" />
                            </ul>
                        </li>
                        <li @class(['nav-item','menu-open'=>Str::startsWith($active, 'system.')])>
                            <a @class(['nav-link','active'=>Str::startsWith($active, 'system.')]) href="#">
                                <i class="nav-icon far fa-cogs"></i>
                                <p>系统设置 <i class="fas fa-angle-left right"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <x-treeview :name="'全局配置'" :active="$active=='system.settings'" :fa="'fas fa-cog'" :link="route('admin.settings')" />
                                <x-treeview :name="'自定义字段'" :active="$active=='system.customkeys'" :fa="'fas fa-sliders-h'" :link="route('admin.customkeys')" />
                                <x-treeview :name="'数据维护'" :active="$active=='system.database'" :fa="'fas fa-database'" :link="route('admin.database')" />
                                <x-treeview :name="'国家地区'" :active="$active=='system.country'" :fa="'fas fa-globe-asia'" :link="route('admin.country.index')" />

                            </ul>
                        </li>
                        <li @class(['nav-item','menu-open'=>Str::startsWith($active, 'tool.')])>
                            <a @class(['nav-link','active'=>Str::startsWith($active, 'tool.')]) href="#">
                                <i class="nav-icon far fa-tools"></i>
                                <p>我的工具 <i class="fas fa-angle-left right"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <x-treeview :name="'数据导出'" :active="$active==Str::startsWith($active, 'tool.export')" :fa="'fas fa-tasks'" :link="route('admin.export')" />
                                <x-treeview :name="'附件管理'" :active="$active=='tool.attachments'" :fa="'fas fa-paperclip'" :link="route('admin.attachment')" />
                            </ul>
                        </li>
                        <li @class(['nav-item','menu-open'=>Str::startsWith($active, 'log.')])>
                            <a @class(['nav-link','active'=>Str::startsWith($active, 'log.')]) href="#">
                                <i class="nav-icon far fa-spider"></i>
                                <p>日志报告 <i class="fas fa-angle-left right"></i></p>
                            </a>
                            <ul class="nav nav-treeview">
                                <x-treeview :name="'后台访问'" :active="$active=='log.logs'" :fa="'fas fa-keyboard'" :link="route('admin.logs')" />
                                <x-treeview :name="'登陆日志'" :active="$active=='log.loginlogs'" :fa="'fas fa-terminal'" :link="route('admin.login.logs')" />
                            </ul>
                        </li>
                        @endif
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>
        <!-- 页面内容区域 -->
        <div class="content-wrapper">@yield('content')</div>
        <!-- Main Footer -->
        <footer class="main-footer" id="footer">
            <strong>Copyright &copy; {{date("Y")}} {{$settings['app_name']}}</strong> All rights reserved.
            <div class="d-none d-sm-inline-block float-right">
                <span>本次请求耗时:{{round(microtime(true) - LARAVEL_START,3)}}秒,内存使用:{{round(memory_get_peak_usage(true)/1024/1024, 2)}}MB,
                    Powered by <a href="https://www.cqseo.net" target="_blank">CQSEO.NET</a> v{{ Illuminate\Foundation\Application::VERSION }} Licensed
            </div>
        </footer>
    </div>
    <span class="btn btn-dark back-to-top gobacktop d-none" role="button"><i class="fas fa-chevron-up"></i></span>
    <!-- jQuery -->
    <script src="{{ asset('jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap -->
    <script src="{{ asset('bootstrap/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE -->
    <script src="{{ asset('adminlte/adminlte.min.js') }}"></script>
    @stack('foot_mor')
    <script>
        $(function(){
            console.log('Powered by CQSEO.NET v{{ Illuminate\Foundation\Application::VERSION }} Licensed');
            /* 弹出小提示(hover) */
            $('[data-toggle="tooltip"]').tooltip();
            /* 弹出小提示(点击) */
            $('[data-toggle="popover"]').popover();
            /*点击全选*/
            var items = $('input[name="items[]"]');
            $("#select-all").on("click", function () {
                items.prop("checked", this.checked);
            });
             /*检测是否已全选*/             
             items.on("click", function () {
                $("#select-all").prop("checked", (items.length === items.filter(":checked").length) ? true : false);
            });
        /* 回到顶部按钮 */
        const BACKTOTOP=$('.gobacktop');
        $(window).scroll(function() {
            $(this).scrollTop()>150?BACKTOTOP.removeClass("d-none").fadeIn():BACKTOTOP.fadeOut();
        });
        BACKTOTOP.click(function(){$('html,body').animate({scrollTop:0},500)});   
        });
        /* 弹出小提示(toast) */
        function toast(color,message,title='提示'){$(document).Toasts('create',{class:color,title:title,body:message,autohide:true,delay:5000})}
    </script>
</body>

</html>