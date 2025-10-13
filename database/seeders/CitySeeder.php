<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Province;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            // Maputo Cidade
            'MPC' => [
                'Maputo',
            ],
            
            // Maputo Província
            'MP' => [
                'Matola',
                'Boane',
                'Marracuene',
                'Manhiça',
                'Magude',
                'Moamba',
                'Namaacha',
            ],
            
            // Gaza
            'GZ' => [
                'Xai-Xai',
                'Chokwé',
                'Chibuto',
                'Manjacaze',
                'Bilene',
                'Guijá',
                'Chicualacuala',
                'Mabalane',
                'Massangena',
                'Massingir',
                'Chigubo',
                'Mandlakazi',
            ],
            
            // Inhambane
            'IN' => [
                'Inhambane',
                'Maxixe',
                'Vilankulo',
                'Inhassoro',
                'Massinga',
                'Morrumbene',
                'Quissico',
                'Zavala',
                'Inharrime',
                'Panda',
                'Homoine',
                'Jangamo',
                'Funhalouro',
                'Govuro',
            ],
            
            // Sofala
            'SF' => [
                'Beira',
                'Dondo',
                'Nhamatanda',
                'Búzi',
                'Gorongosa',
                'Marromeu',
                'Chemba',
                'Caia',
                'Muanza',
                'Machanga',
                'Cheringoma',
            ],
            
            // Manica
            'MN' => [
                'Chimoio',
                'Manica',
                'Gondola',
                'Sussundenga',
                'Báruè',
                'Macossa',
                'Tambara',
                'Guro',
                'Machaze',
                'Mossurize',
            ],
            
            // Tete
            'TT' => [
                'Tete',
                'Moatize',
                'Cahora Bassa',
                'Changara',
                'Mutarara',
                'Zumbo',
                'Angónia',
                'Tsangano',
                'Macanga',
                'Chifunde',
                'Marávia',
                'Dôa',
                'Chiuta',
                'Magoe',
            ],
            
            // Zambézia
            'ZB' => [
                'Quelimane',
                'Mocuba',
                'Gurué',
                'Milange',
                'Alto Molócue',
                'Ile',
                'Namacurra',
                'Nicoadala',
                'Pebane',
                'Maganja da Costa',
                'Inhassunge',
                'Mocubela',
                'Morrumbala',
                'Gilé',
                'Namarroi',
                'Lugela',
                'Molumbo',
                'Chinde',
            ],
            
            // Nampula
            'NP' => [
                'Nampula',
                'Nacala',
                'Angoche',
                'Monapo',
                'Ilha de Moçambique',
                'Murrupula',
                'Ribáuè',
                'Malema',
                'Meconta',
                'Mogovolas',
                'Mongincual',
                'Mossuril',
                'Nacala-a-Velha',
                'Moma',
                'Larde',
                'Nacaroa',
                'Memba',
                'Eráti',
                'Mecubúri',
                'Nametil',
                'Muecate',
                'Rapale',
            ],
            
            // Cabo Delgado
            'CD' => [
                'Pemba',
                'Montepuez',
                'Mocímboa da Praia',
                'Mueda',
                'Palma',
                'Macomia',
                'Quissanga',
                'Ibo',
                'Chiúre',
                'Ancuabe',
                'Balama',
                'Metuge',
                'Meluco',
                'Namuno',
                'Nangade',
                'Muidumbe',
            ],
            
            // Niassa
            'NS' => [
                'Lichinga',
                'Cuamba',
                'Mandimba',
                'Marrupa',
                'Metarica',
                'Sanga',
                'Majune',
                'Muembe',
                'Nipepe',
                'Lago',
                'Chimbonila',
                'Mavago',
                'Mecula',
                'N\'gauma',
            ],
        ];

        foreach ($cities as $provinceCode => $cityNames) {
            $province = Province::where('code', $provinceCode)->first();
            
            if ($province) {
                foreach ($cityNames as $cityName) {
                    City::create([
                        'name' => $cityName,
                        'province_id' => $province->id,
                    ]);
                }
            }
        }
    }
}
