<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_waste_categories_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('waste_categories', function (Blueprint $table) {
            $table->id(); // bigint, primary key, auto increment
            $table->string('name', 191);
            $table->enum('type', ['Organik', 'Anorganik']);
            $table->decimal('price_per_kg', 10, 2);
            $table->timestamps(); // created_at, updated_at (timestamp NN by default)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('waste_categories');
    }
};