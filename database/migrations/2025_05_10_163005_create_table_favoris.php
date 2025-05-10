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
        Schema::create('favoris', function (Blueprint $table) {
            $table->id('idFavori');
            $table->integer('idUser');
            $table->integer('idImmobilier');
            $table->foreign('idUser')->references('idUser')->on('utilisateurs');
            $table->foreign('idImmobilier')->references('idImmobilier')->on('bien_immobiliers');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favoris');
    }
};
