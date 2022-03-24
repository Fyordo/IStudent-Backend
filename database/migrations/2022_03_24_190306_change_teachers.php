<?php

use App\Models\Lesson;
use App\Models\Teacher;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTeachers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('lessons', function (Blueprint $table) {
            $table->integer("teacher_id")->nullable();
        });

        foreach (Lesson::all() as $lesson) {
            if ($lesson->lecturer != ""){
                $teacher_id = Teacher::where("name", $lesson->lecturer)->first()->id;
                $lesson->teacher_id = $teacher_id;
                $lesson->save();
            }
        }

        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn("lecturer");
        });

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
}
