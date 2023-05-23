<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeLevelAdminsTable extends Migration
{
    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->string('level')->nullable()->change();
            $table->boolean('is_online')->default(0);
        });
    }

    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->integer('level')->nullable()->change();
            $table->dropColumn('is_online');
        });
    }
}
