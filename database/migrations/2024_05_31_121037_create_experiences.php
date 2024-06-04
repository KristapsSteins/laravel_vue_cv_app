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
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resume_id');
            $table->string('job_title');
            $table->string('company_name');
            $table->json('job_description');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_current_job')->default(false);
            $table->boolean('is_remote')->default(false);
            $table->timestamps();
            
            $table->foreign('resume_id')->references('id')->on('resumes')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experiences');
    }
};
