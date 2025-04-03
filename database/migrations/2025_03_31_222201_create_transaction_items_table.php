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
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained();
            $table->string('model_type');
            $table->bigInteger('model_id');
            $table->string('description');
            $table->bigInteger('qty');
            $table->bigInteger('amount');
            $table->bigInteger('total_amount');
            $table->bigInteger('vat_amount');
            $table->string('currency');
            $table->foreignId('vat_id')->constrained('vat_rates');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_items');
    }
};
