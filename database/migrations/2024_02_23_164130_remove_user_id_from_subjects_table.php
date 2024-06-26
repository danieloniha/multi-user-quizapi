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
    Schema::table('subjects', function (Blueprint $table) {
        // Drop the foreign key constraint
        $table->dropForeign(['user_id']);
        
        // Drop the user_id column
        $table->dropColumn('user_id');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->bigInteger('user_id')->after('co_lecturer');
        });
    }
};
