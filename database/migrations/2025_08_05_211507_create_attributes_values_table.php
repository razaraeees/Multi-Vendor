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
        Schema::create('attributes_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attribute_id'); // FK to attributes
            $table->string('value'); // e.g., Red, Blue, XL
            $table->tinyInteger('status')->default(1); // 1 = active, 0 = inactive
            $table->timestamps();

            $table->foreign('attribute_id')
                  ->references('id')
                  ->on('attributes')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attributes_values');
    }
};
