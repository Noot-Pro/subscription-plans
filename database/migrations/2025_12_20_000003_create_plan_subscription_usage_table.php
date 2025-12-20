<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tableName = config('subscription-plans.table_names.plan_subscription_usage', 'plan_subscription_usage');

        if (Schema::hasTable($tableName)) {
            return;
        }

        Schema::create($tableName, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subscription_id')->nullable();
            $table->unsignedBigInteger('feature_id')->nullable();
            $table->unsignedBigInteger('used')->default(0);
            $table->timestamp('valid_until')->nullable();
            $table->string('timezone')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('subscription_id');
            $table->index('feature_id');
            $table->index(['subscription_id', 'feature_id']);
            $table->index('valid_until');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('subscription-plans.table_names.plan_subscription_usage', 'plan_subscription_usage'));
    }
};
