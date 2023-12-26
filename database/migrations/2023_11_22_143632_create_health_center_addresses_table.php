<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('health_center_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('health_center_id');
            $table->string('city_code');
            $table
                ->foreign('city_code')
                ->references('code')
                ->on('cities')
                ->onDelete('cascade');
            $table->string('barangay_code');
            $table
                ->foreign('barangay_code')
                ->references('code')
                ->on('barangays')
                ->onDelete('cascade');
            $table->string('house_number')->nullable();
            $table->string('street')->nullable();
            $table->text('map_url')->nullable();
            $table->timestamps();

            $table
                ->foreign('health_center_id')
                ->references('id')
                ->on('health_centers')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_center_addresses');
    }
};
