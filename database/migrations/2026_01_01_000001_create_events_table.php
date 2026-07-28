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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('location');
            $table->string('google_maps_url')->nullable();
            $table->string('cover_image')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->dateTime('registration_start_date')->nullable();
            $table->dateTime('registration_end_date')->nullable();
            $table->integer('capacity')->default(100);
            $table->string('event_type')->default('Physical'); // Physical, Virtual, Hybrid
            $table->string('status')->default('Draft'); // Draft, Published, Closed, Finished
            $table->json('custom_fields')->nullable(); // Configurable dynamic registration form fields
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
