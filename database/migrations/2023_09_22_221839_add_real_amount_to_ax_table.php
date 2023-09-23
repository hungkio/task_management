<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRealAmountToAxTable extends Migration
{
    public function up()
    {
        Schema::table('ax', function (Blueprint $table) {
            $table->string('real_amount')->default(1);
        });
    }

    public function down()
    {
        Schema::table('ax', function (Blueprint $table) {
            $table->dropColumn('real_amount');
        });
    }
}
