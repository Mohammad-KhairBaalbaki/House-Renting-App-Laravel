<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Governorate;
use App\Models\City;

class CitySeeder extends Seeder
{
    public function run()
    {
        $citiesByGov = [
            'Damascus' => [
                ['en'=>'Damascus',           'ar'=>'دمشق'],
                ['en'=>'Al-Qadam', 'ar'=>'القدم'],
                ['en'=>'Sayyida Zainab',     'ar'=>'سيدة زينب'],
                ['en'=>'Al-Midan',           'ar'=>'الميدان'],
                ['en'=>'Kafr Sousa',         'ar'=>'كفر سوسة'],
                ['en'=>'Jobar',              'ar'=>'جوبار'],
                ['en'=>'Bab Touma',          'ar'=>'باب توما'],
                ['en'=>'Baramkeh',           'ar'=>'البرامكة'],
                ['en'=>'Mezzeh',             'ar'=>'المزة'],
                ['en'=>'Rukn al-Din',       'ar'=>'رُكن الدين'],
                ['en'=>'Qanawat',            'ar'=>'القنوات'],
                ['en'=>'Tadamun',            'ar'=>'التضامن'],
                ['en'=>'Shaghour',           'ar'=>'الشاغور'],
                ['en'=>'Khan al-Sabil',      'ar'=>'خان السبيل'],
                ['en'=>'Al-Mazraa',          'ar'=>'المزارع'],
            ],
            'Rural Damascus' => [
                ['en'=>'Douma',         'ar'=>'دوما'],
                ['en'=>'Al-Tall',       'ar'=>'التل'],
                ['en'=>'Al-Qutayfah',   'ar'=>'القطيفة'],
                ['en'=>'An-Nabek',      'ar'=>'النَبْك'],
                ['en'=>'Al-Malihah',    'ar'=>'المالحة'],
            ],
            'Aleppo' => [
                ['en'=>'Aleppo',    'ar'=>'حلب'],
                ['en'=>'Manbij',    'ar'=>'منبج'],
                ['en'=>'Afrin',     'ar'=>'عفرين'],
                ['en'=>'Azaz',      'ar'=>'أعزاز'],
                ['en'=>'Al-Bab',    'ar'=>'الباب'],
            ],
            'Homs' => [
                ['en'=>'Homs',        'ar'=>'حمص'],
                ['en'=>'Tadmur',      'ar'=>'تدمر'],
                ['en'=>'Al-Qusayr',   'ar'=>'القصير'],
                ['en'=>'Al-Rastan',   'ar'=>'الرستن'],
                ['en'=>'Talbiseh',    'ar'=>'تلبيسة'],
            ],
            'Hama' => [
                ['en'=>'Hama',            'ar'=>'حماة'],
                ['en'=>'Mahardah',        'ar'=>'محردة'],
                ['en'=>'Salamiyah',       'ar'=>'السلّمية'],
                ['en'=>'Al-Suqaylabiyah', 'ar'=>'السقيلبية'],
                ['en'=>'Masyaf',          'ar'=>'مصياف'],
            ],
            'Idlib' => [
                ['en'=>'Idlib',          'ar'=>'إدلب'],
                ['en'=>'Jisr al-Shughur', 'ar'=>'جسر الشغور'],
                ['en'=>'Ariha',         'ar'=>'أريحا'],
                ['en'=>'Saraqib',       'ar'=>'سراقب'],
                ['en'=>'Maarrat al-Nu\'man','ar'=>'معرّة النعمان'],
            ],
            'Latakia' => [
                ['en'=>'Latakia',   'ar'=>'اللاذقية'],
                ['en'=>'Jableh',    'ar'=>'جبلة'],
                ['en'=>'Qardaha',   'ar'=>'القرداحة'],
                ['en'=>'Safita',    'ar'=>'صافيتا'],
                ['en'=>'Baniyas',   'ar'=>'بانياس'],
            ],
            'Tartus' => [
                ['en'=>'Tartus',    'ar'=>'طرطوس'],
                ['en'=>'Baniyas',   'ar'=>'بانياس'],
                ['en'=>'Duraykish', 'ar'=>'دريكيش'],
                ['en'=>'Al-Hamidiyah','ar'=>'الحميدية'],
                ['en'=>'Safita',    'ar'=>'صافيتا'],
            ],
            'Deir ez-Zor' => [
                ['en'=>'Deir ez-Zor', 'ar'=>'دير الزور'],
                ['en'=>'Mayadin',     'ar'=>'الميادين'],
                ['en'=>'Al-Busayrah', 'ar'=>'البصيرة'],
                ['en'=>'Al-Quriyah',  'ar'=>'القورية'],
                ['en'=>'Abu Kamal',   'ar'=>'البوكمال'],
            ],
            'Raqqa' => [
                ['en'=>'Raqqa',       'ar'=>'الرقة'],
                ['en'=>'Al-Tabqah',   'ar'=>'الطبقة'],
                ['en'=>'Tal Abyad',   'ar'=>'تل أبيض'],
                ['en'=>'Ain Issa',    'ar'=>'عين عيسى'],
                ['en'=>'Sukhnah',     'ar'=>'سخنة'],
            ],
            'Al-Hasakah' => [
                ['en'=>'Al-Hasakah', 'ar'=>'الحسكة'],
                ['en'=>'Qamishli',   'ar'=>'القامشلي'],
                ['en'=>'Al-Malikiyah','ar'=>'المالكية'],
                ['en'=>'Ras al-Ain', 'ar'=>'رأس العين'],
                ['en'=>'Amuda',      'ar'=>'عامودا'],
            ],
            'Daraa' => [
                ['en'=>'Daraa',     'ar'=>'درعا'],
                ['en'=>'Jasim',     'ar'=>'جاسم'],
                ['en'=>'Izra',      'ar'=>'إزرع'],
                ['en'=>'Al-Sanamayn','ar'=>'الصنمين'],
                ['en'=>'Muzayrib',  'ar'=>'المزيريب'],
            ],
            'Quneitra' => [
                ['en'=>'Quneitra',   'ar'=>'القنيطرة'],
                ['en'=>'Khan Arnabah','ar'=>'خان أرنبة'],
                ['en'=>'Fiq',         'ar'=>'فيق'],
                ['en'=>'Buq\'ata',    'ar'=>'بقعاثا'],
                ['en'=>'Beit Jinn',   'ar'=>'بيت جن'],
            ],
            'As-Suwayda' => [
                ['en'=>'As-Suwayda', 'ar'=>'السويداء'],
                ['en'=>'Salkhad',    'ar'=>'صلخد'],
                ['en'=>'Shahba',     'ar'=>'شهبا'],
                ['en'=>'Al-Mazraa',  'ar'=>'المزرعة'],
                ['en'=>'Hauran towns','ar'=>'بلدات حوران'],
            ],
        ];

        $governorates = Governorate::all();

        foreach ($governorates as $gov) {
            try {
                $govEn = $gov->getTranslation('name', 'en');
            } catch (\Throwable $e) {
                $govEn = $gov->name;
            }

            if (! isset($citiesByGov[$govEn])) {
                continue;
            }

            foreach ($citiesByGov[$govEn] as $city) {
                City::create([
                    'governorate_id' => $gov->id,
                    'name' => [
                        'en' => $city['en'],
                        'ar' => $city['ar'],
                    ],
                ]);
            }
        }
    }
}
