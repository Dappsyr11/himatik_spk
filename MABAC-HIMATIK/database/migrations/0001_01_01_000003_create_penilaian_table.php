<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel penilaian Staff per kriteria per periode
        Schema::create('penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('Staff_id')->constrained('Staff')->onDelete('cascade');
            $table->foreignId('kriteria_id')->constrained('kriteria')->onDelete('cascade');
            $table->string('periode');
            $table->string('label_nilai');  // Sangat Baik, Baik, dst
            $table->integer('nilai');       // 1-5 (dari skala_penilaian)
            $table->timestamps();

            $table->unique(['Staff_id', 'kriteria_id', 'periode']);
        });

        // Tabel hasil perhitungan MABAC per periode per divisi
        Schema::create('hasil_mabac', function (Blueprint $table) {
            $table->id();
            $table->foreignId('Staff_id')->constrained('Staff')->onDelete('cascade');
            $table->foreignId('divisi_id')->constrained('divisi')->onDelete('cascade');
            $table->string('periode');
            $table->decimal('nilai_akhir', 10, 6); // Nilai Si MABAC
            $table->integer('peringkat');
            $table->boolean('terbaik')->default(false);
            $table->json('detail_perhitungan')->nullable(); // store intermediate steps
            $table->timestamps();

            $table->unique(['Staff_id', 'periode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_mabac');
        Schema::dropIfExists('penilaian');
    }
};