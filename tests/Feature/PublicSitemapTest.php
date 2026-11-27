<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PublicSitemapTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'app.key' => 'base64:'.base64_encode(str_repeat('a', 32)),
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
        ]);
        DB::purge('sqlite');
        DB::setDefaultConnection('sqlite');

        foreach (['profiles', 'slides', 'social_links', 'education', 'documentaries', 'gallery_items'] as $tableName) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->id();
                $table->timestamps();
            });
        }
    }

    public function test_sitemap_is_valid_xml_and_lists_only_the_public_profile_page(): void
    {
        DB::table('profiles')->insert([
            'created_at' => '2026-01-01 00:00:00',
            'updated_at' => '2026-01-02 00:00:00',
        ]);
        DB::table('gallery_items')->insert([
            'created_at' => '2026-01-01 00:00:00',
            'updated_at' => '2026-02-03 04:05:06',
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee('<loc>'.url('/').'</loc>', false)
            ->assertSee('<lastmod>2026-02-03T04:05:06+00:00</lastmod>', false)
            ->assertSee('<changefreq>weekly</changefreq>', false)
            ->assertSee('<priority>1.0</priority>', false)
            ->assertDontSee('/login')
            ->assertDontSee('/admin')
            ->assertDontSee('/media/')
            ->assertDontSee('/robots.txt')
            ->assertDontSee('/sitemap.xml');

        $document = new \DOMDocument();
        $this->assertTrue($document->loadXML($response->getContent()));
        $this->assertSame(
            1,
            $document->getElementsByTagNameNS('http://www.sitemaps.org/schemas/sitemap/0.9', 'url')->length
        );
    }

    public function test_sitemap_omits_lastmod_when_public_content_has_no_timestamp(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertOk()->assertDontSee('<lastmod>', false);
        $this->assertTrue((new \DOMDocument())->loadXML($response->getContent()));
    }
}
