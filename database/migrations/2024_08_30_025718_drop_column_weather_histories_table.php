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
        Schema::table('weather_histories', function (Blueprint $table) {
            // $table->dropColumn('city');
            // $table->dropColumn('temperature');
            // $table->dropColumn('wind_speed');
            // $table->dropColumn('humidity');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('weather_histories', function (Blueprint $table) {
            //
        });
    }
};
