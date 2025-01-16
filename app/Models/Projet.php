<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Projet extends Model
{
    protected $fillable = [
        'nom_projet',
        'description_projet',
        'date_deb',
        'date_fin',
        'date_glissement',
        'cause_glissement',
        'pourcentage_avancement',
        'etat_projet',
        'type_projet',
        'taux_avancement'
    ];

    protected $casts = [
        'date_deb' => 'datetime',
        'date_fin' => 'datetime',
        'date_glissement' => 'datetime',
        'pourcentage_avancement' => 'integer'
    ];

    public function competences()
    {
        return $this->belongsToMany(Competence::class, 'projet_competence');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'projet_user')
                    ->withTimestamps();
    }

    // Méthode pour obtenir les utilisateurs éligibles
    public function getEligibleUsers()
    {
        return User::whereHas('competences', function ($query) {
            $query->whereIn('competences.id', $this->competences->pluck('id'));
        })->get();
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
