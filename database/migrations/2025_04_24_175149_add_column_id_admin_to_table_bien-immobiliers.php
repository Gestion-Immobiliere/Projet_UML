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
            $table->integer('idAdmin')->unsigned()->after('idAgent');
            $table->foreign('idAdmin')->references('idAdmin')->on('administrateurs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bien_immobiliers', function (Blueprint $table) {
            $table->dropForeign(['idAdmin']);  
            $table->dropColumn('idAdmin');  
        });
    }
};
