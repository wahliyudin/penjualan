<?php

namespace Database\Seeders;

use App\Models\Classification;
use Illuminate\Database\Seeder;

class ClasificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Classification::insert([
            [
                'nama' => 'Akun Kas'
            ],
            [
                'nama' => 'Penjualan'
            ]
        ]);
    }
}
