<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('secure_download_links', function (Blueprint $table) {
            $table->id();
            $table->string('payload_hash')->unique(); // Payload hash for unique identification
            $table->string('file_path');
            $table->string('type');
            $table->timestamp('expires_at');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedInteger('max_downloads')->nullable();
            $table->unsignedInteger('download_count')->default(0);
            $table->timestamp('last_download_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('secure_download_links');
    }
};
