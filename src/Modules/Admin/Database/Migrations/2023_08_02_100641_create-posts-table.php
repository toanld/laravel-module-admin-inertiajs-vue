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
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('slug',170)->nullable()->index();
            $table->string('md5',40)->index();
            $table->integer('length')->index()->default(0);
            $table->text('meta')->nullable();
            $table->text('teaser')->nullable();
            $table->longText('description')->nullable();
            $table->integer('cat_1')->index()->default(0);
            $table->integer('cat_2')->index()->default(0);
            $table->integer('cat_3')->index()->default(0);
            $table->integer('cat_4')->index()->default(0);
            $table->integer('use_id')->index()->default(0);
            $table->timestamps();
            $table->index('created_at');
            $table->index('updated_at');
            $table->index(['length','md5']);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_posts');
        //
    }
};
