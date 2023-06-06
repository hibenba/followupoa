<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta content="IE=Edge,chrome=1" http-equiv="X-UA-Compatible" />
    <title>【{{$settings['app_name']}}】{{env('APP_NAME')}}</title>
    <link href="{{ asset('fontawesome/css/fontawesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminlte/adminlte.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('icheck-bootstrap/icheck-bootstrap.min.css')}}">

    <style type="text/css">
        body {
            background: linear-gradient(315deg, rgba(101, 0, 94, 1) 3%, rgba(60, 132, 206, 1) 38%, rgba(48, 238, 226, 1) 68%, rgba(255, 25, 25, 1) 98%);
            animation: gradient 15s ease infinite;
            background-size: 400% 400%;
            background-attachment: fixed;
        }

        @keyframes gradient {
            0% {
                background-position: 0% 0%;
            }

            50% {
                background-position: 100% 100%;
            }

            100% {
                background-position: 0% 0%;
            }
        }

        .wave {
            background: rgb(255 255 255 / 25%);
            border-radius: 1000% 1000% 0 0;
            position: fixed;
            width: 200%;
            height: 12em;
            animation: wave 10s -3s linear infinite;
            transform: translate3d(0, 0, 0);
            opacity: 0.8;
            bottom: 0;
            left: 0;
            z-index: -1;
        }

        .wave:nth-of-type(2) {
            bottom: -1.25em;
            animation: wave 18s linear reverse infinite;
            opacity: 0.8;
        }

        .wave:nth-of-type(3) {
            bottom: -2.5em;
            animation: wave 20s -1s reverse infinite;
            opacity: 0.9;
        }

        @keyframes wave {
            2% {
                transform: translateX(1);
            }

            25% {
                transform: translateX(-25%);
            }

            50% {
                transform: translateX(-50%);
            }

            75% {
                transform: translateX(-25%);
            }

            100% {
                transform: translateX(1);
            }
        }

        .form {
            background: rgba(255, 255, 255, 0.4);
            margin: 25% auto;
        }

        .login-page {
            height: auto;
        }
    </style>
</head>

<body class="hold-transition login-page pt-5">
    <div class="login-logo text-white font-weight-bolder mt-5 pt-5">{{$settings['app_name']}} &bull; {{env('APP_NAME')}}</div>
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                @if ($errors->any())
                @foreach ($errors->all() as $error)
                <h5 class="login-box-msg text-danger">{{ $error }}</h5>
                @endforeach
                @else
                <h5 class="login-box-msg mt-3">请输入您的登陆信息</h5>
                @endif
            </div>
            <div class="card-body login-card-body">
                <form action="{{ route('login.store') }}" id="login" method="POST">@csrf
                    <div class="input-group mb-3">
                        <input type="text" value="{{old('username')}}" name="username" class="form-control" placeholder="用户名/姓名/邮箱/手机号">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fa fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="请输入密码" name="password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    @if($settings['captcha'])
                    <div class="input-group mb-3">
                        <img src="{{ asset('load.gif') }}" class="cursor-pointer rounded align-bottom" id="validate" title="点击刷新" onerror="this.src='{{ asset('load.gif') }}'">
                        <input type="text" size="10" autocomplete="false" autocorrect="off" autocapitalize="off" class="form-control" placeholder="验证码" name="captcha">
                        <div class="input-group-append">
                            <div class="input-group-text" id="seccode"></div>
                        </div>
                    </div>
                    @endif
                    <div class="py-3">
                        <button type="submit" class="btn btn-info btn-block">登 录 系 统</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="wave"></div>
    <div class="wave"></div>
    <div class="wave"></div>
    <div class="fixed-bottom text-right text-white p-2">
        技术支持：<a class="text-white" href="http://www.cqseo.net" target="_blank">重庆恩祖科技有限公司</a>
    </div>

    <div class="modal fade" style="top:25%" data-backdrop="static" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fas fa-exclamation-triangle text-warning mr-3"></i> 警告信息</h4>
                </div>
                <!-- 模态框主体 -->
                <div class="modal-body">{!!$settings['login_rules']!!}</div>
                <div class="modal-footer">
                    <div class="icheck-primary d-inline">
                        <input type="checkbox" id="checkboxPrimary" name="isShow" value="1">
                        <label for="checkboxPrimary">不再显示</label>
                    </div>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">同意并进入系统</button>
                </div>
            </div>
        </div>

    </div>
    <!-- jQuery -->
    <script src="{{ asset('jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap -->
    <script src="{{ asset('bootstrap/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE -->
    <script src="{{ asset('adminlte/adminlte.min.js') }}"></script>
    <!-- jquery-validation -->
    <script src="{{asset('jquery-validation/jquery.validate.min.js')}}"></script>

    <script>
        $(function () {
            $('#login').validate({
                rules: {
                    username: {required:true,maxlength: 100},
                    password: {required:true},
                    captcha: {required:true},
                },
                messages: {
                    username: {required: "",maxlength: ""},
                    password: {required: ""},
                    captcha: {required: ""}
                },
                errorElement: 'p',
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass){                  
                    $(element).removeClass('is-invalid');               
                }
            });
			if (!getCookie('isShow')) {
				$('#myModal').modal({show:true});
			}		
			$('#myModal').on('hidden.bs.modal', function () {
				if ($("input[name='isShow']").is(':checked')) {
					setCookie('isShow', 1);	/*用户同意后检测是否不再显示警告信息*/
				}
			})
		});
        function setCookie(name, value, time) {
            var Days = time ? time : 365;
            var exp = new Date();
            exp.setTime(exp.getTime() + Days * 24 * 60 * 60 * 1000);
            document.cookie = name + "=" + escape(value) + ";expires=" + exp.toGMTString();
        }
        function getCookie(c_name) {
            if (document.cookie.length > 0) {
                c_start = document.cookie.indexOf(c_name + "=");
                if (c_start != -1) {
                    c_start = c_start + c_name.length + 1;
                    c_end = document.cookie.indexOf(";", c_start);
                    if (c_end == -1) c_end = document.cookie.length;
                    return unescape(document.cookie.substring(c_start, c_end))
                }
            }
            return false
        }
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
        $(":input[name='captcha']").blur(function() {     
           const  captcha = $(this).val();
            if (captcha.length === 4) {
                $.ajax({
                    type: "POST",
                    url: '{{route('captcha')}}',
                    data: {captcha,_token:'{{csrf_token()}}'},
                    success: function(result) {
                        $("#seccode").html((result.status == 'success') ?
                         ' <i class="fas fa-check text-success" title="验证码输入正确，可以提交！"></i>' : 
                         '<i class="fas fa-times text-danger" title=""验证码输入有误，请点击刷新后重新输入！"></i>');
                    }
                });
            } else {
                $("#seccode").html('<i class="fas fa-exclamation text-warning" title="请输入4位的验证码！"></i>');
            }
        });
        function captcha(){$("#validate").attr('src', '{{captcha_src()}}' + Math.random())}
        var isclick = true;
        captcha();
        setInterval(function(){captcha()}, 60000);/* 每60秒更新一次 */
        $("#validate").click(function() {
            if (isclick) {
                isclick = false;
                captcha();
                setTimeout(function() {isclick = true}, 1000);
            }
        });
    });
    </script>
</body>

</html>