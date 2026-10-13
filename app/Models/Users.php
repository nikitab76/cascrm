<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Users extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'name',
        'surname',
        'second_name',
        'job_title',
        'role',
        'phone',
        'login',
        'password'
    ];
    public function fullName()
    {
        return trim($this->surname . ' ' .  $this->name . ' ' . $this->second_name);
    }

    public function shortName()
    {
        return trim($this->surname . ' ' . mb_substr($this->name, 0, 1) . '. ' . mb_substr($this->second_name, 0, 1) . '.');
    }
}
