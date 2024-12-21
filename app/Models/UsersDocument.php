<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersDocument extends Model
{
    protected $fillable = [
        'user_id',
        'user_birth',
        'coach',
        'representative_phone',
        'representative',
        'medical_certificate',
        'nosology'];
}
