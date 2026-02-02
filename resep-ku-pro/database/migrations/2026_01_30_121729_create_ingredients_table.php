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
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('purchase_price', 15, 2);
            $table->decimal('package_size', 10, 2);
            $table->string('unit'); // gr, ml, pcs
            $table->decimal('yield', 5, 2)->default(100);
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
        Schema::dropIfExists('ingredients');
    }
};
