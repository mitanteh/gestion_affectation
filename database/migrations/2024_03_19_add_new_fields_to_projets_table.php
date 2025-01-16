<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projets', function (Blueprint $table) {
            // Ajout des nouveaux champs s'ils n'existent pas déjà
            if (!Schema::hasColumn('projets', 'taux_avancement')) {
                $table->integer('taux_avancement')->default(0);
            }
            if (!Schema::hasColumn('projets', 'date_glissement')) {
                $table->date('date_glissement')->nullable();
            }
            if (!Schema::hasColumn('projets', 'cause_glissement')) {
                $table->string('cause_glissement')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('projets', function (Blueprint $table) {
            $table->dropColumn('taux_avancement');
            $table->dropColumn('date_glissement');
            $table->dropColumn('cause_glissement');
        });
    }
}; 