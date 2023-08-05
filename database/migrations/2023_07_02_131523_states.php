<?php

use App\Models\Countries;
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
        if (!Schema::hasTable("states")) {
            Schema::create('states', function (Blueprint $table) {
                $table->id()->autoIncrement();
                $table->string("name", 100);
                $table->mediumInteger("population", false, false);
                $table->foreignId("country_id")->constrained(
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
        if (Schema::hasTable('states')) {
            Schema::dropIfExists('states');
        }
    }
};
