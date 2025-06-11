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
        Schema::create('company_applications', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('company_email');
            $table->text('company_description')->nullable();
            $table->string('contact_name');
            $table->string('contact_email');
            $table->string('contact_phone')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->dateTime('rejected_at')->nullable();
            $table->unsignedBigInteger('admin_user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_applications');
    }
};
