<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // panitia pemilik acara
            $table->string('title');
            $table->text('description');
            $table->string('category', 30);
            $table->string('location');
            $table->string('organizer');
            $table->date('event_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedInteger('quota');
            $table->timestamps();

            $table->index(['event_date', 'category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
