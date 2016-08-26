@extends('layouts.app')

@section('content')
    <div class="container">
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h2>查询网站信息</h2>
            </section>
            <!-- Main content -->
            <section class="content">

                <div class="row"><hr></div>
                <div class="row clearfix">
                    <div class="col-sm-12">
                        {{--<form method="post" action="{{ URL('whois') }}" class="form-horizontal serverForm" role="form">--}}
                            {{--编号:<input type="text" value="{{ $all['where']['url'] or "" }}" name="where[url]" placeholder="输入域名,如: 2"/>&nbsp;--}}
                            {{--<input type="submit" class="btn btn-success btn-sm" value="查询">&nbsp;--}}
                            {{--<input type="submit" class="btn btn-warning btn-sm" value="重置">--}}
                        {{--</form>--}}
                        <form method="post" action="{{ URL('whois') }}" class="form-horizontal serverForm" role="form">
                            {{--<div class="form-group">--}}
                                {{--<label for="url">域名 ：</label>--}}
                                {{--<input value="{{ $all['where']['url'] or "" }}" name="where[url]" placeholder="输入域名,如: aac.com" type="text" class="form-control" id="url" />--}}
                            {{--</div>--}}
                            <div class="input-group">
                                <span class="input-group-addon" id="sizing-addon1">域名</span>
                                <input value="{{ $all['where']['url'] or "zhaoyoutu.com" }}" name="where[url]" placeholder="输入域名,如: zhaoyoutu.com" type="text" class="form-control" id="url" />
                                {{--<input type="reset" value="重置">--}}
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-info">提交</button>
                                    <span type="btn" onclick="formReset(this);" class="btn btn-warning">清空</span>
                                </span>
                            </div><!-- /input-group -->
                            {{--<button type="submit" class="btn btn-default">提交</button>--}}
                        </form>
                    </div>
                </div>
                <div class="row"><hr></div>
                <div class="row clearfix">
                    <div class="col-md-12 column">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>
                                    项目
                                </th>
                                <th>
                                    信息
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @if ($whois)
                                <tr><td>域名</td><td>{{ $whois['Domain_Name'] }}</td></tr>
                                <tr><td>注册时间</td><td>{{ $whois['Creation_Date'] }}</td></tr>
                                <tr><td>到期时间</td><td>{{ $whois['Registrar_Registration_Expiration_Date'] }}</td></tr>
                                <tr><td>城市</td><td>{{ $whois['address'] }}</td></tr>
                                <tr><td>服务商</td><td>{{ $whois['Registrar_WHOIS_Server'] }}</td></tr>
                                <tr><td>注册人</td><td>{{ $whois['Registrant_Name'] }}</td></tr>
                                <tr><td>注册机构</td><td>{{ $whois['Registrant_Organization'] }}</td></tr>
                                <tr><td>电话</td><td>{{ $whois['Registrant_Phone'] }}</td></tr>
                                <tr><td>邮箱</td><td>{{ $whois['Registrant_Email'] }}</td></tr>
                                <tr><td>qq</td><td>{{ $whois['qq'] }}</td></tr>
                            @else
                                <tr>
                                    <td colspan="4" align="center">暂无数据</td>
                                </tr>
                            @endif
                            {{--@forelse($whois as $k=>$v)--}}
                                {{--<tr>--}}
                                    {{--<td>--}}
                                        {{--{{ $k }}--}}
                                    {{--</td>--}}
                                    {{--<td>--}}
                                        {{--{{ $v }}--}}
                                    {{--</td>--}}
                                {{--</tr>--}}
                            {{--@empty--}}
                                {{--<tr>--}}
                                    {{--<td colspan="4" align="center">暂无数据</td>--}}
                                {{--</tr>--}}
                            {{--@endforelse--}}
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Your Page Content Here -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <script>

            function formReset(obj) {
                $(function () {
                    $(obj).parent().prev().val('');
                });
            }

//            rmNew();

        </script>
    </div>
@endsection



