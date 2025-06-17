<?php

use App\Models\Property;
use App\Models\Room;
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
        Schema::table('mantenance_requests', function (Blueprint $table) {
             $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending')->after('description');
            $table->foreignIdFor(Property::class)->constrained()->after('tenant_id');
            $table->foreignIdFor(Room::class)->constrained()->after('property_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mantenance_requests', function (Blueprint $table) {
             $table->dropColumn(['status', 'property_id', 'room_id']);
        });
    }
};
