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
      Schema::create('invoice_attachments', function (Blueprint $table) {
        $table->id();
        $table->string('fileName', 999);
        $table->string('invoiceNumber', 50);
        $table->string('Created_by', 999);
        $table->unsignedBigInteger('invoiceId')->nullable();
        $table->foreign('invoiceId')->references('id')->on('invoices')->onDelete('cascade');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_attachments');
    }
};
