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
        Schema::create('mensagens_agendadas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instancia_id')->constrained('instancias')->onDelete('cascade');
            $table->string('telefone_destino', 20);
            $table->text('mensagem');
            $table->json('midia')->nullable();
            $table->timestamp('agendado_para');
            $table->enum('status', ['agendada', 'processando', 'enviada', 'erro', 'cancelada'])
                  ->default('agendada');
            $table->text('erro_detalhes')->nullable();
            $table->integer('tentativas')->default(0);
            $table->timestamps();

            $table->index(['status', 'agendado_para']);
            $table->index('instancia_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mensagens_agendadas');
    }
};
