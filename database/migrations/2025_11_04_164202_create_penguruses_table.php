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
        Schema::create('penguruses', function (Blueprint $table) {
            $table->id('id_pengurus');
            $table->string('nama_pengurus', 50);
            $table->string('email_pengurus')->unique();
            $table->string('password_pengurus');
            $table->timestamps();
        });
    }
    /** 
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penguruses');
    }
};
