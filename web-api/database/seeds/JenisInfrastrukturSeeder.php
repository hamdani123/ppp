<?php

use Illuminate\Database\Seeder;
use App\JenisInfrastruktur;

class JenisInfrastrukturSeeder extends Seeder

{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $jenis_infrastrukturs = ['Transportasi', 'Konservasi Energi', 'Jalan', 'Fasilitas Perkotaan', 'Sumber Daya Air dan Irigasi', 'Fasilitas Pendidikan', 'Air Minum', 'Fasilitas Sarana dan Prasarana Olahraga serta Kesenian', 'Sistem Pengolahan Air Limbah Terpusat', 'Kawasan', 'Sistem Pengelolaan Air Limbah Setempat', 'Pariwisata', 'Sistem Pengelolaan Persampahan', 'Lembaga Pemasyarakatan', 'Telekomunikasi dan Informatika', 'Kesehatan', 'Ketenagalistrikan', 'Perumahan Rakyat', 'Minyak dan Gas Bumi dan Energi Terbarukan'];

        foreach($jenis_infrastrukturs as $jenis_infrastruktur) {
            $obj = new JenisInfrastruktur;
            $obj->jenis_infrastruktur = $jenis_infrastruktur;

            $obj->save();
        }
    }
}
