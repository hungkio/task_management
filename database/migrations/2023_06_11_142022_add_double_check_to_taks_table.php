<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDoubleCheckToTaksTable extends Migration
{
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->bigInteger('dbcheck')->default(0);
            $table->integer('dbcheck_num')->default(0);
        });
    }

    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('dbcheck');
            $table->dropColumn('dbcheck_num');
        });
    }
}
