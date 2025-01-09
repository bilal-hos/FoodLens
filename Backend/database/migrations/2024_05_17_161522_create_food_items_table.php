<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Plate;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('food_items', function (Blueprint $table) {
            $table->id();
            $table->string('food_name');
            $table->string('cal_per_g');
            $table->string('fat_per_g');
            $table->string('carb_per_g');
            $table->string('protein_per_g');
            $table->string('components')->nullable();
            $table->foreignIdFor(Plate::class)->constrained()->cascadeOnUpdate()->cascadwOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_items');
    }
};
