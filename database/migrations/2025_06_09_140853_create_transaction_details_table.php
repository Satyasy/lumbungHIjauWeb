<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id(); // bigint UNSIGNED NOT NULL AUTO_INCREMENT
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade'); // Foreign key
            $table->foreignId('category_id')->constrained('waste_categories')->onDelete('cascade'); // Foreign key (SQL dump mengatakan cascade)
            $table->integer('estimated_weight'); // int NOT NULL
            $table->integer('actual_weight')->nullable(); // int DEFAULT NULL
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
    }
};