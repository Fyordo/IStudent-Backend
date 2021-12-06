<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HashPasswords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $students = \App\Models\Student::all();
        foreach ($students as $student){
            $student->update([
                "password" => base64_encode($student["password"])
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $students = \App\Models\Student::all();
        foreach ($students as $student){
            $student->update([
                "password" => base64_decode($student["password"])
            ]);
        }
    }
}
