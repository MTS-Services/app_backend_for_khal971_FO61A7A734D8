<?php

use App\Http\Traits\AuditColumnsTrait;
use App\Models\Question;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    use AuditColumnsTrait;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_index')->default(0);
            $table->unsignedBigInteger('question_details_id')->nullable()->index();
            $table->tinyInteger('status')->index()->default(Question::STATUS_ACTIVE);
            $table->timestamps();

            $this->addAuditColumns($table);

            $table->foreign('question_details_id')->references('id')->on('question_details')->nullOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
