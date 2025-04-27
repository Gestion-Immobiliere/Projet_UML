<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->bigIncrements('id');

            // On force tokenable_type à 191 caractères pour rester sous 1000 octets max en utf8mb4
            $table->string('tokenable_type', 191);
            $table->unsignedBigInteger('tokenable_id');

            // On recrée l'index sur les deux colonnes
            $table->index(
                ['tokenable_type', 'tokenable_id'],
                'personal_access_tokens_tokenable_type_tokenable_id_index'
            );

            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
    }
};
