<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use App\Models\Province;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $iran = Country::create([
            'name' => ['en' => 'Iran', 'fa' => 'ایران'],
            'iso2' => 'IR',
        ]);

        $provinces = [
            ['Alborz', 'البرز', ['Karaj', 'کرج', 'Nazarabad', 'نظرآباد', 'Garmdarreh', 'گلشهر']],
            ['Ardabil', 'اردبیل', ['Ardabil', 'اردبیل', 'Meshgin Shahr', 'مشگین‌شهر', 'Parsabad', 'پارس‌آباد']],
            ['Bushehr', 'بوشهر', ['Bushehr', 'بوشهر', 'Borazjan', 'برازجان', 'Bandar Ganaveh', 'گناوه']],
            ['Chaharmahal and Bakhtiari', 'چهارمحال و بختیاری', ['Shahrekord', 'شهرکرد', 'Borujen', 'بروجن', 'Farrokhshahr', 'فرخ‌شهر']],
            ['East Azerbaijan', 'آذربایجان شرقی', ['Tabriz', 'تبریز', 'Maragheh', 'مراغه', 'Marand', 'مرند']],
            ['Isfahan', 'اصفهان', ['Isfahan', 'اصفهان', 'Kashan', 'کاشان', 'Najafabad', 'نجف‌آباد']],
            ['Fars', 'فارس', ['Shiraz', 'شیراز', 'Marvdasht', 'مرودشت', 'Jahrom', 'جهرم']],
            ['Gilan', 'گیلان', ['Rasht', 'رشت', 'Lahijan', 'لاهیجان', 'Anzali', 'انزلی']],
            ['Golestan', 'گلستان', ['Gorgan', 'گرگان', 'Gonbad-e Kavus', 'گنبد کاووس', 'Bandar-e Torkaman', 'بندر ترکمن']],
            ['Hamadan', 'همدان', ['Hamadan', 'همدان', 'Malayer', 'ملایر', 'Nahavand', 'نهاوند']],
            ['Hormozgan', 'هرمزگان', ['Bandar Abbas', 'بندرعباس', 'Minab', 'میناب', 'Qeshm', 'قشم']],
            ['Ilam', 'ایلام', ['Ilam', 'ایلام', 'Eyvan', 'ایوان', 'Mehran', 'مهران']],
            ['Kerman', 'کرمان', ['Kerman', 'کرمان', 'Sirjan', 'سیرجان', 'Rafsanjan', 'رفسنجان']],
            ['Kermanshah', 'کرمانشاه', ['Kermanshah', 'کرمانشاه', 'Kangavar', 'کنگاور', 'Eslamabad-e Gharb', 'اسلام‌آباد غرب']],
            ['Khuzestan', 'خوزستان', ['Ahvaz', 'اهواز', 'Abadan', 'آبادان', 'Dezful', 'دزفول']],
            ['Kohgiluyeh and Boyer-Ahmad', 'کهگیلویه و بویراحمد', ['Yasuj', 'یاسوج', 'Dehdasht', 'دهدشت']],
            ['Kurdistan', 'کردستان', ['Sanandaj', 'سنندج', 'Saqqez', 'سقز', 'Marivan', 'مریوان']],
            ['Lorestan', 'لرستان', ['Khorramabad', 'خرم‌آباد', 'Borujerd', 'بروجرد', 'Aligudarz', 'الیگودرز']],
            ['Markazi', 'مرکزی', ['Arak', 'اراک', 'Saveh', 'ساوه', 'Khomeyn', 'خمین']],
            ['Mazandaran', 'مازندران', ['Sari', 'ساری', 'Amol', 'آمل', 'Babol', 'بابل']],
            ['North Khorasan', 'خراسان شمالی', ['Bojnord', 'بجنورد', 'Shirvan', 'شیروان', 'Esfarayen', 'اسفراین']],
            ['Qazvin', 'قزوین', ['Qazvin', 'قزوین', 'Takestan', 'تاکستان', 'Alvand', 'الوند']],
            ['Qom', 'قم', ['Qom', 'قم', 'Salafchegan', 'سلفچگان']],
            ['Razavi Khorasan', 'خراسان رضوی', ['Mashhad', 'مشهد', 'Neyshabur', 'نیشابور', 'Sabzevar', 'سبزوار']],
            ['Semnan', 'سمنان', ['Semnan', 'سمنان', 'Shahroud', 'شاهرود', 'Damghan', 'دامغان']],
            ['Sistan and Baluchestan', 'سیستان و بلوچستان', ['Zahedan', 'زاهدان', 'Zabol', 'زابل', 'Iranshahr', 'ایرانشهر']],
            ['South Khorasan', 'خراسان جنوبی', ['Birjand', 'بیرجند', 'Ferdows', 'فردوس', 'Tabas', 'طبس']],
            ['Tehran', 'تهران', ['Tehran', 'تهران', 'Varamin', 'ورامین', 'Shahriar', 'شهریار']],
            ['West Azerbaijan', 'آذربایجان غربی', ['Urmia', 'ارومیه', 'Khoy', 'خوی', 'Mahabad', 'مهاباد']],
            ['Yazd', 'یزد', ['Yazd', 'یزد', 'Ardakan', 'اردکان', 'Mehriz', 'مهریز']],
            ['Zanjan', 'زنجان', ['Zanjan', 'زنجان', 'Abhar', 'ابهر', 'Khorramdarreh', 'خرمدره']],
        ];

        foreach ($provinces as [$enName, $faName, $cities]) {
            $province = Province::create([
                'name' => ['en' => $enName, 'fa' => $faName],
                'country_id' => $iran->id,
            ]);

            for ($i = 0; $i < count($cities); $i += 2) {
                City::create([
                    'name' => ['en' => $cities[$i], 'fa' => $cities[$i + 1]],
                    'province_id' => $province->id,
                ]);
            }
        }
    }
}
