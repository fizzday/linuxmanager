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
                        <form method="get" action="{{ URL('linux/sysCmd/getList') }}" class="form-horizontal serverForm" role="form">
                            编号:<input type="text" value="{{ $where['id'] or "" }}" name="where[id]" placeholder="输入编号,如: 2"/>&nbsp;
                            名字:<input type="text" value="{{ $where['cmdname'] or "" }}" name="where[cmdname]" placeholder="输入名字,如: 查看home"/>&nbsp;
                            命令:<input type="text" value="{{ $where['sys_cmd'] or "" }}" name="where[sys_cmd]" placeholder="输入命令,如: ls /home"/>&nbsp;
                            <input type="submit" class="btn btn-success btn-sm" value="查询">&nbsp;
                            <input type="submit" class="btn btn-warning btn-sm" value="重置">
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
                                        @if ( strlen($v->sys_cmd) >30 )
                                            <a href="#" title="{{ $v->sys_cmd }}">
                                                {{ substr($v->sys_cmd, 0, 30) }}......
                                            </a>
                                        @else
                                            <a href="#" title="{{ $v->sys_cmd }}">
                                                {{ $v->sys_cmd }}
                                            </a>
                                        @endif
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
                        <div class="pull-right">{!! $cmdList->render() !!}</div>
                    </div>
                </div>
                <!-- Your Page Content Here -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <script>

            function rmNew() {
                $(function () {
                    alert(3)
                });
            }

            rmNew();

        </script>
    </div>
@endsection



