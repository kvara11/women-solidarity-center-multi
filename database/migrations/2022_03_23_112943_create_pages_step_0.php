<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesStep0 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages_step_0', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table -> string('alias_az') -> default('');
			$table -> string('alias_en') -> default('');
			$table -> string('alias_ar') -> default('');
			$table -> string('title_en') -> default('');
			$table -> string('title_az') -> default('');
			$table -> string('title_ar') -> default('');
			$table -> text('text_az') -> nullable();
			$table -> text('text_en') -> nullable();;
			$table -> text('text_ar') -> nullable();
			$table -> string('slug') -> default('');
			$table -> integer('like_default') -> default(0);
			$table -> string('meta_title_az') -> default('');
			$table -> string('meta_title_en') -> default('');
			$table -> string('meta_title_ar') -> default('');
			$table -> string('meta_description_az') -> default('');
			$table -> string('meta_description_en') -> default('');
			$table -> string('meta_description_ar') -> default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages_step_0');
    }
}
