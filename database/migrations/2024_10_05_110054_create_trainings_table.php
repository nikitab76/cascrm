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
        Schema::create('trainings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug_room');
            $table->string('coach');
            $table->string('profile');
            $table->string('date');
            $table->string('time_start');
            $table->string('time_end');
            $table->string('quarter');
            $table->string('comment');
            $table->timestamps();
        });

        if (!Schema::hasColumn('trainings', 'comment')) {
            Schema::table('trainings', function (Blueprint $table) {
                $table->string('comment')->after('quarter');
            });
        }
        if (!Schema::hasColumn('trainings', 'time_end')) {
            Schema::table('trainings', function (Blueprint $table) {
                $table->string('time_end')->after('time_start');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};
