<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropPublishedColumnFromNewsStep1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('news_step_1', function (Blueprint $table) {
            $table -> dropColumn('published');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('news_step_1', function (Blueprint $table) {
            $table -> integer('published') -> default(0);
        });
    }
}
