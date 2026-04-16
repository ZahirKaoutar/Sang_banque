<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();


            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->foreignId('center_id')
                  ->constrained('centers')
                  ->onDelete('cascade');


            $table->dateTime('donation_date');
            $table->enum('test_result', ['accepted', 'rejected'])->default('accepted');
    $table->string('observed_blood_group')->nullable();
            $table->text('medical_notes')->nullable();

            $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
