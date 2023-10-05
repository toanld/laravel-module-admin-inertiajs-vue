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
        //
        Schema::table("blog_categories", function (Blueprint $table){
            if (!Schema::hasColumn($table->getTable(), 'left')) $table->integer('left')->unsigned()->default(0)->index();
            if (!Schema::hasColumn($table->getTable(), 'right')) $table->integer('right')->unsigned()->default(0)->index();
            $table->integer('parent_id')->unsigned()->nullable()->change();
            $table->index(array('left', 'right', 'parent_id'));
        });
        \Modules\Admin\Entities\Category::fixTree();
        \Modules\Admin\Entities\Category::whereNull('parent_id')->update(['parent_id'=>0]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
