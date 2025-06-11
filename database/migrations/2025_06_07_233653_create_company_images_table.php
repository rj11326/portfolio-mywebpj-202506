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
        Schema::create('company_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('file_path');
            $table->integer('order')->default(1);
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unique(['company_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_images');
    }
};
