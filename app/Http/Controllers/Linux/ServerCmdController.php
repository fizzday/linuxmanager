<?php

namespace App\Http\Controllers\Linux;

use App\Models\Server_cmd_user;
use App\User;
use App\Models\Cmd;
use App\Models\Server;
use App\Models\Server_cmd;
use App\Models\Sys_cmd;
use Illuminate\Support\Facades\Auth;
use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use SSH;
use Request;
use Route;

use App\Http\Controllers\Controller;

class ServerCmdController extends Controller
{
    /**
     * 执行结果的返回
     * @var
     */
    public $returnRunResult = [];

    protected $fields = [
        'id',
        'actname',
        'server_cmd',
        'user_id',
        'created_at'
    ];

    public function getList()
    {
        $req = Request::all();
        if (!isset($req['where'])) $req['where'] = [];

        $where = handleWhere($req);
        $where[] = ['user_id', Auth::user()->id];
        
        // 获取服务器列表
        $serverList = Server::where('user_id', Auth::user()->id)->get();

        // 获取命令列表
        $cmdList = Cmd::where('user_id', Auth::user()->id)->get();

        // 获取执行操作的列表
        $serverCmdList = Server_cmd::where($where)->paginate(20);

        // 获取预设命令列表
        $sysCmdList = Sys_cmd::get();

        return view('linux.home')
            ->with('where', $req['where'])
            ->with('serverList', $serverList)
            ->with('serverCmdList', $serverCmdList)
            ->with('cmdList', $cmdList)
            ->with('sysCmdList', $sysCmdList);
    }

    public function serverCmdTeamList()
    {
        $req = Request::all();
        if (!isset($req['where'])) $req['where'] = [];

//        $where = handleWhere($req);
        $where[] = ['a.child_id', Auth::user()->id];
        if (!empty($req['where']['id']))
            $where[] = ['b.id', $req['where']['id']];
        if (!empty($req['where']['actname']))
            $where[] = ['b.actname', 'like', '%'.$req['where']['actname'].'%'];

//        // 获取服务器列表
//        $serverList = Server::where('user_id', Auth::user()->id)->get();
//
//        // 获取命令列表
//        $cmdList = Cmd::where('user_id', Auth::user()->id)->get();
//
//        // 获取预设命令列表
//        $sysCmdList = Sys_cmd::get();

        // 获取执行操作的列表
//        $serverCmdList = Server_cmd::where('user_id', Auth::user()->id)->get();
        $serverCmdList = DB::table('server_cmd_users as a')
            ->leftJoin('server_cmds as b', 'a.server_cmd_id', '=', 'b.id')
            ->where($where)
            ->select('b.*')
            ->paginate(20);

        return view('linux.serverCmdTeamList')
            ->with('where', $req['where'])
            ->with('serverCmdList', $serverCmdList);
    }

    public function addOrEdit()
    {
        $data = Request::all();

        if (empty($data)) die('empty receive data (数据为空)');

        if (empty($data['id'])) {   // 添加
            $model = new Server_cmd();
        } else { // 编辑
            $model = Server_cmd::find($data['id']);
            unset($data['id']);
        }

        // 循环取出符合条件的数据
        foreach ($data as $k=>$v) {
            if ( in_array($k, $this->fields) ) {
                if ($k == 'server_cmd')
                    $model->$k = json_encode($v);
                else
                    $model->$k = $v;
            }
        }
        
        $model->user_id = Auth::user()->id;

        $model->save();

        success();
    }

    public function del()
    {
        $id = Route::input('id');
        // 执行删除
        $server = Server_cmd::find($id);
        $re = $server->delete();

        if (!$id) apireturn('', '9999');
        else apireturn();
    }

    public function run()
    {
        $id = Route::input('id');

        $cmdList = Server_cmd::find($id);

        $cmd = json_decode($cmdList->server_cmd);

        if ( !$cmd ) apireturn('暂无执行命令', '9999');

        $ssh = '';

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

                // 直接执行
                $run_cmd = explode("\r\n", $cmd);

                $cmd_real = [];
                foreach ($run_cmd as $item) {
                    if ($item && (substr(trim($item), 0, 1) != '#')) {
                        $cmd_real[] = $item;
                    }
                }
                
                $ssh->run($cmd_real, function($line)
                {
                    $this->returnRunResult[] = $line;
                });
            }
        }

        if (!$this->returnRunResult)
            apireturn('', '9999');
        else
            apireturn(json_encode($this->returnRunResult));
    }
    
    /**
     * 团队成员列表
     */
    public function userList()
    {
        $id = Route::input('id');
        // 命令信息
        $server_cmd = Server_cmd::find($id);

        // 团队信息
//        $server_cmd_user = Server_cmd_user::where('pid', $server_cmd->user_id)->pluck('child_id')->toArray();
//
//        // 获取用户信息
//        $user_list = User::whereIn('id', $server_cmd_user)->get();

        $server_cmd_user = DB::table('server_cmd_users as a')
            ->leftJoin('users as b', 'a.child_id', '=', 'b.id')
            ->where('pid', $server_cmd->user_id)
            ->where('a.server_cmd_id', $id)
            ->select('a.id', 'b.email', 'b.name')
            ->paginate(20);

        return view('linux.userList')
            ->with('server_cmd', $server_cmd)
            ->with('server_cmd_user', $server_cmd_user)
            ->with('server_cmd_id', $id);
    }

    /**
     * 团队成员的增加
     */
    public function userAdd()
    {
        $post = Request::all();

        Validator::make($post, [
            'server_cmd_id' => 'required|num',
            'email' => 'required|email|max:255',
        ]);

        // 检查是否有此人
        $user = User::where('email', $post['email'])->first();
        if (!$user) error('查无此人');

        // 检查此命令是否存在
        $server_cmd = Server_cmd::find($post['server_cmd_id']);
        if (!$server_cmd) error('查无此条命令');

        // 添加此用户到本组
        $server_cmd_user = new Server_cmd_user;
        $server_cmd_user->server_cmd_id = $post['server_cmd_id'];
        $server_cmd_user->pid = $server_cmd->user_id;
        $server_cmd_user->child_id = $user->id;
        $res = $server_cmd_user->save();

        if (!$res) error('添加失败');

        success();
    }

    /**
     * 团队成员的删除
     */
    public function userDel()
    {
        $id = Route::input('id');

        // 命令信息
        $server_cmd_user = Server_cmd_user::find($id);
        $res = $server_cmd_user->delete();

        if (!$res) error();

        success();
    }
}
