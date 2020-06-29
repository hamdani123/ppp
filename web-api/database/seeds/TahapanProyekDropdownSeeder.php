<?php

use Illuminate\Database\Seeder;

use App\KegiatanProyekDropdown;
use App\TahapanProyekDropdown;

class TahapanProyekDropdownSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $tahapans = ['Perencanaan', 'Penyiapan', 'Transaksi', 'Implementasi'];
        $kegiatans1 = array(
            array('Identifikasi Proyek', 'Penganggaran KPBU', 'Studi Pendahuluan', 'Konsultasi Publik', 'Pengusulan Proyek'), 
            array('Kajian Awal Prastudi Kelayakan (OBC)', 'Proses Persetujuan Project Development Facilities (PDF)', 'Kajian Akhir Prastudi Kelayakan (FBC)', 'Proses Persetujuan Viability Gap Fund (VGF)', 'Proses Persetujuan Availability Payment (AP)', 'Screening Penjaminan', 'Appraisal Penjaminan'), 
            array('Pra-kualifikasi', 'Request for Proposal', 'Structuring Penjaminan', 'Penetapan Pemenang', 'Proses Persetujuan Viability Gap Fund (VGF)', 'Proses Persetujuan Availability Payment (AP)', 'Penandatanganan Kerjasama', 'Financial Close'), 
            array('Konstruksi', 'Operasi')
        );

        $kegiatans2 = array(
            array('Penyampaian Pre-FS', 'Evaluasi Pre-FS', 'Persetujuan Pre-FS'), 
            array('Penyampaian FS', 'Evaluasi FS', 'Persetujuan FS', 'Proses Persetujuan Availability Payment (AP)', 'Screening Penjaminan', 'Appraisal Penjaminan'), 
            array('Pra-kualifikasi', 'Request for Proposal', 'Structuring Penjaminan', 'Penetapan Pemenang', 'Proses Persetujuan Viability Gap Fund (VGF)', 'Proses Persetujuan Availability Payment (AP)', 'Penandatanganan Kerjasama', 'Financial Close'), 
            array('Konstruksi', 'Operasi')
        );

        $i = 0;

        foreach($tahapans as $tahapan) {
            $obj_tahapan = new TahapanProyekDropdown;
            $obj_tahapan->tahapan = $tahapan;
            $obj_tahapan->tipe = 'Solicited';

            $obj_tahapan->save();

            foreach($kegiatans1[$i] as $kegiatan) {
                $obj_kegiatan = new KegiatanProyekDropdown;
                $obj_kegiatan->kegiatan = $kegiatan;
                $obj_kegiatan->id_tahapan = $obj_tahapan->id;

                $obj_kegiatan->save();
            }

            $i++;
        }

        $i = 0;

        foreach($tahapans as $tahapan) {
            $obj_tahapan = new TahapanProyekDropdown;
            $obj_tahapan->tahapan = $tahapan;
            $obj_tahapan->tipe = 'Unsolicited';

            $obj_tahapan->save();

            foreach($kegiatans2[$i] as $kegiatan) {
                $obj_kegiatan = new KegiatanProyekDropdown;
                $obj_kegiatan->kegiatan = $kegiatan;
                $obj_kegiatan->id_tahapan = $obj_tahapan->id;

                $obj_kegiatan->save();
            }

            $i++;
        }
        
    }
}
