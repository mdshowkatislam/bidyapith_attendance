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
    Schema::table('groups', function (Blueprint $table) {
        $table->integer('flexible_in_time')->nullable()->after('status');
        $table->integer('flexible_out_time')->nullable()->after('flexible_in_time');
    });
}

public function down()
{
    Schema::table('groups', function (Blueprint $table) {
        $table->dropColumn(['flexible_in_time', 'flexible_out_time']);
    });
}
};
