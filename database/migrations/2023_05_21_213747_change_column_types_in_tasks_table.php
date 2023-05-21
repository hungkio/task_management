<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnTypesInTasksTable extends Migration
{
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->string('finish_path')->nullable()->change(); // đường dẫn file hoàn thành do editor đưa lên
            $table->integer('QA_check_num')->nullable()->change(); // số lượng ảnh checked
            $table->text('QA_note')->nullable()->change(); // note QA
            $table->boolean('redo')->nullable()->change(); // đánh dấu là công việc bị thất bại true/false
            $table->text('redo_note')->nullable()->change(); // đánh dấu là công việc bị thất bại
            $table->string("level")->nullable()->default(0)->change();
        });
    }
}
