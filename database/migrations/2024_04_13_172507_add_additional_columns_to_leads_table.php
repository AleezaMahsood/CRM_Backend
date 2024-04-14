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
        Schema::table('leads', function (Blueprint $table) {
        $table->string('job_title')->nullable();
        $table->string('mobile')->nullable();
        $table->string('whatsapp')->nullable();
        $table->string('source')->nullable();
        $table->string('industry')->nullable();
        $table->string('company')->nullable();
        $table->string('email')->nullable();
        $table->string('fax')->nullable();
        $table->string('website')->nullable();
        $table->string('status')->nullable();
        $table->integer('employees')->nullable();
        $table->string('rating')->nullable();
        $table->integer('revenue')->nullable();
        $table->string('skype')->nullable();
        $table->string('remarks')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            //
        });
    }
};
