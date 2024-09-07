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
        Schema::create('invoices', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->string('invoiceNumber', 50);
          $table->date('invoiceDate')->nullable();
          $table->date('dueDate')->nullable();
          $table->string('product', 50);
          $table->bigInteger( 'sectionId' )->unsigned();
          $table->foreign('sectionId')->references('id')->on('sections')->onDelete('cascade');
          $table->decimal('amountCollection',8,2)->nullable();;
          $table->decimal('amountCommission',8,2);
          $table->decimal('discount',8,2);
          $table->decimal('valueVAT',8,2);
          $table->string('rateVAT', 999);
          $table->decimal('total',8,2);
          $table->string('status', 50);
          $table->integer('valueStatus');
          $table->text('note')->nullable();
          $table->date('paymentDate')->nullable();
          $table->softDeletes();
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
