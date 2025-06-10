<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_users_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // bigint UNSIGNED NOT NULL AUTO_INCREMENT
            $table->string('name'); // varchar(255) NOT NULL
            $table->string('email')->unique(); // varchar(255) NOT NULL UNIQUE
            $table->string('phone_number')->nullable(); // varchar(255) NOT NULL
            $table->string('password'); // varchar(255) NOT NULL
            $table->string('avatar')->nullable(); // varchar(255) DEFAULT NULL
            $table->enum('role', ['user', 'collector', 'admin'])->default('user'); // enum NOT NULL DEFAULT 'user'
            $table->decimal('balance', 10, 2)->default(0.00); // decimal(10,2) NOT NULL DEFAULT '0.00'
            $table->boolean('email_verified')->default(false); // tinyint(1) NOT NULL DEFAULT '0'
            $table->string('otp_code')->nullable(); // varchar(255) DEFAULT NULL
            $table->timestamp('otp_expires_at')->nullable(); // timestamp NULL DEFAULT NULL
            $table->rememberToken(); // varchar(100) DEFAULT NULL
            $table->timestamps(); // created_at, updated_at timestamp NULL DEFAULT NULL
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};