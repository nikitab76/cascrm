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
        if (!Schema::hasTable('users')){
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->text('login')->nullable();
                $table->text('password')->nullable();
                $table->integer('aktiv')->default(0);
                $table->string('name')->nullable();
                $table->string('surname')->nullable();
                $table->string('second_name')->nullable();
                $table->string('phone')->nullable();
                $table->string('role')->nullable();
                $table->string('job_title')->nullable();
                $table->timestamps();
            });
        }

        Schema::table('users', function (Blueprint $table) {
            // Добавление новых столбцов
            if (!Schema::hasColumn('users', 'name')) {
                $table->string('name')->nullable()->after('aktiv');
            }

            if (!Schema::hasColumn('users', 'surname')) {
                $table->string('surname')->nullable()->after('name');
            }

            if (!Schema::hasColumn('users', 'second_name')) {
                $table->string('second_name')->nullable()->after('surname');
            }

            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->nullable()->after('second_name');
            }

            if (!Schema::hasColumn('users', 'job_title')) {
                $table->string('job_title')->nullable()->after('role');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('second_name');
            }
            if (Schema::hasColumn('users', 'login')) {
                $table->string('login')->nullable()->change();
            }
            if (Schema::hasColumn('users', 'password')) {
                $table->string('password')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    /*public function down(): void
    {
        Schema::dropIfExists('users');
    }*/
};
