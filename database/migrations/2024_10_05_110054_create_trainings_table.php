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
        if (!Schema::hasTable('trainings')) {
            Schema::create('trainings', function (Blueprint $table) {
                $table->increments('id');
                $table->string('slug_room')->default(null);
                $table->string('coach')->default(null);
                $table->string('profile')->default(null);
                $table->string('date')->default(null);
                $table->string('time_start')->default(null);
                $table->string('time_end')->default(null);
                $table->string('quarter')->default(null);
                $table->string('comment')->default(null);
                $table->timestamps();
            });
        }

        if (!Schema::hasColumn('trainings', 'comment')) {
            Schema::table('trainings', function (Blueprint $table) {
                $table->string('comment')->after('quarter')->default(null);
            });
        }
        if (!Schema::hasColumn('trainings', 'time_end')) {
            Schema::table('trainings', function (Blueprint $table) {
                $table->string('time_end')->after('time_start')->default(null);
            });
        }

    }

    /**
     * Reverse the migrations.
     */
    /*public function down(): void
    {
        Schema::dropIfExists('trainings');
    }*/
};
