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
                <div class="col-sm-12">
                    <form method="get" action="{{ URL('linux/server/getList') }}" class="form-horizontal serverForm" role="form">
                        编号:<input type="text" value="{{ $where['id'] or "" }}" name="where[id]" placeholder="输入编号,如: 2"/>&nbsp;
                        名字:<input type="text" value="{{ $where['servername'] or "" }}" name="where[servername]" placeholder="输入名字,如: 正式服务器"/>&nbsp;
                        host:<input type="text" value="{{ $where['host'] or "" }}" name="where[host]" placeholder="输入hot,如: 127.0.0.1"/>&nbsp;
                        <input type="submit" class="btn btn-success btn-sm" value="查询">&nbsp;
                        <input type="submit" class="btn btn-warning btn-sm" value="查询">
                    </form>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-md-12 column">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>
                                编号
                            </th>
                            <th>
                                名字
                            </th>
                            <th>
                                host
                            </th>
                            <th>
                                用户名
                            </th>
                            <th>
                                操作
                            </th>
                            <th>
                                添加时间
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        {{--@if ($serverList ==='')--}}
                            {{--<tr>--}}
                                {{--<td colspan="6" align="center">暂无数据</td>--}}
                            {{--</tr>--}}
                        {{--@endif--}}
                        @forelse($serverList as $v)
                            <tr>
                                <td>
                                    {{ $v->id }}
                                </td>
                                <td>
                                    {{ $v->servername }}
                                </td>
                                <td>
                                    {{ $v->host }}
                                </td>
                                <td>
                                    {{ $v->username }}
                                </td>
                                <td>
                                    {{--<a href="/linux/cmdList/{{ $v->id }}" class="btn btn-success btn-sm">查看</a>--}}
                                    <a href="#" data-toggle="modal" data-target="#serverModal" onclick="serverEdit(this)" class="serverEdit btn btn-info btn-sm">编辑</a>
                                    <span href="" onclick="serverDel({{ $v->id }})" class="serverDel btn btn-warning btn-sm">删除</span>
                                </td>
                                <td>
                                    {{ $v->created_at }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" align="center">暂无数据</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12 column">
                    <div class="pull-right">{!! $serverList->render() !!}</div>
                </div>
            </div>
            <!-- Your Page Content Here -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <!-- 弹出框 -->
    <div id="serverModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">添加服务器信息</h4>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ URL('linux/server/addOrEdit') }}" class="form-horizontal serverForm" role="form">
                        <input name="id" type="hidden" value=""/>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">名字</label>
                            <div class="col-sm-10">
                                <input name="servername" placeholder="如: 测试服务器" type="text" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">ip</label>
                            <div class="col-sm-10">
                                <input name="host" placeholder="如: 127.0.0.1" type="text" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">用户名</label>
                            <div class="col-sm-10">
                                <input name="username" placeholder="如: root" type="text" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">密码</label>
                            <div class="col-sm-10">
                                <input name="password" placeholder="如: 123456" type="password" class="form-control" />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="serverSubmit btn btn-success">提交</button>
                        </div>
                    </form>
                </div>
                {{--<div class="modal-footer">--}}
                    {{--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>--}}
                    {{--<button type="submit" class="serverSubmit btn btn-success">提交</button>--}}
                {{--</div>--}}
            </div>
        </div>
    </div>
    <script>
        $(function () {
            // 提交
            $("#serverModal button.serverSubmit").click( function(){
//                alert(3);
                $(this).parent().prev().find('form').submit();
            });
            // 编辑 服务器
//            $(".serverEdit").click(function () {
//                var form = $('.serverForm');
//                var a = $(this).parent().parent().find('td').each(function () {
//                    var index1 = $(this).index();
//                    if (index1 < 4)
//                        form.find('input:eq('+index1+')').val($.trim($(this).html()));
//                })
//            });
        });

        function serverAdd(obj)
        {
            var form = $('.serverForm');
            form.find('input').val();
        }

        function serverEdit(obj)
        {
            $(function () {
                var a = $(obj).parent().parent().find('td');
                var form = $('.serverForm');
                a.each(function () {
                    var index1 = $(this).index();
                    var content = $.trim($(this).html());
                    if (index1 < 4)
                        form.find('input:eq('+index1+')').val(content);
                });
            });
        }
        function serverDel(id)
        {
            if(confirm("确定要删除吗？")) {
                $(function () {
                    $.get('/linux/server/del/'+id, {}, function(re){
                        if (re.status == '9999')
                            alert('删除失败')
                        else
                            window.location.reload();
                    });
                });
            }
        }
    </script>
</div>
@endsection

