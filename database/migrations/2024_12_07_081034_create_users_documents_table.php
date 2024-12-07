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
        if (!Schema::hasTable('users_documents')){
            Schema::create('users_documents', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id')->nullable();
                $table->string('coach')->nullable();
                $table->string('medical_certificate')->nullable();
                $table->string('representative')->nullable();
                $table->string('nosology')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    /*public function down(): void
    {
        Schema::dropIfExists('users_documents');
    }*/
};
