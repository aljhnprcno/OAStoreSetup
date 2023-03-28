<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportedFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exported_files', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('employee_id')->nullable();
            $table->string('branch_code')->nullable();
            $table->string('file_name')->nullable();
            $table->string('date')->nullable();
            $table->string('url', 3000);
            $table->integer('isOpened')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exported_files');
    }
}
