<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAxTable extends Migration
{
    public function up()
    {
        Schema::create('ax', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('cost');
            $table->string('priority');
            $table->string('estimate_QA');
            $table->string('estimate_editor');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ax');
    }
}
