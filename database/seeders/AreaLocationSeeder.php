<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Area;
use App\Models\Location;

class AreaLocationSeeder extends Seeder
{
    public function run(): void
    {
        // 親エリア
        $areas = [
            ['name' => '北海道・東北', 'slug' => 'hokkaido-tohoku'],
            ['name' => '関東', 'slug' => 'kanto'],
            ['name' => '信越・北陸', 'slug' => 'shinetsu-hokuriku'],
            ['name' => '東海', 'slug' => 'tokai'],
            ['name' => '近畿', 'slug' => 'kinki'],
            ['name' => '中国', 'slug' => 'chugoku'],
            ['name' => '四国', 'slug' => 'shikoku'],
            ['name' => '九州・沖縄', 'slug' => 'kyushu-okinawa'],
            ['name' => 'フルリモート・海外', 'slug' => 'other'],
        ];

        $areaIds = [];
        foreach ($areas as $i => $area) {
            $a = Area::create([
                'name' => $area['name'],
                'slug' => $area['slug'],
                'sort_order' => $i + 1,
            ]);
            $areaIds[$area['slug']] = $a->id;
        }

        // 都道府県 + 海外・リモート
        $locations = [
            // 北海道・東北
            ['name' => '北海道', 'slug' => 'hokkaido', 'area_slug' => 'hokkaido-tohoku'],
            ['name' => '青森県', 'slug' => 'aomori', 'area_slug' => 'hokkaido-tohoku'],
            ['name' => '岩手県', 'slug' => 'iwate', 'area_slug' => 'hokkaido-tohoku'],
            ['name' => '宮城県', 'slug' => 'miyagi', 'area_slug' => 'hokkaido-tohoku'],
            ['name' => '秋田県', 'slug' => 'akita', 'area_slug' => 'hokkaido-tohoku'],
            ['name' => '山形県', 'slug' => 'yamagata', 'area_slug' => 'hokkaido-tohoku'],
            ['name' => '福島県', 'slug' => 'fukushima', 'area_slug' => 'hokkaido-tohoku'],

            // 関東
            ['name' => '茨城県', 'slug' => 'ibaraki', 'area_slug' => 'kanto'],
            ['name' => '栃木県', 'slug' => 'tochigi', 'area_slug' => 'kanto'],
            ['name' => '群馬県', 'slug' => 'gunma', 'area_slug' => 'kanto'],
            ['name' => '埼玉県', 'slug' => 'saitama', 'area_slug' => 'kanto'],
            ['name' => '千葉県', 'slug' => 'chiba', 'area_slug' => 'kanto'],
            ['name' => '東京都', 'slug' => 'tokyo', 'area_slug' => 'kanto'],
            ['name' => '神奈川県', 'slug' => 'kanagawa', 'area_slug' => 'kanto'],

            // 信越・北陸
            ['name' => '新潟県', 'slug' => 'niigata', 'area_slug' => 'shinetsu-hokuriku'],
            ['name' => '富山県', 'slug' => 'toyama', 'area_slug' => 'shinetsu-hokuriku'],
            ['name' => '石川県', 'slug' => 'ishikawa', 'area_slug' => 'shinetsu-hokuriku'],
            ['name' => '福井県', 'slug' => 'fukui', 'area_slug' => 'shinetsu-hokuriku'],
            ['name' => '山梨県', 'slug' => 'yamanashi', 'area_slug' => 'shinetsu-hokuriku'],
            ['name' => '長野県', 'slug' => 'nagano', 'area_slug' => 'shinetsu-hokuriku'],

            // 東海
            ['name' => '岐阜県', 'slug' => 'gifu', 'area_slug' => 'tokai'],
            ['name' => '静岡県', 'slug' => 'shizuoka', 'area_slug' => 'tokai'],
            ['name' => '愛知県', 'slug' => 'aichi', 'area_slug' => 'tokai'],
            ['name' => '三重県', 'slug' => 'mie', 'area_slug' => 'tokai'],

            // 近畿
            ['name' => '滋賀県', 'slug' => 'shiga', 'area_slug' => 'kinki'],
            ['name' => '京都府', 'slug' => 'kyoto', 'area_slug' => 'kinki'],
            ['name' => '大阪府', 'slug' => 'osaka', 'area_slug' => 'kinki'],
            ['name' => '兵庫県', 'slug' => 'hyogo', 'area_slug' => 'kinki'],
            ['name' => '奈良県', 'slug' => 'nara', 'area_slug' => 'kinki'],
            ['name' => '和歌山県', 'slug' => 'wakayama', 'area_slug' => 'kinki'],

            // 中国
            ['name' => '鳥取県', 'slug' => 'tottori', 'area_slug' => 'chugoku'],
            ['name' => '島根県', 'slug' => 'shimane', 'area_slug' => 'chugoku'],
            ['name' => '岡山県', 'slug' => 'okayama', 'area_slug' => 'chugoku'],
            ['name' => '広島県', 'slug' => 'hiroshima', 'area_slug' => 'chugoku'],
            ['name' => '山口県', 'slug' => 'yamaguchi', 'area_slug' => 'chugoku'],

            // 四国
            ['name' => '徳島県', 'slug' => 'tokushima', 'area_slug' => 'shikoku'],
            ['name' => '香川県', 'slug' => 'kagawa', 'area_slug' => 'shikoku'],
            ['name' => '愛媛県', 'slug' => 'ehime', 'area_slug' => 'shikoku'],
            ['name' => '高知県', 'slug' => 'kochi', 'area_slug' => 'shikoku'],

            // 九州・沖縄
            ['name' => '福岡県', 'slug' => 'fukuoka', 'area_slug' => 'kyushu-okinawa'],
            ['name' => '佐賀県', 'slug' => 'saga', 'area_slug' => 'kyushu-okinawa'],
            ['name' => '長崎県', 'slug' => 'nagasaki', 'area_slug' => 'kyushu-okinawa'],
            ['name' => '熊本県', 'slug' => 'kumamoto', 'area_slug' => 'kyushu-okinawa'],
            ['name' => '大分県', 'slug' => 'oita', 'area_slug' => 'kyushu-okinawa'],
            ['name' => '宮崎県', 'slug' => 'miyazaki', 'area_slug' => 'kyushu-okinawa'],
            ['name' => '鹿児島県', 'slug' => 'kagoshima', 'area_slug' => 'kyushu-okinawa'],
            ['name' => '沖縄県', 'slug' => 'okinawa', 'area_slug' => 'kyushu-okinawa'],

            // その他（海外・フルリモート）
            ['name' => '海外', 'slug' => 'overseas', 'area_slug' => 'other'],
            ['name' => 'フルリモート', 'slug' => 'remote', 'area_slug' => 'other'],
        ];

        foreach ($locations as $i => $loc) {
            Location::create([
                'name' => $loc['name'],
                'slug' => $loc['slug'],
                'area_id' => $areaIds[$loc['area_slug']],
                'sort_order' => $i + 1,
            ]);
        }
    }
}
