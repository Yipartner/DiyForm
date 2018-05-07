<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->jsonb('attributes');
            /*
                {
                    "1":{
                        "required" : 1,
                        "type" : "input",
                        "name" : "姓名"，

                    },
                    "2":{
                        "required" : 1
                        "type" : "text",
                        "name" : "自我介绍"
                    }
                }

            */
            $table->tinyInteger('status'); // 0:未开启1:进行中2:结束3废弃
            $table->string('url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forms');
    }
}
