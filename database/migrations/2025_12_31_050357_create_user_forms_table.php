<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Anonymous class for migration
return new class extends Migration {

    // Run the migration: create the table
    public function up(): void
    {
        Schema::create('user_forms', function (Blueprint $table) {
            $table->id();              // Primary key column: id
            $table->string('name');    // Column for user's name
            $table->string('email');   // Column for user's email
            $table->string('password');// Column for user's password
            $table->timestamps();      // Adds created_at and updated_at columns
        });
    }

    // Reverse the migration: drop the table
    public function down(): void
    {
        Schema::dropIfExists('user_forms');
    }
};
