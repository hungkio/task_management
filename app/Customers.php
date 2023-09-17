<?php

namespace App;

use App\Domain\Admin\Models\Admin;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    public $guarded = [];
    protected $fillable = ['name', 'ax'];

}
