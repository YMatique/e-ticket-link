<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $provinces = [
            ['name' => 'Maputo Cidade', 'code' => 'MPC'],
            ['name' => 'Maputo', 'code' => 'MP'],
            ['name' => 'Gaza', 'code' => 'GZ'],
            ['name' => 'Inhambane', 'code' => 'IN'],
            ['name' => 'Sofala', 'code' => 'SF'],
            ['name' => 'Manica', 'code' => 'MN'],
            ['name' => 'Tete', 'code' => 'TT'],
            ['name' => 'Zambézia', 'code' => 'ZB'],
            ['name' => 'Nampula', 'code' => 'NP'],
            ['name' => 'Cabo Delgado', 'code' => 'CD'],
            ['name' => 'Niassa', 'code' => 'NS'],
        ];

        foreach ($provinces as $province) {
            Province::create($province);
        }
    }
}
