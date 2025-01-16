<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom_user',
        'prenom_user',
        'email',
        'password',
        'role_id',
        'statut'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function conges()
    {
        return $this->hasMany(Conge::class);
    }

    public function competences()
    {
        return $this->belongsToMany(Competence::class, 'competence_user');
    }

    // Liste des utilisateurs pour lesquels ce User est un backup
    public function backupsFor()
    {
        return $this->belongsToMany(User::class, 'backups', 'backup_id', 'user_id');
    }

    // Liste des backups pour ce User
    public function backups()
    {
        return $this->belongsToMany(User::class, 'backups', 'user_id', 'backup_id');
    }

    /**
     * Assigne un backup réciproque.
     */
    public function assignMutualBackup(User $backupUser)
    {
        // Vérifie si la relation existe déjà
        if (!$this->backups()->where('backup_id', $backupUser->id)->exists()) {
            $this->backups()->attach($backupUser->id); // Ajoute le backup
        }

        // Ajoute la relation dans l'autre sens
        if (!$backupUser->backups()->where('backup_id', $this->id)->exists()) {
            $backupUser->backups()->attach($this->id); // Ajoute le backup mutuel
        }
    }

    // Relation avec les projets
    public function projets()
    {
        return $this->belongsToMany(Projet::class, 'projet_user')
                    ->withTimestamps()
                    ->withPivot(['created_at', 'updated_at']); // spécifiez ici uniquement les colonnes qui existent
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}