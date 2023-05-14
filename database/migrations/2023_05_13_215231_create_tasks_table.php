<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('estimate')->nullable();
            $table->string('path');
            $table->string('countRecord')->default(0);
            $table->string('date');
            $table->string('month');
            $table->string('case');
            $table->string('customer');
            $table->string('status')->default('0'); // 0:waiting; 1:editing; 2:testing; 3:done
            $table->string('editor_id')->nullable();
            $table->string('QA_id')->nullable();
            $table->string('admin_id')->nullable();
            $table->timestamp('start_at')->nullable(); // thời điểm bắt đầu
            $table->timestamp('end_at')->nullable(); // thời điểm hoàn thành
            $table->timestamp('finish_path')->nullable(); // đường dẫn file hoàn thành do editor đưa lên
            $table->timestamp('QA_check_num')->nullable(); // số lượng ảnh checked
            $table->timestamp('QA_note')->nullable(); // note QA
            $table->timestamp('redo')->nullable(); // đánh dấu là công việc bị thất bại true/false
            $table->timestamp('redo_note')->nullable(); // đánh dấu là công việc bị thất bại
            $table->timestamps();
        });
    }

    public function down()
    {
         Schema::dropIfExists('tasks');
    }
}
