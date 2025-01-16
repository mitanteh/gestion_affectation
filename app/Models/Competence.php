<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competence extends Model
{
    use HasFactory;

    protected $fillable = [
        'lib_comp',
        'statut'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'competence_user');
    }

}
