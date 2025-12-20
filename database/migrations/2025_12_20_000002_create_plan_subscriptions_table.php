<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tableName = config('subscription-plans.table_names.plan_subscriptions', 'plan_subscriptions');

        if (Schema::hasTable($tableName)) {
            return;
        }

        Schema::create($tableName, function (Blueprint $table) {
            $table->id();
            $table->morphs('subscriber');
            $table->unsignedBigInteger('plan_id')->nullable();
            $table->string('slug')->nullable();
            $table->json('name')->nullable();
            $table->json('description')->nullable();
            $table->string('subscription_type')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('cancels_at')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->string('timezone')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_paid')->default(false);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('plan_id');
            $table->index(['is_active', 'ends_at']);
            $table->index(['plan_id', 'is_active']);
            $table->index('trial_ends_at');
            $table->index('canceled_at');
            $table->index('slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('subscription-plans.table_names.plan_subscriptions', 'plan_subscriptions'));
    }
};
