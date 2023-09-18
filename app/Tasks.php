<?php

namespace App;

use App\Domain\Admin\Models\Admin;
use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    public $guarded = [];
    protected $fillable = ['name', 'estimate', 'path', 'countRecord', 'date', 'month', 'case', 'customer', 'status', 'editor_id', 'QA_id', 'admin_id',
        'start_at', 'end_at', 'finish_path', 'QA_check_num', 'QA_note', 'redo', 'redo_note','QA_start','QA_end', 'level', 'estimate_QA', 'editor_check_num',
        'instruction', 'priority', 'dbcheck', 'dbcheck_num', 'excellent', 'customer_note'];
    const WAITING = 0; // 0:waiting; 1:editing; 2:testing; 3:done
    const EDITING = 1;
    const TESTING = 2;
    const DONE = 3; // khi QA check ok
    const REJECTED = 4; // khi QA check và thấy chưa đạt sẽ kéo về editing
    const TODO = 5; // khi Editor chưa sẵn sàng làm
    const FINISH = 6; // khi đã bàn giao cho khách
    const STATUS = [
        self::WAITING => 'Waiting',
        self::EDITING => 'Editing',
        self::TESTING => 'QA Check',
        self::DONE => 'Done Reject',
        self::REJECTED => 'Reject',
        self::TODO => 'Ready',
        self::FINISH => 'Finish',
    ];

    const CUSTOMER_STATUS = [
        self::WAITING => 'Editing',
        self::EDITING => 'Editing',
        self::TESTING => 'Testing',
        self::DONE => 'Done',
        self::REJECTED => 'Editing',
        self::TODO => 'Editing',
        self::FINISH => 'Done',
    ];

    const EXCELLENT = 1; // Case tốt ko bị reject
    const NOT_EXCELLENT = 0; // Case không tốt, bị reject

    public function QA()
    {
        return $this->belongsTo(Admin::class, 'QA_id');
    }

    public function editor()
    {
        return $this->belongsTo(Admin::class, 'editor_id');
    }

    public function checker()
    {
        return $this->belongsTo(Admin::class, 'dbcheck');
    }

    public function Customer() {
        return $this->belongsTo(Customers::class, 'customer', 'name');
    }
}
