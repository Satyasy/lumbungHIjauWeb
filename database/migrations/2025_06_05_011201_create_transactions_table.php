<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_transactions_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id(); // bigint
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // User yang request
            $table->foreignId('collector_id')->nullable()->constrained('collectors')->onDelete('set null'); // Collector yang mengambil
            $table->text('pickup_location');
            $table->decimal('total_weight', 8, 2)->nullable();
            $table->decimal('total_price', 10, 2)->nullable();
            $table->enum('status', ['pending', 'verified', 'rejected', 'completed', 'cancelled'])->default('pending'); // Tambahkan status lain jika perlu
            $table->string('verification_token', 64)->nullable()->unique();
            $table->timestamp('token_expires_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};