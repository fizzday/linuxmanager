<?php
    $uri = Request::path();
    if ($uri) {
        $preUri = explode('/', $uri)[0];
    }
?>
        
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>网络服务</title>

    <link href="http://libs.baidu.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet" />
    <link href="http://cdn.bootcss.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">

    <style>
        .fa-btn {
            margin-right: 6px;
        }
        /*.btn-info.active {*/
            /*background: #1A6F88;*/
        /*}*/
        body {
            /*background: url(http://www.baimg.com/uploadfile/2015/0712/BTW_2015071272921.jpg) no-repeat center center fixed;*/
            /*background: url( http://pic2.ooopic.com/11/79/26/99bOOOPIC91.jpg ) no-repeat center center fixed;*/
            background: url( {{ asset('img/bg.jpg') }} ) no-repeat center center fixed;
            opacity: 0.95;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }

        nav .container {
            background: #222;
        }

        .container {
            background: #ffffff;
            border: 3px solid #fff;
            /*boder-color: #fff;*/
            border-radius: 15px;
        }

    </style>
</head>
<body id="app-layout">
    <nav class="navbar navbar-inverse navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->

                <a class="navbar-brand" href="{{ url('/') }}">
                    <i class="fa fa-globe"></i> 网络服务
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- 可视化运维 -->
                <ul class="nav navbar-nav">
                    @if ($preUri=='linux')
                        <li class="active">
                    @else
                        <li>
                            @endif
                            <a href="{{ url('/linux') }}">可视化运维</a>
                        </li>
                </ul>
                <!-- whios查询 -->
                <ul class="nav navbar-nav">
                    @if ($preUri=='whios')
                        <li class="active">
                    @else
                        <li>
                            @endif
                            <a href="{{ url('/whois') }}">网站信息查询</a>
                        </li>
                </ul>
                <!-- Left Side Of Navbar -->
                {{--<ul class="nav navbar-nav">--}}
                    {{--@if ($preUri=='table')--}}
                        {{--<li class="active">--}}
                    {{--@else--}}
                        {{--<li>--}}
                    {{--@endif--}}
                            {{--<a href="{{ url('/table') }}">可视化表操作</a>--}}
                        {{--</li>--}}
                {{--</ul>--}}
                {{--<!-- Left Side Of Navbar -->--}}
                {{--<ul class="nav navbar-nav">--}}
                    {{--@if ($preUri=='home')--}}
                        {{--<li class="active">--}}
                    {{--@else--}}
                        {{--<li>--}}
                            {{--@endif--}}
                            {{--<a href="{{ url('/home') }}">Home</a>--}}
                        {{--</li>--}}
                {{--</ul>--}}

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">Login</a></li>
                        <li><a href="{{ url('/register') }}">Register</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- JavaScripts -->

    <script src="http://libs.baidu.com/jquery/1.9.1/jquery.min.js"></script>
    <script src="http://cdn.bootcss.com/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
    <script src="http://libs.baidu.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
<script>
//$(function () {
//    var img = "http://pic.58pic.com/58pic/12/66/52/70d58PICqeA.jpg";
//    var img2 = "http://www.baimg.com/uploadfile/2015/0712/BTW_2015071272921.jpg";
//    $("html").css({
//
////        "background": "url(http://www.baimg.com/uploadfile/2015/0712/BTW_2015071272921.jpg) no-repeat center center fixed",
//        "background": "url("+img2+") no-repeat center center fixed",
//        "-webkit-background-size": "cover",
//        "-moz-background-size": "cover",
//        "-o-background-size": "cover",
//        "background-size": "cover",
//        "opacity": "0.5"
//    });
//});

</script>
</body>
</html>
