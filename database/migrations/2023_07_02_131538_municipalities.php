<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use NunoMaduro\Collision\Adapters\Phpunit\State;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        if (!Schema::hasTable("municipalities")) {
            Schema::create('municipalities', function (Blueprint $table) {
                $table->id()->autoIncrement();
                $table->string("name", 100);
                $table->mediumIncrements("population", false, false);
                $table->foreignId("state_id")->constrained(
                    table: 'countries',
                    indexName: 'id'
                )->onUpdate("cascade");
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        if (Schema::hasTable('municipalities')) {
            Schema::dropIfExists('municipalities');
        }
    }
};
