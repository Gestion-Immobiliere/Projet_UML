<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
      Schema::create('images', function (Blueprint $table) {
    $table->id();
    $table->unsignedInteger('bien_id');
    $table->string('chemin');
    $table->timestamps();

    // Tu commentes la contrainte si vraiment nécessaire :
    // $table->foreign('bien_id')->references('idImmobilier')->on('bien_immobiliers')->onDelete('cascade');
});


    }

    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
