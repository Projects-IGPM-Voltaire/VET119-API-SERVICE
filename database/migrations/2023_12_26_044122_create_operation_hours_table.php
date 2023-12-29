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
        Schema::create('operation_hours', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('health_center_id');
            $table->time('time_from')->default('08:00:00');
            $table->time('time_to')->default('18:00:00');
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
        Schema::dropIfExists('operation_hours');
    }
};
