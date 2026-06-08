<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kriteria', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->enum('tipe', ['benefit', 'cost'])->default('benefit');
            $table->decimal('bobot', 5, 4)->default(0); // ← langsung di sini
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        Schema::create('skala_penilaian', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->integer('nilai');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // bobot_kriteria sudah tidak perlu, dihapus
    }

    public function down(): void
    {
        Schema::dropIfExists('skala_penilaian');
        Schema::dropIfExists('kriteria');
    }
};