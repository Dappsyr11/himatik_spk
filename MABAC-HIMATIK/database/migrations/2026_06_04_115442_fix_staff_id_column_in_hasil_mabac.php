<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hasil_mabac', function (Blueprint $table) {
            $table->renameColumn('Staff_id', 'staff_id');
        });
    }

    public function down(): void
    {
        Schema::table('hasil_mabac', function (Blueprint $table) {
            $table->renameColumn('staff_id', 'Staff_id');
        });
    }
};