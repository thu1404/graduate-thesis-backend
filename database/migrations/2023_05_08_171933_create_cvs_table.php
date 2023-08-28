<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCvsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cvs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->integer('creator_id')->nullable()->default(null);
            $table->string('name', '191')->nullable()->default(null);
            $table->string('avatar', '191')->nullable()->default(null);
            $table->integer('age')->nullable()->default(null);
            $table->integer('gender_id')->nullable()->default(null);
            $table->string('phone', '191')->nullable()->default(null);
            $table->string('email', '191')->nullable()->default(null);
            $table->string('address', '191')->nullable()->default(null);
            $table->string('education', '191')->nullable()->default(null);
            $table->string('experience', '191')->nullable()->default(null);
            $table->string('skills', '191')->nullable()->default(null);
            $table->string('cv_files', '191')->nullable()->default(null);
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
        Schema::dropIfExists('cvs');
    }
}
