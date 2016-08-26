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
                    <form method="get" action="{{ URL('linux/serverCmd/serverCmdTeamList') }}" class="form-horizontal serverForm" role="form">
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
                                    {{ $v->created_at }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" align="center">暂无数据</td>
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

    @include('layouts._modal')
    <script>
        function fizzFormRun(id)
        {
            if(confirm("确定要执行吗？")) {
                $(function () {
                    $('#originModal').modal('show');
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

