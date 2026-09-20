<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class freeFloatingModel extends Model
{
    use HasFactory;
    protected $table = 'free_floatings';
    protected $fillable = ['fio', 'phone', 'nozologe', 'date_spravka'];
}
