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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->longText('name');
            $table->longText('phone');
            $table->longText('alternate_phone');
            $table->longText('email');
            $table->longText('alternate_email');
            $table->longText('address');
            $table->longText('contact_person');
            $table->string('logo', 100);
            $table->longText('description');
            $table->string('industry_id', 100);
            $table->string('no_of_employee', 100);
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
        Schema::dropIfExists('companies');
    }
};
