<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_class_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_class_id')->nullable()->index();
            $table->string('language', 5)->index();
            $table->string('name')->unique();
            $table->timestamps();

            $table->foreign('user_class_id')->references('id')->on('user_classes')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_class_translations');
    }
};
