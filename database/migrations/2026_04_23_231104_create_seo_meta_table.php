<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Per-route SEO config (home, blog index, contact, etc.)
        Schema::create('seo_meta', function (Blueprint $table) {
            $table->id();
            $table->string('route_key')->unique();
            $table->string('title');
            $table->string('description', 500)->nullable();
            $table->string('keywords')->nullable();
            $table->string('og_image')->nullable();
            $table->string('canonical_url')->nullable();
            $table->boolean('noindex')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_meta');
    }
};
