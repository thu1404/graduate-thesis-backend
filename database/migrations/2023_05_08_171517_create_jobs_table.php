<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('title')->nullable()->default(null);
            $table->longText('requirements')->nullable()->default(null);
            $table->longText('description')->nullable()->default(null);
            $table->longText('benefit')->nullable()->default(null);
            $table->integer('hiring_process')->nullable()->default(null);
            $table->string('location')->nullable()->default(null);
            $table->integer('salary')->nullable()->default(null);
            $table->integer('user_id')->nullable()->default(null);
            $table->nullableTimestamps();
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
        Schema::dropIfExists('jobs');
    }
}
