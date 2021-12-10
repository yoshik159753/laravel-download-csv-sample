<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChildToClassTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('child_to_class', function (Blueprint $table) {
            $table->timestamps();
            $table->unsignedInteger('child_id')->comment('子ID');
            $table->unsignedInteger('class_id')->comment('クラスID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('child_to_class');
    }
}
