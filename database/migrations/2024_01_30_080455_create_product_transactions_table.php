<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->index()
                ->constrained('products')
                ->cascadeOnDelete();

            $table->string('description')->nullable(); // exmplo: "Usuário {$x} {$type} {$y} unidades."

            $table->string('type')->default('sale');

            $table->unsignedInteger('quantity');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_transactions');
    }
};
