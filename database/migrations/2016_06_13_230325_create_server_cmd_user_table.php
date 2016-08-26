<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServerCmdUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('server_cmd_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('server_cmd_id')->comment = "操作执行的 id";
            $table->text('pid')->comment = "命令拥有者 id";
            $table->text('child_id')->comment = "组用户 id";
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
        Schema::drop('server_cmd_users');
    }
}
