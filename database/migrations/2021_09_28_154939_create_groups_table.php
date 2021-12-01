<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->integer("groupNumber");
            $table->integer("groupCourse");
            $table->integer("headmanId");
            $table->bigInteger("directionId");

            $table->foreign("headmanId")->references("id")->on("students")->onDelete("cascade");
            $table->foreign("directionId")->references("id")->on("directions")->onDelete("cascade");
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('groups');
    }
}
