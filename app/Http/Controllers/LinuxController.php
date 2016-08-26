<?php

namespace App\Http\Controllers;

use Request;
use Route;
use DB;

use App\Models\Server;
use App\Models\Cmd;
use App\Models\Server_cmd;
use App\Models\Sys_cmd;

use SSH,
    Config
    ;

class LinuxController extends Controller
{
    public $fields = [
        'id',
        'servername',
        'host',
        'username',
        'password',
        'user_id'
    ];

    protected $apireturn;

    public function index()
    {
        $title = '自动化运维首页';

        $serverList = Server::get();

        return view('linux.home')->with('title', $title)->with('serverList', $serverList);
    }

    public function serverList()
    {
        $title = '自动化运维首页';

        $serverList = Server::get();

        return view('linux.serverList')->with('title', $title)->with('serverList', $serverList);
    }

    public function serverAddOrEdit()
    {
        $data = Request::all();

        if (empty($data)) die('empty receive data (数据为空)');

        if (empty($data['id'])) {   // 添加
            $model = new Server();
        } else { // 编辑
            $model = Server::find($data['id']);
            unset($data['id']);
        }

        // 循环取出符合条件的数据
        foreach ($data as $k=>$v) {
            if ( $v == '' ) unset($data[$k]);
            else $model->$k = $v;
        }

        $model->save();

        success();
    }

    public function serverDel()
    {
        $id = Route::input('id');
        // 执行删除
        $server = Server::find($id);
        $re = $server->delete();

        if (!$id) apireturn('', '9999');
        else apireturn();
    }

    public function cmdList()
    {
//        $id = Route::input('id');
        $cmdList = Cmd::get();
        return view('linux.cmdList')->with('cmdList', $cmdList);
    }

    public function SysCmdList()
    {
        $sysCmdList = Sys_cmd::get();
        return view('linux.sysCmdList')->with('cmdList', $sysCmdList);
    }

    public function cmdAddOrEdit()
    {
        $data = Request::all();

        if (empty($data)) die('empty receive data (数据为空)');

        if (empty($data['id'])) {   // 添加
            $model = new cmd();
        } else { // 编辑
            $model = Cmd::find($data['id']);
            unset($data['id']);
        }

        // 循环取出符合条件的数据
        foreach ($data as $k=>$v) {
            if ( $v == '' ) unset($data[$k]);
            else $model->$k = $v;
        }

        $model->save();

        success();
    }

    public function cmdDel()
    {
        $id = Route::input('id');
        // 执行删除
        $server = Cmd::find($id);
        $re = $server->delete();

        if (!$id) apireturn('', '9999');
        else apireturn();
    }

    public function serverCmdList()
    {
        // 获取服务器列表
        $serverList = Server::get();

        // 获取命令列表
        $cmdList = Cmd::get();

        // 获取预设命令列表
        $sysCmdList = Sys_cmd::get();

        // 获取执行操作的列表
        $serverCmdList = Server_cmd::get();

        return view('linux.home')
            ->with('serverList', $serverList)
            ->with('serverCmdList', $serverCmdList)
            ->with('cmdList', $cmdList)
            ->with('sysCmdList', $sysCmdList);
    }

    public function serverCmdAddOrEdit()
    {
        $data = Request::all();
        
        if (empty($data)) die('empty receive data (数据为空)');

        if (empty($data['id'])) {   // 添加
            $model = new Server_cmd();
        } else { // 编辑
            $model = Server_cmd::find($data['id']);
        }
        unset($data['id']);
        
        // 循环去除未选取的数据
        foreach ($data['server_cmd'] as $k=>$v) {
            if ( $v['value'] == 99 ) unset($data['server_cmd'][$k]);
        }
        $model->actname = $data['actname'];
        $model->server_cmd = json_encode($data['server_cmd']);

        $model->save();

        success();
    }

    public function serverCmdDel()
    {
        $id = Route::input('id');
        // 执行删除
        $server = Server_cmd::find($id);
        $re = $server->delete();

        if (!$id) apireturn('', '9999');
        else apireturn();
    }

    public function serverCmdRun()
    {
        $id = Route::input('id');

        $cmdList = Server_cmd::find($id);

        $cmd = json_decode($cmdList->server_cmd);
        
        if ( !$cmd ) apireturn('暂无执行命令', '9999');

        $ssh = '';
        $apireturn = '';
        foreach ($cmd as $v) {
            if ($v->key == 'server') {
                // 获取服务器信息
                $server = Server::find($v->value);

                Config::set('remote.connections.runtime.host', $server->host);
                Config::set('remote.connections.runtime.username', $server->username);
                Config::set('remote.connections.runtime.password', $server->password);

                $ssh = SSH::into('runtime');

            } else {
                if ($ssh == '') apireturn('请先选择服务器', '9999');
                // 获取命令信息
                if ($v->key == 'cmd')
                    $cmd = Cmd::find($v->value)->cmd;
                else
                    $cmd = Sys_cmd::find($v->value)->sys_cmd;

                $cmd_array = explode("\r\n", $cmd);

                foreach ($cmd_array as $item) {
                    $ssh->run($item, function($line)
                    {
                        $this->apireturn .= $line.PHP_EOL;
                    });
                }
            }
        }
//d($this->apireturn);
        if (!$this->apireturn) apireturn('', '9999');
        else apireturn($this->apireturn);
    }


}