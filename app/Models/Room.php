<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['title' , 'slug'];
    public function getClients(){
        return $this->hasMany(Client::class);
    }
}
