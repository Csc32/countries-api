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
        if (!Schema::hasTable("parish")) {
            Schema::create('parish', function (Blueprint $table) {
                $table->id()->autoIncrement();
                $table->string("name", 100);
                $table->mediumInteger("population", false, false);
                $table->foreignId("municipality_id")->constrained(
                    table: 'municipalities',
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
        if (Schema::hasTable('parish')) {
            Schema::dropIfExists('parish');
        }
    }
};
