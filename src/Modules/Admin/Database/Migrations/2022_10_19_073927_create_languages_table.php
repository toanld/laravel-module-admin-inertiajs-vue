<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('languages')) {
            Schema::create('languages', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->longText('json_default')->nullable();
                $table->longText('json_translate')->nullable();
                $table->timestamps();
                $table->engine = "InnoDB";
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('languages');
    }
}
