<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parents', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name', 255)->comment('氏名');
            $table->string('kana', 255)->comment('氏名ｶﾅ');
            $table->tinyInteger('sex')->comment('性別');
            $table->longText('comment')->comment('コメント');
            $table->string('zip', 255)->comment('郵便番号');
            $table->string('address', 255)->comment('住所');
            $table->string('tel', 255)->comment('電話番号');
            $table->string('email', 255)->comment('メールアドレス');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parents');
    }
}
