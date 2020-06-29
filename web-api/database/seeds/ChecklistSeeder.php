<?php

use Illuminate\Database\Seeder;

use App\CheckList;

class ChecklistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $tipe_checklists = array('kategori', 'dukungan_pemerintah');
        $checklists = array(
            array('Proyek Strategis Nasional', 'PPP Book', 'Pipeline Kantor Bersama'),
            array('PDF', 'VGF', 'Dukungan Konstruksi', 'Dukungan Lain'));

        $i = 0;
        foreach($tipe_checklists as $tipe_checklist) {

            foreach($checklists[$i] as $checklist) {
                $obj = new CheckList;
                $obj->tipe = $tipe_checklist;
                $obj->checklist = $checklist;

                $obj->save();
            }
            

            $i++;
        }
    }
}
