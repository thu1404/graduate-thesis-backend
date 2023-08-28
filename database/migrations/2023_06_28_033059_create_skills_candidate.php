<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkillsCandidate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skills_cv', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->integer("cv_id")->nullable()->default(null);
            $table->integer("skill_id")->nullable()->default(null);
            // $table->integer("level")->nullable()->default(null);
            $table->integer("num_lever")->nullable()->default(null);
            $table->nullableTimestamps();

            // $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('skills_cv');
    }
}
