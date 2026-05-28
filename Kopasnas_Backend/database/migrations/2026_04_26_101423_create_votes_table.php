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
        Schema::create('votes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('anggota_id')
                ->constrained('anggotas')
                ->onDelete('cascade');

            $table->foreignId('voting_id')
                ->constrained('votings')
                ->onDelete('cascade');

            $table->foreignId('opsi_id')
                ->constrained('opsi_votings')
                ->onDelete('cascade');

            $table->timestamps();

            $table->unique(['anggota_id', 'voting_id']); // Each member can only vote once per voting question
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
