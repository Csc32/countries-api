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
        if (!Schema::hasTable("zones")) {
            Schema::create('zones', function (Blueprint $table) {
                $table->id()->autoIncrement();
                $table->string("name", 100);
                $table->integer("population", false, false);
                $table->foreignId("parish_id")->constrained(
                    table: 'parish',
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
        if (Schema::hasTable('zones')) {
            Schema::dropIfExists('zones');
        }
    }
};
