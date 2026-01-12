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
        Schema::create('logs_de_mensagens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instancia_id')->constrained('instancias')->onDelete('cascade');
            $table->enum('tipo', ['enviada', 'recebida', 'webhook', 'erro']);
            $table->string('telefone_destino', 20);
            $table->text('mensagem');
            $table->json('payload')->nullable();
            $table->enum('status', ['pendente', 'enviada', 'entregue', 'lida', 'erro'])
                  ->default('pendente');
            $table->string('message_id', 100)->nullable();
            $table->text('erro_detalhes')->nullable();
            $table->timestamps();

            $table->index(['instancia_id', 'tipo']);
            $table->index(['instancia_id', 'created_at']);
            $table->index('telefone_destino');
            $table->index('message_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs_de_mensagens');
    }
};
