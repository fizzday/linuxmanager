<style>
    .nav>li:hover .dropdown-menu {display: block;}
    /*.nav>li:hover {background-color: #00a000}*/
    /*div.dropdown:hover .dropdown-menu {display: block;}*/
</style>

<div class="row clearfix">
    <div class="col-md-12 column" style=" border-radius: 10px; color:#fff ">
        <div class="nav navbar-nav">
            <!-- Authentication Links -->
            <li class="dropdown">
                <a href="{{ URL('linux') }}" class="dropdown-toggle" role="button">
                    执行操作 <span class="caret"></span>
                </a>

                <ul class="dropdown-menu" role="menu">
                    <li><a href="{{ URL('linux/serverCmd/serverCmdTeamList') }}"><i class="fa fa-btn fa-sign-out"></i>团队执行操作列表</a></li>
                </ul>
            </li>
        </div>

        {{--<div class="nav navbar-nav">--}}
            {{--<!-- Authentication Links -->--}}
            {{--<li class="">--}}
                {{--@if (Request::path()=='linux')--}}
                    {{--<a href="{{ URL('linux') }}" class="btn btn-info active">执行操作</a>--}}
                {{--@else--}}
                    {{--<a href="/linux" class="">执行操作</a>--}}
                {{--@endif--}}
            {{--</li>--}}
        {{--</div>--}}
        <div class="nav navbar-nav">
            <!-- Authentication Links -->
            <li class="active">
                @if (Request::path()=='linux/cmd/getList')
                    <a href="{{ URL('linux/cmd/getList') }}" class="btn btn-info active">命令列表</a>
                @else
                    <a href="{{ URL('linux/cmd/getList') }}" class="">命令列表</a>
                @endif
            </li>
        </div>
        <div class="nav navbar-nav">
            <!-- Authentication Links -->
            <li class="">
                @if (Request::path()=='linux/server/getList')
                    <a href="{{ URL('linux/server/getList') }}" class="btn btn-info active">服务器列表</a>
                @else
                    <a href="{{ URL('linux/server/getList') }}" class="">服务器列表</a>
               @endif
            </li>
        </div>
        <div class="nav navbar-nav">
            <!-- Authentication Links -->
            <li class="">
                @if (Request::path()=='linux/sysCmd/getList')
                    <a href="{{ URL('linux/sysCmd/getList') }}" class="btn btn-info active">预设命令</a>
                @else
                    <a href="{{ URL('linux/sysCmd/getList') }}" class="">预设命令</a>
                    {{--<a class="btn btn-success pull-right" data-toggle="modal" onclick="cmdNew()" data-target="#serverModal"><i class="glyphicon glyphicon-plus"></i>&nbsp;New</a>--}}
                @endif
            </li>
        </div>
        <div class="nav navbar-nav pull-right">
            <!-- Authentication Links -->
            <li class="">
                @if (Request::path()=='linux/sysCmd/getList' || Request::path()=='linux/serverCmd/serverCmdTeamList')
                @else
                    <a class="btn btn-success pull-right" data-toggle="modal" onclick="cmdNew()" data-target="#serverModal"><i class="glyphicon glyphicon-plus"></i>&nbsp;New</a>
                @endif
            </li>
        </div>




        {{--@if (Request::path()=='linux')--}}
            {{--<a href="{{ URL('linux') }}" class="btn btn-info active">执行操作</a>--}}
        {{--@else--}}
            {{--<a href="/linux" class="btn btn-info">执行操作</a>--}}
        {{--@endif--}}

        {{--@if (Request::path()=='linux/cmd/getList')--}}
            {{--<a href="{{ URL('linux/cmd/getList') }}" class="btn btn-info active">命令列表</a>--}}
        {{--@else--}}
            {{--<a href="{{ URL('linux/cmd/getList') }}" class="btn btn-info">命令列表</a>--}}
        {{--@endif--}}

        {{--@if (Request::path()=='linux/server/getList')--}}
            {{--<a href="{{ URL('linux/server/getList') }}" class="btn btn-info active">服务器列表</a>--}}
        {{--@else--}}
            {{--<a href="{{ URL('linux/server/getList') }}" class="btn btn-info">服务器列表</a>--}}
        {{--@endif--}}


        {{--@if (Request::path()=='linux/sysCmd/getList')--}}
            {{--<a href="{{ URL('linux/sysCmd/getList') }}" class="btn btn-info active">预设命令</a>--}}
        {{--@else--}}
            {{--<a href="{{ URL('linux/sysCmd/getList') }}" class="btn btn-info">预设命令</a>--}}
            {{--<a class="btn btn-success pull-right" data-toggle="modal" onclick="cmdNew()" data-target="#serverModal"><i class="glyphicon glyphicon-plus"></i>&nbsp;New</a>--}}
        {{--@endif--}}

    </div>
</div>
<hr>

@if (Request::path()=='linux/server/getList' || Request::path()=='linux/cmd/getList')
    <script>
        // 添加命令弹出
        function cmdNew()
        {
            $(function () {
                var form = $('.serverForm,.fizzForm');
                form.find("input,textarea").val("");
            });
            // 调用执行命令的添加
            cmdNewRun();
        }
    </script>
@else
    <script>
        // 添加命令弹出
        function cmdNew()
        {
            // 调用执行命令的添加
            cmdNewRun();
        }
    </script>
@endif
