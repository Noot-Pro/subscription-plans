<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tableName     = config('subscription-plans.table_names.invoices', 'plan_invoices');
        $subscriberKey = config('subscription-plans.foreign_keys.subscriber_id', 'subscriber_id');

        if (Schema::hasTable($tableName)) {
            return;
        }

        Schema::create($tableName, function (Blueprint $table) use ($subscriberKey) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->unsignedBigInteger('subscription_id')->nullable();
            $table->unsignedBigInteger($subscriberKey);
            $table->decimal('amount', 10, 2);
            $table->decimal('tax', 10, 2)->default(0);
            $table->string('status')->default('new');
            $table->date('due_date')->nullable();
            $table->date('exp_date')->nullable();
            $table->boolean('paid')->default(false);
            $table->text('note')->nullable();
            $table->timestamps();

            // Indexes
            $table->index($subscriberKey);
            $table->index('subscription_id');
            $table->index('status');
            $table->index('paid');
            $table->index('due_date');
            $table->index('invoice_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('subscription-plans.table_names.invoices', 'plan_invoices'));
    }
};
