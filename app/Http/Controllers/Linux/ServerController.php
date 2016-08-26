<?php

namespace App\Http\Controllers\Linux;

use App\Models\Server;
use Illuminate\Support\Facades\Auth;
use Request;
use Route;

use App\Http\Controllers\Controller;

class ServerController extends Controller
{
    protected $fields = [
        'id',
        'servername',
        'host',
        'username',
        'password',
        'user_id',
        'created_at'
    ];

    public function getList()
    {
        $req = Request::all();
        if (!isset($req['where'])) $req['where'] = [];

        $where = handleWhere($req);
        $where[] = ['user_id', Auth::user()->id];

        $list = Server::where($where)->paginate(10);

        return view('linux.serverList')
            ->with('where', $req['where'])
            ->with('serverList', $list);
    }

    public function addOrEdit()
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
            if ( in_array($k, $this->fields) ) $model->$k = trim($v);
        }

        $model->user_id = Auth::user()->id;
//d($model);
        $re = $model->save();
        
        success();
    }

    public function del()
    {
        $id = Route::input('id');
        // 执行删除
        $server = Server::find($id);
        $re = $server->delete();

        if (!$id) apireturn('', '9999');
        else apireturn();
    }
}
