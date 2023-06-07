<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEditorNumToTasksTable extends Migration
{
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->integer('editor_check_num')->nullable();
        });
    }
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('editor_check_num');
        });
    }
}

