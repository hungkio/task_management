<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShareLinkCollumnToPreTasksTable extends Migration
{
    public function up()
    {
        Schema::table('pre_tasks', function (Blueprint $table) {
            $table->string('share_link')->nullable();
        });
    }

    public function down()
    {
        Schema::table('pre_tasks', function (Blueprint $table) {
            $table->dropColumn('share_link');
        });
    }
}
