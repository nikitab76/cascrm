<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersDocument extends Model
{
    protected $fillable = ['user_id',  'coach', 'medical_certificate', 'medical_certificate', 'nosology'];
}
