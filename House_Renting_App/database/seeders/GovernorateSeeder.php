<?php

namespace Database\Seeders;

use App\Models\Governorate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GovernorateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $governorates = [
            ['en' => 'Damascus',          'ar' => 'دمشق'],
            ['en' => 'Rif Damascus',    'ar' => 'ريف دمشق'],
            ['en' => 'Aleppo',           'ar' => 'حلب'],
            ['en' => 'Homs',             'ar' => 'حمص'],
            ['en' => 'Hama',             'ar' => 'حماة'],
            ['en' => 'Idlib',            'ar' => 'إدلب'],
            ['en' => 'Latakia',          'ar' => 'اللاذقية'],
            ['en' => 'Tartus',           'ar' => 'طرطوس'],
            ['en' => 'Deir ez-Zor',      'ar' => 'دير الزور'],
            ['en' => 'Raqqa',            'ar' => 'الرقة'],
            ['en' => 'Al-Hasakah',       'ar' => 'الحسكة'],
            ['en' => 'Daraa',            'ar' => 'درعا'],
            ['en' => 'Quneitra',         'ar' => 'القنيطرة'],
            ['en' => 'As-Suwayda',       'ar' => 'السويداء'],
        ];

        foreach ($governorates as $item) {
            Governorate::create([
                'name' => $item,
            ]);
        }
    }
}
