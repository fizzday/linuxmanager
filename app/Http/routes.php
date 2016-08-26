<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    //return redirect('/whois');
    return view('welcome');
});

Route::get('/test', function () {
    v(Auth::user()->email);
    echo 'welcome';
});

Route::auth();

Route::get('/home', 'HomeController@index');

/**
 * 工具类
 */
Route::group(['prefix' => 'tool'], function(){
    /**
     * 获取表的字段及注释
     * @api /tool
     */
    Route::get('', 'ToolController@index');
});
/**
 * whios信息查询
 */
Route::group(['prefix'=>'whois', 'namespace'=>'Whois'], function() {
    Route::any('', 'WhoisController@index');
//    Route::post('', 'WhiosController@index');
});
/**
 * 运维
 */
Route::group(['prefix'=>'linux', 'middleware'=>'auth', 'namespace'=>'Linux'], function() {
    /**
     * 执行命令
     */
    Route::get('', 'ServerCmdController@getList');
        
    Route::group(['prefix'=>'serverCmd'], function () {
        // 团队命令列表
        Route::get('serverCmdTeamList', 'ServerCmdController@serverCmdTeamList');
        Route::get('getList/{id?}', 'ServerCmdController@getList');
        Route::post('addOrEdit', 'ServerCmdController@addOrEdit');
        Route::get('del/{id}', 'ServerCmdController@del');
        Route::get('run/{id}', 'ServerCmdController@run');
        // 团队成员的管理
        Route::get('userList/{id}', 'ServerCmdController@userList');
        Route::post('userAdd', 'ServerCmdController@userAdd');
        Route::get('userDel/{id}', 'ServerCmdController@userDel');
    });

    /**
     * 服务器管理
     */
    Route::group(['prefix'=>'server'], function () {
        Route::get('getList/{id?}', 'ServerController@getList');
        Route::post('addOrEdit', 'ServerController@addOrEdit');
        Route::get('del/{id}', 'ServerController@del');
    });
    Route::get('serverList', 'LinuxController@serverList');
    Route::post('serverAddOrEdit', 'LinuxController@serverAddOrEdit');
    Route::get('serverDel/{id}', 'LinuxController@serverDel');
    Route::get('serverInfo/{id}', 'LinuxController@serverInfo');

    /**
     * 命令管理
     */
    Route::group(['prefix'=>'cmd'], function () {
        Route::get('getList/{id?}', 'CmdController@getList');
        Route::post('addOrEdit', 'CmdController@addOrEdit');
        Route::get('del/{id}', 'CmdController@del');
    });
    Route::get('cmdList/{id?}', 'LinuxController@cmdList');
    Route::post('cmdAddOrEdit', 'LinuxController@cmdAddOrEdit');
    Route::get('cmdDel/{id}', 'LinuxController@cmdDel');
    Route::get('cmdRun/{id}', 'LinuxController@cmdRun');

    /**
     * 系统预设命令
     */
    Route::group(['prefix'=>'sysCmd'], function () {
        Route::get('getList/{id?}', 'SysCmdController@getList');
    });
    Route::get('sysCmdList/{id?}', 'LinuxController@sysCmdList');
});

Route::get('/ssh', function (){
//    dd(234);
    $commands = [
        'ls -l /var/www',
    ];
    SSH::into('local')->run($commands, function($line)
    {
        echo '<pre>';
        dd($line);
        echo $line.PHP_EOL;
    });
});

/*
 * auth
 */
//Route::group(['namespace'=>'Auth'], function(){
//    Route::get('login', 'AuthController@getLogin');
//    Route::post('login', 'AuthController@postLogin');
//    Route::get('logout', 'AuthController@logout');
//    Route::get('register', 'AuthController@getRegister');
//    Route::post('register', 'AuthController@postRegister');
//});

