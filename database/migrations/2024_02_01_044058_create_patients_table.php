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
        Schema::create('patients', function (Blueprint $table) {
            $table->uuid('id')->primary(); 
            $table->string('name');
            $table->integer('age');
            $table->string('address');
            $table->enum('gender', ['Laki-laki', 'Perempuan']);
            $table->decimal('systolic_blood_pressure')->nullable();
            $table->decimal('diastolic_blood_pressure')->nullable();
            $table->enum('blood_glucose_type', ['GDP', 'GDS'])->nullable();
            $table->decimal('blood_glucose')->nullable();
            $table->decimal('uric_acid')->nullable();
            $table->decimal('cholesterol')->nullable();
            $table->integer('table_number');
            $table->boolean('is_printed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
