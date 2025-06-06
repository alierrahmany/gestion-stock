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
    Schema::table('users', function (Blueprint $table) {
        $table->enum('status', ['active', 'inactive'])
              ->default('active')
              ->change();
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->enum('status', [0, 1])
              ->default(1)
              ->change();
    });
}
};
