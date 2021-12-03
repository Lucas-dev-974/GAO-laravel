<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'horraire', 'date', 'user_id', 'id', 'computer_id'
    ];

    public function client(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function computer(){
        return $this->belongsTo(Computer::class, 'id', 'computer_id');
    }
}
