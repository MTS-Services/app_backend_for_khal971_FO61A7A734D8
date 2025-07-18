<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Http\Traits\AuditColumnsTrait;

return new class extends Migration {
    use AuditColumnsTrait;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_index')->default(0);

            $table->string('username')->unique()->nullable();
            $table->string('name')->nullable();
            $table->string('phone', 20)->unique();
            $table->string('email')->unique();
            $table->string('image')->nullable();

            $table->date('dob')->nullable();
            $table->tinyInteger('gender')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('school')->nullable();


            $table->boolean('is_premium')->default(false);
            $table->dateTime('premium_expires_at')->nullable();

            $table->timestamp('email_verified_at')->nullable();

            $table->string('otp', 4)->nullable();
            $table->dateTime('otp_sent_at')->nullable();
            $table->dateTime('otp_expires_at')->nullable();

            $table->string('password');

            $table->boolean('is_admin')->index()->default(false);
            $table->tinyInteger('status')->index()->default(User::STATUS_ACTIVE);

            $table->rememberToken();
            $table->timestamps();
            $this->addAuditColumns($table);

        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
