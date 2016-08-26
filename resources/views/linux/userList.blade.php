@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            @include('linux._nav')
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row clearfix">
                <div class="col-md-12 column">
                    <h4> {{ $server_cmd->actname }}  >> 组内人员列表 </h4>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-md-12 column">
                    &nbsp;
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-md-12 column">
                    <ul class="nav nav-pills">
                        @forelse($server_cmd_user as $v)
                        <li class="active">
{{--                            <a onclick="fizzFormDel({{ $v->id }})" href="{{ URL('linux/serverCmd/userDel') }}/{{ $v->id }}">{{ $v->name }} ({{ $v->email }}) <span class="badge pull-right">X</span> </a>--}}
                            <a onclick="fizzFormDel({{ $v->id }})" href="javascript:void(0)">{{ $v->name }} ({{ $v->email }}) <span class="badge pull-right">X</span> </a>
                        </li>
                        @empty
                            <center><h2>暂无成员</h2></center>
                        @endforelse

                    </ul>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-md-12 column">
                    &nbsp;
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <!-- 弹出框 -->
    <div id="serverModal" class="modal fade" role="dialog">
    <div id="serverModal" class="modal fade in" role="dialog" aria-hidden="false" style="display: block;">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content col-md-10">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">添加用户到本组命令</h4>
                </div>
                <div class="modal-body">
                    {{--<form method="post" action="{{ URL('linux/serverCmd/addOrEdit') }}" class="form-horizontal fizzForm" role="form">--}}
                        {{--<input name="id" type="hidden" value=""/>--}}
                        {{--<label for="inputEmail3" class="col-sm-3 control-label">执行操作名</label>--}}
                        {{--<div class="col-sm-9">--}}
                            {{--<input name="actname" placeholder="如: 重启服务器" type="text" class="form-control" />--}}
                        {{--</div>--}}
                    {{--</form>--}}
                    {{--<div class="col-md-12 column">--}}
                        <form method="post" action="{{ URL('linux/serverCmd/userAdd') }}" class="form-horizontal fizzForm" role="form">
{{--                        <form method="post" action="{{ URL('linux/serverCmd/userAdd') }}" class="form-horizontal fizzForm" role="form">--}}
                            <input type="hidden" name="server_cmd_id" value="{{ $server_cmd_id }}">
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" name="email" class="form-control" placeholder="请输入用户的邮箱地址" />
                                </div>
                            </div>
                        </form>
                    {{--</div>--}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" onclick="fizzFormSubmit()" class="serverSubmit btn btn-success">提交</button>
                </div>
            </div>
        </div>
    </div>

    <script>

        function fizzFormSubmit()
        {
            $('.fizzForm').submit();
        }


        function fizzFormDel(id)
        {
            if(confirm("确定要删除吗？")) {
//                $(function () {
//                    $.get('/linux/serverCmd/del/'+id, {}, function(re){
//                        if (re.status == '9999')
//                            alert('删除失败')
//                        else
//                            window.location.reload();
//                    });
//                });
                window.location.href = "{{ URL('linux/serverCmd/userDel') }}/"+id;
            }
        }

    </script>
</div>
@endsection

