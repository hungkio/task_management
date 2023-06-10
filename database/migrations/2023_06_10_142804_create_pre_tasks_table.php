<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreTasksTable extends Migration
{
    public function up()
    {
        Schema::create('pre_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('case');
            $table->string('path');
            $table->string('countRecord')->default(0);
            $table->string('customer');
            $table->string("level")->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
         Schema::dropIfExists('pre_tasks');
    }
}
