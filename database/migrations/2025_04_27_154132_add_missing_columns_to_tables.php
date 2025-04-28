<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumnsToTables extends Migration
{
    public function up()
    {

        // Ajouter les colonnes aux biens immobiliers
        Schema::table('bien_immobiliers', function (Blueprint $table) {
            if (!Schema::hasColumn('bien_immobiliers', 'titre')) {
                $table->string('titre')->after('idImmobilier');
            }
            if (!Schema::hasColumn('bien_immobiliers', 'description')) {
                $table->text('description')->after('titre');
            }
            if (!Schema::hasColumn('bien_immobiliers', 'image')) {
                $table->string('image')->nullable()->after('description');
            }
            if (!Schema::hasColumn('bien_immobiliers', 'localisation')) {
                $table->string('localisation')->after('image');
            }
            if (!Schema::hasColumn('bien_immobiliers', 'statut')) {
                $table->enum('statut', ['disponible', 'vendu', 'reserve'])->default('disponible')->after('localisation');
            }
        });
    }
    

    public function down()
    {
        // Rollback des colonnes ajoutées
        Schema::table('bien_immobiliers', function (Blueprint $table) {
            $table->dropColumn(['titre', 'description', 'image', 'localisation', 'statut']);
        });
    }
}
