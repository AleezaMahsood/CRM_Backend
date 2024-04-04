<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
                $table->id('campaign_id');
                $table->string('campaign_name', 150);
                $table->text('description');
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->string('expected_revenue');
                $table->string('actual_cost');
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaigns');
    }
};
