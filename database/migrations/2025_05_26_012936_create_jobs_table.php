<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('job_category_id')->constrained('job_categories')->onDelete('cascade');
            $table->foreignId('location_id')->nullable()->constrained('locations')->onDelete('set null');
            $table->string('title');
            $table->string('location')->nullable();
            $table->unsignedInteger('salary_min');
            $table->unsignedInteger('salary_max');
            $table->tinyInteger('employment_type')->default(1);
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->text('welcome_skills')->nullable();
            $table->text('required_qualifications')->nullable();
            $table->text('tools')->nullable();
            $table->text('selection_flow')->nullable();
            $table->text('required_documents')->nullable();
            $table->string('interview_place')->nullable();
            $table->text('benefits')->nullable();
            $table->string('work_time')->nullable();
            $table->string('holiday')->nullable();
            $table->integer('number_of_positions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_closed')->default(false);
            $table->date('application_deadline')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
