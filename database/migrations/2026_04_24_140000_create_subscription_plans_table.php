<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('slug', 120)->unique();
            $table->string('tagline', 160)->nullable();
            $table->text('description')->nullable();

            $table->decimal('price_monthly', 10, 2)->default(0);
            $table->decimal('price_yearly',  10, 2)->default(0);
            $table->string('currency', 3)->default('INR');
            $table->unsignedSmallInteger('save_percent')->nullable();

            // [{text: "...", included: true|false}, ...]
            $table->json('features')->nullable();

            $table->string('cta_label', 60)->default('Register');
            $table->string('cta_url', 255)->nullable();

            $table->boolean('is_active')->default(true);
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_free_trial')->default(false);
            $table->unsignedSmallInteger('free_trial_days')->nullable();

            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
