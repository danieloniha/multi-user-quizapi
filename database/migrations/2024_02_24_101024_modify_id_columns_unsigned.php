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
        Schema::table('subject_user', function (Blueprint $table) {
            $table->bigInteger('subject_id')->unsigned()->change();
            $table->bigInteger('user_id')->unsigned()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subject_user', function (Blueprint $table) {
            $table->bigInteger('subject_id')->change();
            $table->bigInteger('user_id')->change();
        });
    }
};
