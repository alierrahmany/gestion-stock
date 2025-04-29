<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // First, modify the column to VARCHAR to avoid conflicts
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->change();
        });

        // Then set it as ENUM with the correct values
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'gestionnaire', 'magasin') NOT NULL DEFAULT 'magasin'");
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->change();
        });
    }
};
