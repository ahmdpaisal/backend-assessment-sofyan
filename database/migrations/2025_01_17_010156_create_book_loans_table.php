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
        Schema::create('book_loans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('book_id')->constrained('books', 'id');
            $table->foreignUuid('member_id')->constrained('members', 'id');
            $table->date('loan_date');
            $table->date('estimated_return');
            $table->date('return_date')->nullable();
            $table->string('status');
            $table->integer('forfeit')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users', 'id');
            $table->timestamps();
            $table->softDeletesTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_loans');
    }
};
