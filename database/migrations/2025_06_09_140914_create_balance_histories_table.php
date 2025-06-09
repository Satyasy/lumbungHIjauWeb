<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('balance_histories', function (Blueprint $table) {
            $table->id(); // bigint UNSIGNED NOT NULL AUTO_INCREMENT
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Foreign key
            $table->decimal('amount', 10, 2); // decimal(10,2) NOT NULL
            $table->foreignId('transaction_id')->nullable()->constrained('transactions')->onDelete('set null'); // Foreign key
            $table->timestamp('timestamp'); // timestamp NOT NULL (ini berbeda dari created_at/updated_at)
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('balance_histories');
    }
};