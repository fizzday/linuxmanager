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
                        <form method="get" action="{{ URL('linux/cmd/getList') }}" class="form-horizontal" role="form">
                            编号:<input type="text" value="{{ $where['id'] or "" }}" name="where[id]" placeholder="输入编号,如: 2"/>&nbsp;
                            名字:<input type="text" value="{{ $where['cmdname'] or "" }}" name="where[cmdname]" placeholder="输入名字,如: 查看home"/>&nbsp;
                            命令:<input type="text" value="{{ $where['cmd'] or "" }}" name="where[cmd]" placeholder="输入命令,如: ls /home"/>&nbsp;
                            <input type="submit" class="btn btn-success btn-sm" value="查询">
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
                                    命令内容
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
                            @forelse($cmdList as $v)
                                <tr>
                                    <td>
                                        {{ $v->id }}
                                    </td>
                                    <td>
                                        {{ $v->cmdname }}
                                    </td>
                                    <td>
                                        @if ( strlen($v->cmd) >30 )
                                            <a href="#" title="{{ $v->cmd }}">
                                                {{ substr($v->cmd, 0, 30) }}......
                                            </a>
                                        @else
                                            <a href="#" title="{{ $v->cmd }}">
                                                {{ $v->cmd }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        {{--<a href="/linux/serverInfo/{{ $v->id }}" class="btn btn-success btn-sm">查看</a>--}}
                                        <a href="#" data-toggle="modal" data-target="#serverModal" onclick="fizzFormEdit(this)" class="serverEdit btn btn-info btn-sm">编辑</a>
                                        <span href="" onclick="fizzFormDel({{ $v->id }})" class="serverDel btn btn-warning btn-sm">删除</span>
                                    </td>
                                    <td>
                                        {{ $v->created_at }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" align="center">暂无数据</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12 column">
                        <div class="pull-right">{!! $cmdList->render() !!}</div>
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
                        <h4 class="modal-title">添加命令</h4>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="{{ URL('linux/cmd/addOrEdit') }}" class="form-horizontal fizzForm" role="form">
                            <input name="id" type="hidden" value=""/>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-1 control-label">name</label>
                                <div class="col-sm-11">
                                    <input name="cmdname" placeholder="如: 测试命令" type="text" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-1 control-label">cmd</label>
                                <div class="col-sm-11">
                                    <textarea name="cmd" placeholder="如: ls /home, 多条命令请换行" rows="12" class="form-control" /></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <a href="javascript:;" onclick="fizzFormSubmit()" class="btn btn-success">提交</a>
                    </div>
                </div>
            </div>
        </div>
        <script>

            function fizzFormSubmit()
            {
                $(function () {
                    $('.fizzForm').submit();
                });
            }

            function fizzFormEdit(obj)
            {
                $(function () {
                    var a = $(obj).parent().parent().find('td');
                    var form = $('.fizzForm');
                    a.each(function () {
                        var index1 = $(this).index();
                        var content = $.trim($(this).html());
                        if (index1 < 2)
                            form.find('input:eq('+index1+')').val(content);
                        else if (index1 == 2)
                            form.find('textarea').val($(this).find('a').attr('title'));
                    });
                });
            }

            function fizzFormDel(id)
            {
                if(confirm("确定要删除吗？")) {
                    $(function () {
                        $.get('/linux/cmd/del/'+id, {}, function(re){
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


