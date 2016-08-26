<?php

namespace App\Http\Controllers\Linux;

use App\Models\Server;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class NginxController extends Controller
{

    public function getList()
    {
        $req = Request::all();
        if (!isset($req['where'])) $req['where'] = [];

        $where = handleWhere($req);

        // 根据接收的服务器 id , 获取配置文件
        if (!empty($req['id'])) {
            // 获取服务器连接
            $server = Server::find($req['id']);
            Config::set('remote.connections.runtime.host', $server->host);
            Config::set('remote.connections.runtime.username', $server->username);
            Config::set('remote.connections.runtime.password', $server->password);

            $ssh = SSH::into('runtime');

            // 获取配置文件列表
            if ($ssh == '') apireturn('请先选择服务器', '9999');
            $cmd_real = '';
            $ssh->run($cmd_real, function($line)
            {
                $this->returnRunResult[] = $line;
            });
        }

        $list = Sys_cmd::where($where)->paginate(10);

        return view('linux.sysCmdList')
            ->with('where', $req['where'])
            ->with('cmdList', $list);
    }
}
