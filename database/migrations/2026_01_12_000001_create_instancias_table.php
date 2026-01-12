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
        Schema::create('instancias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nome', 100);
            $table->string('telefone', 20)->nullable();
            $table->string('token', 255)->unique();
            $table->enum('status', ['online', 'offline', 'connecting', 'qr_pending'])
                  ->default('offline');
            $table->text('qr_code')->nullable();
            $table->timestamp('last_connected_at')->nullable();
            $table->json('configuracoes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'status']);
            $table->index('token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instancias');
    }
};
