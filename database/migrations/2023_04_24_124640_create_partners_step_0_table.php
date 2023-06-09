<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartnersStep0Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partners_step_0', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title_en')->default('');
            $table->string('title_az')->default('');
            $table->string('title_ar')->default('');
            $table->string('link')->default('');
            $table->integer('rang')->default(0);
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
        Schema::dropIfExists('partners_step_0');
    }
}
