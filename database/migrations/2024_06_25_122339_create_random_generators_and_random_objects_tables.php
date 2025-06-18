<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('random_generators', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('hash', 20)->nullable()->default(null);
            $table->text('description')->nullable()->default(null);
            $table->text('parsed_description')->nullable()->default(null);
            $table->integer('sort')->default(0);
            $table->boolean('is_active')->default(1);
            $table->boolean('has_image')->default(0);
        });
        Schema::create('random_objects', function (Blueprint $table) {
            $table->id();
            $table->string('text');
            $table->string('link')->nullable()->default(null);
            $table->integer('random_generator_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('random_generators');
        Schema::dropIfExists('random_objects');
    }
};
