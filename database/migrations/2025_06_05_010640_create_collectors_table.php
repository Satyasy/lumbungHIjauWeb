<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_collectors_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collectors', function (Blueprint $table) {
            $table->id(); // bigint
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // FK ke users.id
            $table->text('assigned_area')->nullable();
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collectors');
    }
};