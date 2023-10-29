<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomerForWorkToAdminsTable extends Migration
{
    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->string('customer_for_work')->nullable();
        });
    }
    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('customer_for_work');
        });
    }
}
