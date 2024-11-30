<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class traning_group extends Model
{
    use HasFactory;
    protected $fillable = ['coach_id', 'group_num', 'users_list'];
}
