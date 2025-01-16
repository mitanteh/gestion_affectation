<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
            Schema::create('projets', function (Blueprint $table) {
                $table->id();
                $table->string('nom_projet');
                $table->longText('description_projet');
                $table->date('date_deb');
                $table->date('date_fin');
                $table->string('etat_projet'); // Ex. "A démarrer", "En cours", "Terminé", "Annulé"
                $table->string('type_projet'); // Ex. "Projet", "Chantier"
                $table->integer('taux_avancement')->default(0); // Nouveau champ pour le %
                $table->date('date_glissement')->nullable();
                $table->string('cause_glissement')->nullable();
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projets');
    }
};
