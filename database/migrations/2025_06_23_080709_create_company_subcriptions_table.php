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
        Schema::create('company_subcriptions', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->integer('package_id');
            $table->enum('payment_mode', ['CASH', 'UPI', 'CREDIT CARD', 'DEBIT CARD', 'NETBANKING', 'CHEQUE', 'DEMAND DRAFT'])->nullable();
            $table->float('payment_amount', 10, 2)->default(0.00);
            $table->longText('txn_id')->nullable();
            $table->longText('licence_no')->nullable();
            $table->string('start_date', 250)->nullable();
            $table->string('end_date', 250)->nullable();
            $table->longText('comment')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->softDeletes();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate(); // Auto-updates on change
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_subcriptions');
    }
};
