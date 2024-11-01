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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->text('login');
            $table->text('password');
            $table->integer('aktiv')->default(0);
            $table->timestamps();
        });

        if (!Schema::hasColumn('users', 'aktiv')) {
            Schema::table('users', function (Blueprint $table) {
                $table->integer('aktiv')->after('password')->default(0);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    /*public function down(): void
    {
        Schema::dropIfExists('users');
    }*/
};
