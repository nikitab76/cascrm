<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MagazineVisits extends Model
{
    protected $fillable = ['user', 'profile', 'profile', 'on_visit', 'coach', 'date'];
}
