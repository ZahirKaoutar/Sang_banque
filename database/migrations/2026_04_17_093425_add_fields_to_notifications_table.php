<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->string('blood_group_needed')->nullable()->after('center_id');
            $table->enum('donor_response', ['accepter', 'refuser'])->nullable()->after('blood_group_needed');
            $table->dateTime('responded_at')->nullable()->after('donor_response');
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['blood_group_needed', 'donor_response', 'responded_at']);
        });
    }
};
