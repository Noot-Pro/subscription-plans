<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tableName = config('subscription-plans.table_names.invoice_transactions', 'plan_invoice_transactions');

        if (Schema::hasTable($tableName)) {
            return;
        }

        Schema::create($tableName, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('invoice_id');
            $table->index('status');
            $table->index('payment_method');
            $table->index('transaction_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('subscription-plans.table_names.invoice_transactions', 'plan_invoice_transactions'));
    }
};
