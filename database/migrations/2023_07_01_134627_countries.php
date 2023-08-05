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
        if (!Schema::hasTable("countries")) {
            Schema::create('countries', function (Blueprint $table) {
                $table->id()->autoIncrement();
                $table->string("name", 100);
                $table->bigInteger("population", false, false);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::drop('flights');
        if (Schema::hasTable('countries')) {
            Schema::dropIfExists('countries');
        }
    }
};
