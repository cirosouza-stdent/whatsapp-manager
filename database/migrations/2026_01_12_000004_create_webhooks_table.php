<?php

declare(strict_types=1);

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
        Schema::create('webhooks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instancia_id')->constrained('instancias')->onDelete('cascade');
            $table->string('evento', 50);
            $table->json('payload');
            $table->enum('status', ['recebido', 'processado', 'erro'])->default('recebido');
            $table->text('resposta')->nullable();
            $table->timestamps();

            $table->index(['instancia_id', 'evento']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhooks');
    }
};
