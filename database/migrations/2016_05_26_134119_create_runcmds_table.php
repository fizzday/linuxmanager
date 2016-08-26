<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRuncmdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('server_cmds', function (Blueprint $table) {
            $table->increments('id');
            $table->string('actname', 32)->comment = "执行操作的名字,如:重启测试服务器";
            /**
             * 具体的命令
             * 示例:
             */
//            $server_cmd = [
//                ['key'=>'server', 'value'=>1],
//                ['key'=>'cmd', 'value'=>2]
//            ];
            $table->text('server_cmd')->default("")->comment = "具体的操作命令, 如上边注释的格式, json存储";
            $table->text('children')->default("")->comment = "组内用户的邮箱, json格式";
            $table->integer('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('server_cmds');
    }
}
