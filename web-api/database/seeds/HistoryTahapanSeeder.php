<?php

use Illuminate\Database\Seeder;
use App\HistoryTahapan;
use App\TahapanProyek;

class HistoryTahapanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $tahapans = TahapanProyek::all();

        foreach($tahapans as $tahapan) {
            $history_tahapan = new HistoryTahapan;

            $history_tahapan->username = 'admin';
            $history_tahapan->action = 'CREATE';
            $history_tahapan->id_tahapan = $tahapan->id;
            $history_tahapan->id_proyek = $tahapan->id_proyek;
            $history_tahapan->nama_tahapan = $tahapan->nama_tahapan;
            $history_tahapan->nama_kegiatan = $tahapan->nama_kegiatan;
            $history_tahapan->nama_sub_kegiatan = $tahapan->nama_sub_kegiatan;
            $history_tahapan->keterangan = $tahapan->keterangan;
            $history_tahapan->tanggal_info = $tahapan->tanggal_info;
            $history_tahapan->sumber_info = $tahapan->sumber_info;
            $history_tahapan->tindak_lanjut = $tahapan->tindak_lanjut;
            $history_tahapan->penanggung_jawab = $tahapan->penanggung_jawab;
            $history_tahapan->pihak_terkait = $tahapan->pihak_terkait;
            $history_tahapan->target_penyelesaian = $tahapan->target_penyelesaian;
            $history_tahapan->status = $tahapan->status;
            $history_tahapan->tanggal_selesai = $tahapan->tanggal_selesai;

            $history_tahapan->save();
        }
    }
}
