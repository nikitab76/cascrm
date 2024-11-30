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
        if(!Schema::hasTable('traning_groups')){
            Schema::create('traning_groups', function (Blueprint $table) {
                $table->id();
                $table->string('coach_id');
                $table->string('group_num')->nullable();
                $table->string('users_list');
                $table->timestamps();
            });
        }

        Schema::table('traning_groups', function (Blueprint $table) {
            // Добавление новых столбцов
            if (!Schema::hasColumn('traning_groups', 'group_num')) {
                $table->string('group_num')->nullable()->after('coach_id');
            }
        });

    }

    /**
     * Reverse the migrations.
     */
    /*public function down(): void
    {
        Schema::dropIfExists('traning_groups');
    }*/
};
