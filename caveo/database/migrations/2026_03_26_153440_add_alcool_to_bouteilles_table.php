<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bouteilles', function (Blueprint $table) {
            $table->decimal('alcool', 5, 2)->nullable()->after('millesime');
        });
    }

    public function down(): void
    {
        Schema::table('bouteilles', function (Blueprint $table) {
            $table->dropColumn('alcool');
        });
    }
};
