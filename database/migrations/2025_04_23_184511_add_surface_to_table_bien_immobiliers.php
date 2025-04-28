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
        Schema::table('bien_immobiliers', function (Blueprint $table) {
            $table->decimal('surface', 12, 2)->nullable()->after('montant');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bien_immobiliers', function (Blueprint $table) {
            $table->dropColumn('surface');
        });
    }
};
