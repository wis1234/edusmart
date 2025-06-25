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
        Schema::create('video_calls', function (Blueprint $table) {
            $table->id();
            $table->string('room_id')->unique(); // Identifiant unique de la salle d'appel
            $table->string('title')->nullable(); // Titre de l'appel
            $table->text('description')->nullable(); // Description de l'appel
            $table->enum('type', ['video', 'audio', 'both'])->default('both'); // Type d'appel
            $table->enum('status', ['pending', 'active', 'ended', 'cancelled'])->default('pending');
            $table->foreignId('initiator_id')->constrained('users')->onDelete('cascade'); // Utilisateur qui initie l'appel
            $table->foreignId('school_id')->nullable()->constrained('schools')->onDelete('cascade'); // École associée
            $table->json('participants')->nullable(); // Liste des participants (JSON)
            $table->timestamp('started_at')->nullable(); // Heure de début
            $table->timestamp('ended_at')->nullable(); // Heure de fin
            $table->integer('duration')->nullable(); // Durée en secondes
            $table->json('settings')->nullable(); // Paramètres de l'appel (enregistrement, etc.)
            $table->timestamps();
            
            $table->index(['room_id', 'status']);
            $table->index(['initiator_id', 'status']);
            $table->index(['school_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_calls');
    }
}; 