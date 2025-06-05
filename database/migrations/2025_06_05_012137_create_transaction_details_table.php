<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_transaction_details_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id(); // bigint
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('waste_categories')->onDelete('restrict'); // FK ke waste_categories.id
            $table->decimal('estimated_weight', 8, 2);
            $table->decimal('actual_weight', 8, 2)->nullable();
            $table->decimal('price_per_kg_at_transaction', 10, 2)->nullable(); // Harga saat transaksi
            $table->string('photo_path', 191)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
    }
};