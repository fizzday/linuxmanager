<?php

namespace App\Http\Controllers\Whois;

use App\Libs\Whois;
use Request;
use App\Http\Controllers\Controller;

class WhoisController extends Controller
{

    public function index()
    {
        $all = Request::all();
//        echo testForm($all, 'post', ['url']);
        $whois = [];
        if ($_POST) {
            $whois = Whois::get($all['where']['url']);

            if (!$whois) error('此域名不提供查询');
        }

        return view('whois.index')->with('whois', $whois)->with('all', $all);

    }

}
