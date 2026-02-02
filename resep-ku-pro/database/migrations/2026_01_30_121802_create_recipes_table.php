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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('outlet')->nullable();
            $table->string('image_url')->nullable();
            $table->string('category')->nullable();
            $table->text('method')->nullable();
            $table->decimal('selling_price', 15, 2);
            $table->foreignUuid('user_id')->constrained('users');
            $table->string('org_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
