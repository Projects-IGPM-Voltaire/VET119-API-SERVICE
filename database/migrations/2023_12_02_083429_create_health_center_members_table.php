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
        Schema::create('health_center_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('health_center_id');
            $table->unsignedBigInteger('user_id');
            $table->string('position')->default('staff');
            $table->timestamps();

            $table
                ->foreign('health_center_id')
                ->references('id')
                ->on('health_centers')
                ->onDelete('cascade');
            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_center_members');
    }
};
