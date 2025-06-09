<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('waste_categories', function (Blueprint $table) {
            $table->id(); // bigint UNSIGNED NOT NULL AUTO_INCREMENT
            $table->string('name'); // varchar(255) NOT NULL
            $table->enum('type', ['organic', 'inorganic']); // enum NOT NULL
            $table->decimal('price_per_kg', 10, 2); // decimal(10,2) NOT NULL
            $table->string('image_path')->nullable(); // varchar(255) DEFAULT NULL
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('waste_categories');
    }
};