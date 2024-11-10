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
        if (!Schema::hasTable('job_titles')){
            Schema::create('job_titles', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('role_name')->nullable();
                $table->timestamps();
            });
        }

        Schema::table('job_titles', function (Blueprint $table) {
            if (!Schema::hasColumn('job_titles', 'role_name')) {
                $table->string('role_name')->nullable()->after('name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    /*public function down(): void
    {
        Schema::dropIfExists('job_titles');
    }*/
};
