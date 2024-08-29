<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {

            $table->id();

            $table->string('document', 14)->unique();

            $table->string('ie', 30)->unique()->nullable();

            $table->string('registered_name');

            $table->string('name_company');

            $table->string('telephone', 120)->nullable();

            $table->string('email', 254)->nullable()->unique();

            $table->integer('status')->default(1);

            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
