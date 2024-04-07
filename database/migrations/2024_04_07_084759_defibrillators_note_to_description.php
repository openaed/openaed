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
        Schema::table('defibrillators', function (Blueprint $table) {
            // rename the column 'note' to 'description'
            $table->renameColumn('note', 'description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('defibrillators', function (Blueprint $table) {
            // rename the column 'description' to 'note'
            $table->renameColumn('description', 'note');
        });
    }
};