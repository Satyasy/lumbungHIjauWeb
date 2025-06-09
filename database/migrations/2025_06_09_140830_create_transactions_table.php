<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id(); // bigint UNSIGNED NOT NULL AUTO_INCREMENT
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Foreign key
            $table->string('pickup_location')->nullable(); // varchar(255) DEFAULT NULL
            $table->decimal('total_weight', 10, 2)->nullable(); // decimal(10,2) DEFAULT NULL
            $table->decimal('total_price', 10, 2)->nullable(); // decimal(10,2) DEFAULT NULL
            $table->string('image_path')->nullable(); // varchar(255) DEFAULT NULL
            $table->enum('status', ['cart', 'pending', 'verified', 'rejected'])->default('cart'); // enum NOT NULL DEFAULT 'cart'
            $table->text('rejection_reason')->nullable(); // text DEFAULT NULL
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};