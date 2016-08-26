<?php

namespace App\Http\Controllers\Linux;

use App\Models\Cmd;
use Illuminate\Support\Facades\Auth;
use Request;
use Route;

use App\Http\Controllers\Controller;

class CmdController extends Controller
{
    protected $fields = [
        'id',
        'cmdname',
        'cmd',
        'user_id',
        'created_at'
    ];

    public function getList()
    {
        $req = Request::all();
        if (!isset($req['where'])) $req['where'] = [];

        $where = handleWhere($req);
        $where[] = ['user_id', Auth::user()->id];

        $list = Cmd::where($where)->paginate(10);

        return view('linux.cmdList')
            ->with('where', $req['where'])
            ->with('cmdList', $list);
    }

    public function addOrEdit()
    {
        $data = Request::all();

        if (empty($data)) die('empty receive data (数据为空)');

        if (empty($data['id'])) {   // 添加
            $model = new Cmd();
        } else { // 编辑
            $model = Cmd::find($data['id']);
            unset($data['id']);
        }

        // 循环取出符合条件的数据
        foreach ($data as $k=>$v) {
            if ( in_array($k, $this->fields) ) $model->$k = $v;
        }

        $model->user_id = Auth::user()->id;

        $model->save();

        success();
    }

    public function del()
    {
        $id = Route::input('id');
        // 执行删除
        $server = Cmd::find($id);
        $re = $server->delete();

        if (!$id) apireturn('', '9999');
        else apireturn();
    }
}
