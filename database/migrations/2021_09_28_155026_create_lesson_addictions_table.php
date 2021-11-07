<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonAddictionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_addictions', function (Blueprint $table) {
            $table->id();
            $table->integer("lessonId");
            $table->dateTime("date");
            $table->string("description");

            $table->foreign("lessonId")->references("id")->on("lessons")->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lesson_addictions');
    }
}
