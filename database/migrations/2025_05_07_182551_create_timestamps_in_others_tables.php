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
        Schema::table('avis', function (Blueprint $table) {
            $table->timestamps();
        });

        Schema::table('paiements', function (Blueprint $table) {
            $table->timestamps();
        });

        Schema::table('contrats', function (Blueprint $table) {
            $table->timestamps();
        });

        Schema::table('bien_immobiliers', function (Blueprint $table) {
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('avis', function (Blueprint $table) {
            $table->dropColumn(['created_at', 'updated_at']);
        });
    
        Schema::table('contrats', function (Blueprint $table) {
            $table->dropColumn(['created_at', 'updated_at']);
        });
    
        Schema::table('paiements', function (Blueprint $table) {
            $table->dropColumn(['created_at', 'updated_at']);
        });
        
        Schema::table('bien_immobiliers', function (Blueprint $table) {
            $table->dropColumn(['created_at', 'updated_at']);
        });
    }
};
