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
        Schema::create('barangs', function (Blueprint $table) {
            $table->id('id_barang');
            $table->unsignedBigInteger('id_pengurus');   
            $table->string('nama_barang', 50);
            $table->string('jumlah_barang', 50);
            $table->date('expired_barang');
            $table->timestamp('created_at');
            $table->timestamp('updated_at') ->nullable();


            $table->foreign('id_pengurus')->references('id_pengurus')->on('penguruses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
