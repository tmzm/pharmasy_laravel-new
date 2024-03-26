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
            $table->string('scientific_name');
            $table->string('slug')->unique();
            $table->string('commercial_name');
            $table->string('company_name');
            $table->boolean('is_offer')->default(false);
            $table->double('offer')->nullable();
            $table->text('description');
            $table->double('price');
            $table->double('quantity');
            $table->boolean('is_quantity')->default(false);
            $table->date('expiration');
            $table->string('image')->nullable();
            $table->string('meta_subtitle');
            $table->string('meta_title');
            $table->text('meta_description');
            $table->timestamps();
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
