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
        if (!Schema::hasTable('free_floatings')){
            Schema::create('free_floatings', function (Blueprint $table) {
                $table->id();
                $table->text('fio');
                $table->text('phone');
                $table->text('nozologe');
                $table->text('date_spravka')->nullable();
                $table->timestamps();
            });
        }

        Schema::table('free_floatings', function (Blueprint $table) {

            if (!Schema::hasColumn('free_floatings', 'birthday')) {
                $table->string('birthday')->nullable()->after('fio');
            }

            if (!Schema::hasColumn('free_floatings', 'mail')) {
                $table->string('mail')->nullable()->after('nozologe');
            }

            if (!Schema::hasColumn('free_floatings', 'personal_data_consent')) {
                $table->boolean('personal_data_consent')->after('date_spravka');
            }

            if (!Schema::hasColumn('free_floatings', 'training_id')) {
                $table->string('training_id')->nullable()->after('date_spravka');
            }
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
