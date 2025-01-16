<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'description',
        'date_limite',
        'statut',
        'priorite',
        'projet_id',
        'user_id'
    ];

    public function projet()
    {
        return $this->belongsTo(Projet::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
                    ->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
