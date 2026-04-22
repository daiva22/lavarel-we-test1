<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            if (Schema::hasColumn('services', 'service_group')) {
                $table->dropColumn('service_group');
            }

            $table->unsignedBigInteger('service_category_id')->nullable()->after('id');

            $table->foreign('service_category_id')
                ->references('id')
                ->on('service_categories')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['service_category_id']);
            $table->dropColumn('service_category_id');
            $table->string('service_group')->nullable()->after('name');
        });
    }
};