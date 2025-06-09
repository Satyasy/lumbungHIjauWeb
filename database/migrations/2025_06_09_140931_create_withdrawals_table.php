<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id(); // bigint UNSIGNED NOT NULL AUTO_INCREMENT
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Foreign key
            $table->decimal('amount', 10, 2); // decimal(10,2) NOT NULL
            $table->string('method'); // varchar(255) NOT NULL
            $table->string('virtual_account'); // varchar(255) NOT NULL
            $table->enum('status', ['pending', 'accepted', 'rejected', 'expired']); // enum NOT NULL
            $table->timestamp('expires_at')->nullable(); // timestamp NULL DEFAULT NULL
            $table->timestamps(); // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};