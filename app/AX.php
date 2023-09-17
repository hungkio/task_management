<?php

namespace App;

use App\Domain\Admin\Models\Admin;
use Illuminate\Database\Eloquent\Model;

class AX extends Model
{
    protected $table = 'ax';
    public $guarded = [];
    protected $fillable = ['name', 'cost', 'priority', 'estimate_QA', 'estimate_editor'];

}
