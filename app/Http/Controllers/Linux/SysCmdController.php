<?php

namespace App\Http\Controllers\Linux;

use App\Models\Sys_cmd;
use Request;
use App\Http\Controllers\Controller;

class SysCmdController extends Controller
{

    public function getList()
    {
        $req = Request::all();
        if (!isset($req['where'])) $req['where'] = [];

        $where = handleWhere($req);

        $list = Sys_cmd::where($where)->paginate(10);

        return view('linux.sysCmdList')
            ->with('where', $req['where'])
            ->with('cmdList', $list);
    }
}
