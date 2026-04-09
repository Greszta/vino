<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Effectue la mise à jour de la table celliers.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('celliers', function (Blueprint $table) {
            /**
             * Rend les champs description et emplacement facultatifs.
             */
            $table->text('description')->nullable()->change();
            $table->string('emplacement', 55)->nullable()->change();

            /**
             * Ajoute les colonnes timestamps de Laravel.
             */
            $table->timestamps();
        });

        Schema::table('celliers', function (Blueprint $table) {
            /**
             * Empêche un même utilisateur d'avoir deux celliers portant le même nom.
             */
            $table->unique(['id_utilisateur', 'nom'], 'celliers_id_utilisateur_nom_unique');
        });
    }

    /**
     * Annule la mise à jour de la table celliers.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('celliers', function (Blueprint $table) {
            /**
             * Supprime la contrainte unique ajoutée dans up().
             */
            $table->dropUnique('celliers_id_utilisateur_nom_unique');
        });

        Schema::table('celliers', function (Blueprint $table) {
            /**
             * Retire les timestamps.
             */
            $table->dropTimestamps();

            /**
             * Remet les colonnes comme avant.
             */
            $table->text('description')->nullable(false)->change();
            $table->string('emplacement', 55)->nullable(false)->change();
        });
    }
};
