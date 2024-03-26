<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use OrderTransactionsEnum;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->index()
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->string('billing_type');

            $table->string('charge_id');

            $table->float('value', 10);
            $table->date('due_date');
            $table->string('description')->nullable();
            $table->string('status')->default(OrderTransactionsEnum::PENDING);

            $table->string('pix_url')->nullable();
            $table->longText('pix_qrcode')->nullable();

            $table->unsignedInteger('installment_count')->default(0);
            $table->float('installment_value', 10)->nullable();

            $table->string('remote_ip')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_transactions');
    }
};
