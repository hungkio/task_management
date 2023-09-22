<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeadLineToTasksTable extends Migration
{
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->string('editor_spend')->nullable();
            $table->string('QA_spend')->nullable();
            $table->string('deadline')->nullable();
        });
    }

    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('editor_spend');
            $table->dropColumn('QA_spend');
            $table->dropColumn('deadline');
        });
    }
}
