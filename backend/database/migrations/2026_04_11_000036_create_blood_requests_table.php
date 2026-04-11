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
    {   Schema::create('blood_requests', function (Blueprint $table) {
        $table->id();


    $table->foreignId('hopital_id')->constrained('hopitals')->onDelete('cascade');
    $table->foreignId('center_id')->constrained('centers')->onDelete('cascade');


    $table->integer('quantity_needed');

    $table->enum('blood_group', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']);

    $table->enum('priority', ['Normal', 'Urgent'])
          ->default('Normal');

    $table->enum('status', ['pending', 'Fulfilled', 'Canceled'])
          ->default('pending');

    $table->timestamps();
});
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_requests');
    }
};
