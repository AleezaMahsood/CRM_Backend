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
        //
        Schema::dropIfExists('articles');
        Schema::dropIfExists('categories');
       // Schema::dropIfExists('category_article');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
