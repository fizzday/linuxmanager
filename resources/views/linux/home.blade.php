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
                    <form method="get" action="{{ URL('linux/serverCmd/getList') }}" class="form-horizontal serverForm" role="form">
                        编号:<input type="text" value="{{ $where['id'] or "" }}" name="where[id]" placeholder="输入编号,如: 2"/>&nbsp;
                        操作:<input type="text" value="{{ $where['actname'] or "" }}" name="where[actname]" placeholder="输入名字,如: 部署测试环境"/>&nbsp;
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
                                执行的操作
                            </th>
                            <th>
                                运行
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
                        @forelse($serverCmdList as $v)
                            <tr>
                                <td>
                                    {{ $v->id }}
                                </td>
                                <td title="{{ $v->server_cmd }}">
                                    {{ $v->actname }}
                                </td>
                                <td>
                                    <a href="#" data-toggle="modal" data-target="#originModal.bak" class="btn btn-danger" onclick="fizzFormRun({{ $v->id }})">
                                        <i class="glyphicon glyphicon-forward"></i>&nbsp;Run
                                    </a>
                                </td>
                                <td>
                                    <a href="/linux/serverCmd/userList/{{ $v->id }}" class="btn btn-success btn-sm">查看&成员</a>
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
                    <div class="pull-right">{!! $serverCmdList->render() !!}</div>
                </div>
            </div>
            <!-- Your Page Content Here -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <!-- 弹出框 -->
    <div id="serverModal" class="modal fade" role="dialog">
    {{--<div id="serverModal" class="modal fade in" role="dialog" aria-hidden="false" style="display: block;">--}}
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content col-md-10">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">构建新的执行命令</h4>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ URL('linux/serverCmd/addOrEdit') }}" class="form-horizontal fizzForm" role="form">
                        <input name="id" type="hidden" value=""/>
                        <div class="col-md-12">
                            <label for="inputEmail3" class="col-sm-2 control-label">执行操作名</label>
                            <div class="col-sm-9">
                                <input name="actname" placeholder="如: 重启服务器" type="text" class="form-control" />
                            </div>
                            <div class="col-md-1">
                                <div class="input-group">
                                          {{--<span class="input-group-addon">--}}
                                            <a href="javascript:;" onclick="cmdAdd(this)" class="btn btn-info"><i class="glyphicon glyphicon-plus-sign"></i></a>
                                          {{--</span>--}}
                                </div><!-- /input-group -->
                            </div>
                        </div>
                        <div class="col-md-12">&nbsp;</div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" onclick="fizzFormSubmit()" class="serverSubmit btn btn-success">提交</button>
                </div>
            </div>
        </div>
    </div>

    @include('layouts._modal')
    <script type="text/html" id="jsCloneText">
        <div class="form-group">
            <div class="row">
                <div class="col-md-1">
                    <div class="input-group">
                        <span class="input-group-addon">
                        1
                        </span>
                    </div><!-- /input-group -->
                </div>
                <div class="col-md-10">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <label onclick="radioSelect(0, this)"><input type="radio" name="" value="server" checked aria-label="...">服务器</label>
                        </span>
                        <span class="input-group-addon">
                            <label onclick="radioSelect(1, this)"><input type="radio" name="" value="cmd" aria-label="...">命令</label>
                        </span>
                        <span class="input-group-addon">
                            <label onclick="radioSelect(2, this)"><input type="radio" name="" value="syscmd" aria-label="...">预设</label>
                        </span>
                        <select class="form-control" name="">
                            <option value="99">请选择服务器</option>
                            @foreach($serverList as $v)
                                <option value="{{ $v->id }}">{{ $v->servername }}</option>
                            @endforeach
                        </select>
                        <select class="form-control" name="" style="display: none" disabled>
                            <option value="99">请选择命令</option>
                            @foreach($cmdList as $v)
                                <option value="{{ $v->id }}">{{ $v->cmdname }}</option>
                            @endforeach
                        </select>
                        <select class="form-control" name="" style="display: none" disabled>
                            <option value="99">请选择系统预设命令</option>
                            @foreach($sysCmdList as $v)
                                <option value="{{ $v->id }}">{{ $v->cmdname }}</option>
                            @endforeach
                        </select>
                    </div><!-- /input-group -->
                </div><!-- /.col-lg-6 -->

                <div class="col-md-1">
                    <div class="input-group">
                        <span class="input-group-addon">
                        <a href="javascript:;" onclick="cmdMinus(this)" class="inputOper"><i class="glyphicon glyphicon-minus-sign"></i></a>
                        </span>
                    </div><!-- /input-group -->
                </div>
            </div><!-- /.row -->
        </div>
    </script>
    <script>
        // 选择 radio
        function radioSelect(index, obj)
        {
            var select = $(obj).parent().parent();
            select.find('select').hide().attr('disabled', true);
            select.find('select:eq('+index+')').show().attr('disabled', false);
        }

        // 添加命令弹出
        function cmdNewRun()
        {
            $(function () {
                $('.fizzForm .form-group').remove();
                var thisNode = $("#jsCloneText").html();
                $('.fizzForm').append(thisNode);

                // 重新分配值
                reSort();
            });
        }

        // 添加新的选择框
        function cmdAdd(obj)
        {
            $(function () {
                // 先清除第一个的 name
//                var thisNode = $(obj).parent().parent().parent().parent().parent(); // 获取当前form-group的内容
                var thisNode = $("#jsCloneText").html();
//                thisNode.find('input[type="radio"]').attr('name', '');
//                var thisNode = thisNode.clone();
//                $('.fizzForm .form-group:last').after(thisNode);
                $('.fizzForm').append(thisNode);

                // 重新分配值
                reSort();
            });
        }

        function cmdMinus(obj)
        {
            $(function () {
                $(obj).parent().parent().parent().parent().parent().remove();

                // 重新分配值
                reSort();
            });
        }

        // 重新动态分配所有的排序和 input 的 name
        function reSort()
        {
            $(function () {
                var formGroup = $('.fizzForm .form-group');
                var index = 1;
                // 更改序号
                formGroup.each(function () {
                    $(this).find('.input-group-addon:first').html(index);
                    $(this).find('input[type="radio"]').attr('name', 'server_cmd['+(index-1)+'][key]');
                    $(this).find('select').attr('name', 'server_cmd['+(index-1)+'][value]');
                    index++;
                });

            });
        }

        function fizzFormSubmit()
        {
            $('.fizzForm').submit();
        }

        function fizzFormEdit(obj)
        {
            $(function () {
                // 获取当前 tr
                var tr = $(obj).parent().parent();
                var fizzForm = $('.fizzForm');

                var id = $.trim(tr.find('td:eq(0)').html());
                var serverName = $.trim(tr.find('td:eq(1)').html());
                var cmd = eval($.trim(tr.find('td:eq(1)').attr('title')));

                fizzForm.find('input[name="actname"]').val(serverName);
                fizzForm.find('input[name="id"]').val(id);

                if (cmd != '') {
                    // 先清除第一个的 radio 的 name
                    fizzForm.find('.form-group').remove(); // 获取当前form-group的内容
//                    thisNode.find('input[type="radio"]').attr('name', '');
//                    var thisNodeClone = thisNode.clone();
                    var thisNodeClone = $("#jsCloneText").html();
//                    thisNode.remove();

                    for (var i in cmd) {
                        var key = cmd[i].key;
                        var value = cmd[i].value;
                        fizzForm.append(thisNodeClone);

                        reSort();

                        var formLast = fizzForm.find('.form-group:last');   // 获取最后一个插入的 input 组
                        var input = formLast.find('input');

                        var index = 0;
                        input.each(function () {
                            if ($(this).val() == key) {
                                $(this).attr('checked', true);
                                indexs = index;
                            }
                            else {
                                $(this).attr('checked', false);
                            }
                            index++;
                        });

                        // 获取 select 节点
                        formLast.find('select').hide().attr('disabled', true);          // 隐藏所有select为不可用
                        formLast.find('select:eq('+indexs+')').show().attr('disabled', false);  // 显示当前的为显示可用

                        var option = formLast.find('select:eq('+indexs+') option');
                        console.log(indexs)
                        option.attr('selected', false);
//                        console.log(option)
                        option.each(function () {
                            console.log($(this).val())
                            if ($(this).val() == value) $(this).attr('selected', true);
                        });
                    }
                }
            });
        }

        function fizzFormDel(id)
        {
            if(confirm("确定要删除吗？")) {
                $(function () {
                    $.get('/linux/serverCmd/del/'+id, {}, function(re){
                        if (re.status == '9999')
                            alert('删除失败')
                        else
                            window.location.reload();
                    });
                });
            }
        }

        function fizzFormRun(id)
        {
//            $(function () {
//                $('#originModal').modal('show');
//            });
            if(confirm("确定要执行吗？")) {
                $(function () {
                    $('#originModal').modal('show');
//                    $('#originModal').modal('hide');
//                    var originModal = $('#originModal');
                    $('#originModal .modal-header h4').html('执行结果');
                    $('#originModal .serverSubmit').remove();
                    var modalBody = $('#originModal .modal-body');
                    modalBody.html('执行中......');
                    $.get('/linux/serverCmd/run/'+id, {}, function(re){

                        var result = eval(re.data);

                        var showData = result[0].replace(/\n/ig,"<br/>");
                        modalBody.html(showData);
                    }, "json");
                });
            }
        }
    </script>
</div>
@endsection

