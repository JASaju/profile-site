<?php

namespace Database\Seeders;

use App\Models\SecurityBenefit;
use App\Models\SecurityBenefitCategory;
use App\Models\SecurityBenefitModule;
use App\Support\SecurityBenefitsCatalog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class SecurityBenefitsSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['security_benefit_modules', 'security_benefit_categories', 'security_benefits'] as $table) {
            if (! Schema::hasTable($table)) {
                $this->command?->warn('Security benefits tables are missing. Run php artisan migrate before seeding benefits.');

                return;
            }
        }

        $catalog = SecurityBenefitsCatalog::data();
        $modules = [
            'xdr' => [
                'name' => 'Extended Detection & Response',
                'subtitle' => 'Cross-layer detection · coordinated response',
                'sort_order' => 10,
            ],
            'siem' => [
                'name' => 'Security Information & Event Management',
                'subtitle' => 'Centralized logs · searchable security events',
                'sort_order' => 20,
            ],
            'shared' => [
                'name' => 'Shared outcomes',
                'subtitle' => 'Comparison and business value',
                'sort_order' => 30,
            ],
        ];

        $moduleRecords = [];
        foreach ($modules as $slug => $attributes) {
            $moduleRecords[$slug] = SecurityBenefitModule::firstOrCreate(
                ['slug' => $slug],
                $attributes + ['is_manual' => true]
            );
        }

        $this->seedCategory($moduleRecords['xdr'], 'benefits', 'XDR benefits', 'benefits', $catalog['xdrBenefits']);
        $this->seedCategory($moduleRecords['siem'], 'benefits', 'SIEM benefits', 'benefits', $catalog['siemBenefits']);
        $this->seedCategory($moduleRecords['shared'], 'comparison', 'XDR and SIEM are complementary', 'comparison', $catalog['comparison']);
        $this->seedCategory($moduleRecords['shared'], 'business-value', 'Business and operational value', 'business', $catalog['businessBenefits']);
    }

    private function seedCategory(
        SecurityBenefitModule $module,
        string $slug,
        string $name,
        string $contentType,
        array $items
    ): void {
        $category = SecurityBenefitCategory::firstOrCreate(
            ['module_id' => $module->id, 'slug' => $slug],
            [
                'name' => $name,
                'content_type' => $contentType,
                'sort_order' => 10,
                'is_manual' => true,
            ]
        );

        foreach ($items as $index => $item) {
            $comparison = $contentType === 'comparison';
            $values = [
                'category_id' => $category->id,
                'label' => $item['category'] ?? null,
                'title' => $comparison ? $item['dimension'] : $item['title'],
                'description' => $comparison ? $item['xdr'] : $item['description'],
                'potential_value' => $comparison ? null : ($item['value'] ?? null),
                'context' => $comparison ? ['xdr' => $item['xdr'], 'siem' => $item['siem']] : null,
                'sort_order' => $index + 1,
                'is_manual' => true,
            ];
            $keyPrefix = $module->slug === 'shared'
                ? $contentType
                : $module->slug;
            $key = $item['category'] ?? $item['dimension'] ?? $item['title'];
            $slug = $keyPrefix.'-'.Str::slug($key);

            SecurityBenefit::firstOrCreate(['slug' => $slug], $values);
        }
    }
}
