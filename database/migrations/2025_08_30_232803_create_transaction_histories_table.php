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
        Schema::create('transaction_histories', function (Blueprint $table) {
            $table->id();
            $table->timestamp('transaction_date')->nullable(); // Combined tranDate and time
            $table->decimal('amount', 10, 2); // Amount from API
            $table->string('user_id'); // Contains user_id
            $table->string('code')->nullable(); // Code from API
            $table->string('refId'); // Reference ID
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_histories');
    }
};
