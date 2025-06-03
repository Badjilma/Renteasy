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
        Schema::table('tenant_rooms', function (Blueprint $table) {
            $table->date('start_date')->after('room_id');
            $table->date('end_date')->after('start_date');
            $table->enum('status', ['active', 'inactive', 'expired'])->default('active')->after('end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenant_rooms', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date', 'status']);
        });
    }
};
