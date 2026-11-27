<?php

namespace App\Services;

use App\Models\SecurityBenefitModule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class SecurityBenefitsService
{
    public function adminPageData(): array
    {
        $tables = [
            'security_benefit_modules',
            'security_benefit_categories',
            'security_benefits',
        ];
        $schemaAvailable = collect($tables)->every(fn (string $table) => Schema::hasTable($table));

        if (! $schemaAvailable) {
            return $this->emptyPageData(false);
        }

        $modules = SecurityBenefitModule::query()
            ->where('is_manual', true)
            ->with([
                'categories' => fn ($query) => $query
                    ->where('is_manual', true)
                    ->with(['benefits' => fn ($query) => $query->where('is_manual', true)]),
            ])
            ->orderBy('sort_order')
            ->get()
            ->keyBy('slug');

        $xdrModule = $modules->get('xdr');
        $siemModule = $modules->get('siem');
        $sharedModule = $modules->get('shared');
        $xdrBenefits = $this->categoryBenefits($xdrModule, 'benefits');
        $siemBenefits = $this->categoryBenefits($siemModule, 'benefits');
        $comparisonCategory = $sharedModule?->categories->firstWhere('slug', 'comparison');
        $businessCategory = $sharedModule?->categories->firstWhere('slug', 'business-value');

        $comparison = $comparisonCategory?->benefits ?? collect();
        $businessBenefits = $businessCategory?->benefits ?? collect();

        return [
            'benefitsSchemaAvailable' => true,
            'benefitsAvailable' => $xdrModule
                && $siemModule
                && $xdrBenefits->isNotEmpty()
                && $siemBenefits->isNotEmpty()
                && $comparison->isNotEmpty()
                && $businessBenefits->isNotEmpty(),
            'xdrModule' => $xdrModule,
            'siemModule' => $siemModule,
            'xdrBenefits' => $xdrBenefits,
            'siemBenefits' => $siemBenefits,
            'comparisonCategory' => $comparisonCategory,
            'comparison' => $comparison,
            'businessCategory' => $businessCategory,
            'businessBenefits' => $businessBenefits,
        ];
    }

    private function categoryBenefits(?SecurityBenefitModule $module, string $slug): Collection
    {
        return $module?->categories->firstWhere('slug', $slug)?->benefits ?? collect();
    }

    private function emptyPageData(bool $schemaAvailable): array
    {
        return [
            'benefitsSchemaAvailable' => $schemaAvailable,
            'benefitsAvailable' => false,
            'xdrModule' => null,
            'siemModule' => null,
            'xdrBenefits' => collect(),
            'siemBenefits' => collect(),
            'comparisonCategory' => null,
            'comparison' => collect(),
            'businessCategory' => null,
            'businessBenefits' => collect(),
        ];
    }
}
