<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStylesToCustomersTable extends Migration
{
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->text('styles')->nullable();
        });
    }
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('styles');
        });
    }
}
