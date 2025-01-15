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
        Schema::table('users', function (Blueprint $table) {
            // Ajout du champ 'role'
            $table->string('role')->default('client')->nullable()->after('password');
            
            // Ajout du champ 'phone_number'
            $table->string('phone_number')->nullable()->after('role');
            
            // Ajout du champ 'address'
            $table->text('address')->nullable()->after('phone_number');
            
            // Ajout du champ 'email_verified'
            $table->boolean('email_verified')->default(false)->after('email_verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
