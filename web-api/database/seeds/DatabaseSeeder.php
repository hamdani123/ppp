<?php

use Illuminate\Database\Seeder;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        User::create(['username' => 'penjaminan', 'email' => 'admin@admin.com', 'password' => Hash::make('pdfvgf')]);
        $this->call(TahapanProyekDropdownSeeder::class);
        $this->call(JenisInfrastrukturSeeder::class);
        $this->call(DropdownSeeder::class);
        $this->call(ChecklistSeeder::class);
    }
}
