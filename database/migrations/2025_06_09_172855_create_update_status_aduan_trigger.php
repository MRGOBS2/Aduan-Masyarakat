<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Trigger saat tanggapan ditambahkan
        DB::unprepared('
            CREATE TRIGGER update_status_aduan_after_insert
            AFTER INSERT ON tanggapans
            FOR EACH ROW
            BEGIN
                UPDATE aduans
                SET status = "selesai"
                WHERE id = NEW.aduan_id;
            END
        ');

        // Trigger saat tanggapan dihapus
        DB::unprepared('
            CREATE TRIGGER update_status_aduan_after_delete
            AFTER DELETE ON tanggapans
            FOR EACH ROW
            BEGIN
                UPDATE aduans
                SET status = "diproses"
                WHERE id = OLD.aduan_id;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS update_status_aduan_after_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS update_status_aduan_after_delete');    }
};
