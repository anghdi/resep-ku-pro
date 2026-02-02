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
        Schema::create('user_permissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->string('org_id')->index();
            $table->string('module');

            // Hak Akses (Boolean)
            $table->boolean('can_create')->default(false);
            $table->boolean('can_read')->default(true);
            $table->boolean('can_update')->default(false);
            $table->boolean('can_delete')->default(false);

            $table->timestamps();
            $table->index(['user_id', 'module']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_permissions');
    }
};
