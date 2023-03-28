<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIdSetupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('id_setups', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('grade_level_id')->nullable();
            $table->string('type')->comment('1 student 2 employee');
            $table->string('branch_code');
            $table->string('id_front_path');
            $table->string('id_back_path');
            $table->string('back_content');
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
        Schema::dropIfExists('id_setup');
    }
}
