<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreTasks extends Model
{
    protected $fillable = ['name', 'path', 'countRecord','case', 'customer', 'level'];
    public $guarded = [];
}
