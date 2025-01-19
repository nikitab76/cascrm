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
        if (!Schema::hasTable('magazine_visits')) {
            Schema::create('magazine_visits', function (Blueprint $table) {
                $table->id();
                $table->string('user');
                $table->string('profile');
                $table->string('date');
                $table->boolean('on_visit')->default(false);
                $table->string('coach');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    /*public function down(): void
    {
        Schema::dropIfExists('magazine_visits');
    }*/
};
