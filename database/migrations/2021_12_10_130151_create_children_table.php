<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChildrenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('children', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('parent')->comment('親ID');
            $table->string('name', 255)->comment('氏名');
            $table->string('kana', 255)->comment('氏名ｶﾅ');
            $table->tinyInteger('sex')->comment('性別');
            $table->timestamp('birthday')->nullable()->comment('誕生日');
            $table->longText('comment')->comment('コメント');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('children');
    }
}
