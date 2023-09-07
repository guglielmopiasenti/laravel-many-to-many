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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50); // Project Name
            $table->string('github_url'); // GitHub URL
            $table->string('screenshot_path')->nullable(); // Screenshot File Path
            $table->text('description')->nullable(); // Project Description
            $table->string('technologies')->nullable(); // Technologies used
            $table->boolean('is_featured')->default(false); // Flag to identify if the project should be highlighted
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
