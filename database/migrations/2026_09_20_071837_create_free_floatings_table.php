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
        Schema::create('free_floatings', function (Blueprint $table) {
            $table->id();
            $table->text('fio');
            $table->text('phone');
            $table->text('nozologe');
            $table->text('date_spravka');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    /*public function down(): void
    {
        Schema::dropIfExists('free_floatings');
    }*/
};
