<?php

use Illuminate\Database\Seeder;

use App\DropdownList;

class DropdownSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $tipe_dropdowns = array('sektor', 'tipe', 'sistem_pengembalian_investasi');
        $dropdowns = array(
            array('Transportasi', 'Konservasi Energi', 'Jalan', 'Fasilitas Perkotaan', 'Sumber Daya Air dan Irigasi', 'Fasilitas Pendidikan', 'Air Minum', 'Fasilitas Sarana Prasarana Olahraga & Kesenian', 'Sistem Pengelolaan Air Limbah Terpusat', 'Sistem Pengelolaan Air Limbah Setempat', 'Kawasan', 'Pariwisata', 'Sistem Pengelolaan Persampahan', 'Lembaga Pemasyarakatan', 'Telekomunikasi dan Informatika', 'Kesehatan', 'Ketenagalistrikan', 'Perumahan Rakyat', 'Minyak, Gas Bumi, dan Energi Terbarukan'),
            array('Solicited', 'Unsolicited'),
            array('Availability Payment (AP)', 'User Payment', 'Skema Lain'));

        $i = 0;
        foreach($tipe_dropdowns as $tipe_dropdown) {

            foreach($dropdowns[$i] as $dropdown) {
                $obj = new DropdownList;
                $obj->tipe = $tipe_dropdown;
                $obj->dropdown = $dropdown;

                $obj->save();
            }
            

            $i++;
        }
    }
}
