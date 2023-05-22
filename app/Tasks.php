<?php

namespace App;

use App\Domain\Admin\Models\Admin;
use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    public $guarded = [];
    protected $fillable = ['name', 'estimate', 'path', 'countRecord', 'date', 'month', 'case', 'customer', 'status', 'editor_id', 'QA_id', 'admin_id',
        'start_at', 'end_at', 'finish_path', 'QA_check_num', 'QA_note', 'redo', 'redo_note','QA_start','QA_end', 'level'];
    const WAITING = 0; // 0:waiting; 1:editing; 2:testing; 3:done
    const EDITING = 1;
    const TESTING = 2;
    const DONE = 3;
    const REJECTED = 4; // khi QA check và thấy chưa đạt sẽ kéo về editing
    const STATUS = [
        self::WAITING => 'Chưa giao',
        self::EDITING => 'Đang edit',
        self::TESTING => 'Đang kiểm tra',
        self::DONE => 'Hoàn thành',
        self::REJECTED => 'Từ chối',
    ];

    public function QA()
    {
        return $this->belongsTo(Admin::class, 'QA_id');
    }

    public function editor()
    {
        return $this->belongsTo(Admin::class, 'editor_id');
    }
}
