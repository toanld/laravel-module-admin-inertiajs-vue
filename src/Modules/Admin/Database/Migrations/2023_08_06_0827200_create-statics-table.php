<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statics', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('slug',170)->nullable()->index();
            $table->string('md5',40)->index();
            $table->integer('length')->index()->default(0);
            $table->longText('description')->nullable();
            $table->tinyInteger('status')->index()->default(1);
            $table->string('lang',5)->default('vi')->nullable()->index();
            $table->timestamps();
            $table->index('created_at');
            $table->index('updated_at');
            $table->index(['length','md5']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('ecommerce.table_prefix') . 'statics');
        //
    }
};
