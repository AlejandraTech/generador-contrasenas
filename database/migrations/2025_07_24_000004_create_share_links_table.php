<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('share_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vault_entry_id')->constrained()->onDelete('cascade');
            $table->string('token', 32)->unique();
            $table->string('recipient_hash')->nullable();
            $table->unsignedSmallInteger('max_views')->default(1);
            $table->unsignedSmallInteger('views')->default(0);
            $table->timestamp('expires_at');
            $table->timestamp('viewed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('share_links');
    }
};