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
        Schema::create('telegram_bots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nome');
            $table->string('username')->unique();
            $table->string('token')->unique();
            $table->string('bot_id')->nullable();
            $table->text('descricao')->nullable();
            $table->string('foto_url')->nullable();
            $table->boolean('ativo')->default(true);
            $table->boolean('webhook_ativo')->default(false);
            $table->string('webhook_url')->nullable();
            $table->timestamp('ultimo_uso')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telegram_bots');
    }
};
