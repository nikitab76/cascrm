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
        if (!Schema::hasTable('users_documents')) {
            Schema::create('users_documents', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id')->nullable();
                $table->string('user_birth')->nullable();
                $table->string('coach')->nullable();
                $table->string('medical_certificate')->nullable();
                $table->string('representative')->nullable();
                $table->string('representative_phone')->nullable();
                $table->string('nosology')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasColumn('users_documents', 'user_birth')) {
            Schema::table('users_documents', function (Blueprint $table) {
                $table->string('user_birth')->nullable()->after('user_id');
            });
        }

        if (!Schema::hasColumn('users_documents', 'representative_phone')) {
            Schema::table('users_documents', function (Blueprint $table) {
                $table->string('representative_phone')->nullable()->after('representative');
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
