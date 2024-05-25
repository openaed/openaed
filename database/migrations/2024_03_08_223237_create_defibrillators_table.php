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
        Schema::create('defibrillators', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('osm_id')->unique();
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->string('access')->nullable();
            $table->boolean('indoor')->nullable();
            $table->string('operator')->nullable();
            $table->string('operator_website')->nullable();
            $table->string('phone')->nullable();
            $table->string('location')->nullable();
            $table->string('opening_hours')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('model')->nullable();
            $table->string('level')->nullable();
            $table->string('image')->nullable();
            $table->string('cabinet')->nullable();
            $table->string('cabinet_manufacturer')->nullable();
            $table->string('note')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('defibrillators');
    }
};
