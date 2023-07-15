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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name',60);
            $table->string('description',255);
            $table->unsignedBigInteger('category_id'); // Unsigned Big Integer for foreign key
            $table->timestamps();

            // Foreign Key constraint
            $table->foreign('category_id')
                ->references('id')
                ->on('categories')  // assuming you have a categories table
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
