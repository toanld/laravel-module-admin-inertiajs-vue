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
        Schema::create("categories", function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('slug',170)->nullable()->index();
            $table->string('md5',40)->unique();
            $table->integer('length')->index()->default(0);
            $table->tinyInteger('level')->default(0);
            $table->integer('cat_1')->default(0)->index();
            $table->integer('cat_2')->default(0)->index();
            $table->integer('cat_3')->default(0)->index();
            $table->integer('cat_4')->default(0)->index();
            $table->integer('parent_id')->default(0)->index();
            $table->tinyInteger('has_child')->default(0)->index();
            $table->string('all_child')->nullable();
            $table->string('all_parent')->nullable();
            $table->text('description')->nullable();
            $table->text('pictures')->nullable();
            $table->bigInteger('show')->index()->default(0);
            $table->timestamps();
            $table->index('created_at');
            $table->index('updated_at');
            $table->index(['length','md5']);
        });
        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists("blog_categories");
    }
};
