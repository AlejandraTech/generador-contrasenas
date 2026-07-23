<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vault_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->text('value');
            $table->string('title')->nullable();
            $table->string('site')->nullable();
            $table->string('username')->nullable();
            $table->text('notes')->nullable();
            $table->string('type')->default('password');
            $table->unsignedSmallInteger('length')->nullable();
            $table->boolean('include_special')->default(true);
            $table->boolean('include_numbers')->default(true);
            $table->boolean('include_uppercase')->default(true);
            $table->boolean('include_lowercase')->default(true);
            $table->unsignedInteger('entropy_bits')->nullable();
            $table->timestamps();
        });

        Schema::create('tag_vault_entry', function (Blueprint $table) {
            $table->foreignId('tag_id')->constrained()->onDelete('cascade');
            $table->foreignId('vault_entry_id')->constrained()->onDelete('cascade');
            $table->primary(['tag_id', 'vault_entry_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tag_vault_entry');
        Schema::dropIfExists('vault_entries');
    }
};