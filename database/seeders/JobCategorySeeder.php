<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobCategory;
use Illuminate\Support\Str;

class JobCategorySeeder extends Seeder
{
    public function run(): void
    {
        $parentCategories = [
            'エンジニア・技術（システム/ネットワーク）' => [
                'フロントエンドエンジニア',
                'バックエンドエンジニア',
                'iOSアプリエンジニア',
                'Androidアプリエンジニア',
                'クラウドエンジニア（AWS・GCP・Azure）',
                'ネットワークエンジニア',
                'サーバー・ネットワーク運用保守',
                'DevOps・SRE',
                'QAエンジニア',
                'AI・機械学習エンジニア',
                'データエンジニア',
                'データサイエンティスト',
                'プロジェクトマネージャー',
                'ITコンサルタント・プリセールス',
                'Webコーダー',
                '社内SE',
                '制御・組み込み系エンジニア',
            ],
            'デザイン' => [
                'Webデザイナー',
                'UI/UXデザイナー',
                'グラフィックデザイナー',
            ],
            '営業' => [
                '法人営業',
                '個人営業',
                'インサイドセールス',
            ],
            'マーケティング' => [
                'デジタルマーケティング',
                'コンテンツマーケティング',
                'SEO・SEM',
                'SNSマーケティング',
            ],
            '事務・管理' => [
                '一般事務',
                '経理・財務',
                '人事・総務',
                '法務',
                '秘書',
            ],
            'クリエイティブ' => [
                'コピーライター',
                '編集・ライター',
                '映像制作・編集',
                '音響・サウンドデザイナー',
            ],
            '医療・福祉' => [
                '看護師',
                '介護職',
                '医療事務',
                '薬剤師',
            ],
            '教育・保育' => [
                '保育士',
                '幼稚園教諭',
                '小学校教諭',
                '中学校教諭',
                '高校教諭',
                '専門学校講師',
            ],
            '販売・サービス' => [
                '小売業',
                '飲食業',
                '宿泊業',
                '旅行業',
                '美容・理容',
            ],
            '物流・運輸' => [
                'ドライバー',
                '倉庫管理・物流センター',
                '配送・配達',
            ],
            '建設・土木' => [
                '建築士',
                '土木技術者',
                '施工管理',
                '設備工事',
            ],
            '製造・生産' => [
                '製造オペレーター',
                '品質管理・検査',
                '生産技術',
                '機械設計',
            ],
            '研究・開発' => [
                '研究職',
                '開発職',
                '技術営業',
            ],
            'その他' => [
                '通訳・翻訳',
                'イベント・プロモーション',
                'セキュリティ',
                '環境・エネルギー',
                '農林水産業',
            ]
        ];

        foreach ($parentCategories as $parentName => $children) {
            $parentSlug = uniqid('cat_');
            $parent = JobCategory::create([
                'name' => $parentName,
                'parent_id' => null,
                'slug' => $parentSlug,
                'icon' => 'icon_sample',
            ]);
            foreach ($children as $childName) {
                $childSlug = uniqid('cat_');
                JobCategory::create([
                    'name' => $childName,
                    'parent_id' => $parent->id,
                    'slug' => $childSlug,
                ]);
            }
        }
    }
}
