<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tableName = config('subscription-plans.table_names.plan_features', 'plan_features');

        if (Schema::hasTable($tableName)) {
            return;
        }

        Schema::create($tableName, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plan_id')->nullable();
            $table->string('code')->nullable();
            $table->json('name');
            $table->json('description')->nullable();
            $table->string('value')->nullable();
            $table->unsignedSmallInteger('resettable_period')->default(0);
            $table->string('resettable_interval')->default('month');
            $table->unsignedMediumInteger('sort_order')->default(0);
            $table->decimal('price', 10, 2)->default(0.00);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('plan_id');
            $table->index(['plan_id', 'code']);
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('subscription-plans.table_names.plan_features', 'plan_features'));
    }
};
