<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('typing_results', function (Blueprint $table) {
        $table->id();
        $table->string('username');
        $table->integer('wpm');
        $table->integer('accuracy');
        $table->integer('time_selected');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('typing_results');
    }
};
