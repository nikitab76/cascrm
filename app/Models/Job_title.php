<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job_title extends Model
{
    use HasFactory;

    static function getRole($title)
    {
        return self::where('name', $title)->value('role_name');
    }
}
