<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Account::insert([
            [
                'kode' => '11-10-110',
                'nama' => 'Kas',
                'classification_id' => 1
            ],
            [
                'kode' => '11-11-110',
                'nama' => 'Bank BCA',
                'classification_id' => 1
            ],
            [
                'kode' => '12-11-110',
                'nama' => 'Penjualan Barang Dagang',
                'classification_id' => 2
            ],
        ]);
    }
}
