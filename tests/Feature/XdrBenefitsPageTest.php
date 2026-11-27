<?php

namespace Tests\Feature;

use App\Http\Middleware\LogRequestActivity;
use App\Models\SecurityBenefit;
use App\Models\SecurityBenefitCategory;
use App\Models\SecurityBenefitModule;
use App\Models\User;
use App\Services\SecurityBenefitsService;
use App\Support\SecurityBenefitsCatalog;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Tests\TestCase;

class XdrBenefitsPageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(LogRequestActivity::class);
    }

    public function test_benefits_page_renders_persisted_curated_xdr_and_siem_content(): void
    {
        $pageData = $this->pageDataFromCatalog();
        $this->mock(SecurityBenefitsService::class)
            ->shouldReceive('adminPageData')
            ->once()
            ->andReturn($pageData);
        $this->actingAs($this->persistedAdmin());

        $response = $this->get(route('admin.xdr.benefits'));

        $response->assertOk();
        foreach ([
            'Endpoint visibility',
            'Workload visibility',
            'Network detection',
            'Identity context',
            'Cloud visibility',
            'Email security',
            'Correlated detections',
            'SOC triage',
            'Incident response',
            'Root-cause analysis',
            'Attack-surface visibility',
            'Insider-threat context',
            'Ransomware readiness',
            'Malware investigation',
            'Lateral movement',
            'Vulnerability context',
            'Threat intelligence',
            'Asset inventory',
            'Cases and evidence',
            'Automation and remediation',
            'Continuous improvement',
            'Collection',
            'Parsing and normalization',
            'Retention',
            'Governance',
            'Correlation',
            'Rule management',
            'Behavioral analytics and UEBA',
            'Compliance reporting',
            'Audit trails',
            'Search and forensics',
            'Dashboards and alerts',
            'Integrations',
            'SOAR workflows',
            'False-positive tuning',
            'Scalability',
            'High availability',
            'Cost control',
            'Real-time monitoring',
            'XDR and SIEM are complementary',
            'Business and operational value',
            'No live Wazuh, XDR, or SIEM service is connected',
        ] as $content) {
            $response->assertSee($content);
        }

        $this->assertGreaterThan(40, $pageData['xdrBenefits']->count() + $pageData['siemBenefits']->count());
    }

    public function test_benefits_route_rejects_public_access(): void
    {
        $this->get(route('admin.xdr.benefits'))->assertRedirect(route('login'));
    }

    public function test_benefit_policy_rejects_non_persisted_users(): void
    {
        $user = new User(['name' => 'Unpersisted', 'email' => 'guest@example.test']);
        $this->actingAs($user);

        $this->get(route('admin.xdr.benefits'))->assertForbidden();
    }

    public function test_benefits_page_shows_migration_notice_when_tables_are_missing(): void
    {
        Schema::shouldReceive('hasTable')
            ->once()
            ->with('security_benefit_modules')
            ->andReturnFalse();

        $this->actingAs($this->persistedAdmin())
            ->get(route('admin.xdr.benefits'))
            ->assertOk()
            ->assertSee('Benefits storage is not set up yet')
            ->assertSee('php artisan migrate');
    }

    public function test_seeder_skips_cleanly_when_benefit_tables_are_missing(): void
    {
        Schema::shouldReceive('hasTable')
            ->once()
            ->with('security_benefit_modules')
            ->andReturnFalse();

        $this->seed(\Database\Seeders\SecurityBenefitsSeeder::class);
    }

    private function pageDataFromCatalog(): array
    {
        $catalog = SecurityBenefitsCatalog::data();
        $xdrModule = new SecurityBenefitModule([
            'name' => 'Extended Detection & Response',
            'subtitle' => 'Cross-layer detection · coordinated response',
        ]);
        $siemModule = new SecurityBenefitModule([
            'name' => 'Security Information & Event Management',
            'subtitle' => 'Centralized logs · searchable security events',
        ]);
        $comparisonCategory = new SecurityBenefitCategory(['name' => 'XDR and SIEM are complementary']);
        $businessCategory = new SecurityBenefitCategory(['name' => 'Business and operational value']);

        return [
            'benefitsSchemaAvailable' => true,
            'benefitsAvailable' => true,
            'xdrModule' => $xdrModule,
            'siemModule' => $siemModule,
            'xdrBenefits' => $this->records($catalog['xdrBenefits']),
            'siemBenefits' => $this->records($catalog['siemBenefits']),
            'comparisonCategory' => $comparisonCategory,
            'comparison' => $this->records($catalog['comparison'], true),
            'businessCategory' => $businessCategory,
            'businessBenefits' => $this->records($catalog['businessBenefits']),
        ];
    }

    private function records(array $items, bool $comparison = false): Collection
    {
        return collect($items)->values()->map(function (array $item, int $index) use ($comparison) {
            return new SecurityBenefit([
                'slug' => Str::slug($item['category'] ?? $item['dimension'] ?? $item['title']),
                'label' => $item['category'] ?? null,
                'title' => $comparison ? $item['dimension'] : $item['title'],
                'description' => $comparison ? $item['xdr'] : $item['description'],
                'potential_value' => $item['value'] ?? null,
                'context' => $comparison ? ['xdr' => $item['xdr'], 'siem' => $item['siem']] : null,
                'sort_order' => $index,
            ]);
        });
    }

    private function persistedAdmin(): User
    {
        $user = new User(['name' => 'Admin', 'email' => 'admin@example.test']);
        $user->exists = true;

        return $user;
    }
}
