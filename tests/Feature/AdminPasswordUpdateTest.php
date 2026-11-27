<?php

namespace Tests\Feature;

use App\Http\Middleware\LogRequestActivity;
use App\Models\Profile;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Tests\TestCase;

class AdminPasswordUpdateTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(LogRequestActivity::class);
    }

    public function test_admin_dashboard_has_password_update_card_and_no_public_reset_link(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertDontSee('Forgot password?');

        $routes = app('router')->getRoutes();
        $this->assertNull($routes->getByName('password.request'));
        $this->assertNull($routes->getByName('password.email'));

        $html = view('admin.dashboard', [
            'profile' => new Profile,
            'slides' => collect(),
            'socials' => collect(),
            'education' => collect(),
            'documentaries' => collect(),
            'gallery' => collect(),
        ])->render();

        $this->assertStringContainsString('Update Password', $html);
        $this->assertStringContainsString('Current Password', $html);
        $this->assertStringContainsString('New Password', $html);
        $this->assertStringContainsString('Confirm Password', $html);
        $this->assertStringContainsString(route('admin.password'), $html);
        $this->assertStringContainsString('SAVE', $html);
    }

    public function test_password_update_route_is_protected(): void
    {
        $this->put(route('admin.password'), [])->assertRedirect(route('login'));
    }

    public function test_admin_can_update_password_with_current_password_and_audit_event(): void
    {
        $user = $this->mockAdmin('Current-password-123!');
        $user->shouldReceive('save')->once()->andReturnTrue();

        $auditLogger = Mockery::mock(AuditLogger::class);
        $auditLogger->shouldReceive('record')->once()->withArgs(
            fn ($request, string $action, string $description, string $severity) => $action === 'admin.password.updated'
                && $description === 'Administrator password updated.'
                && $severity === 'info'
        );
        $this->app->instance(AuditLogger::class, $auditLogger);
        $this->actingAs($user);

        $this->put(route('admin.password'), [
            'current_password' => 'Current-password-123!',
            'password' => 'New-secure-password-456!',
            'password_confirmation' => 'New-secure-password-456!',
        ])
            ->assertRedirect()
            ->assertSessionHas('status', 'Password updated successfully.');

        $this->assertTrue(Hash::check('New-secure-password-456!', $user->password));
        $this->assertNotSame('existing-remember-token', $user->remember_token);
        $this->assertAuthenticatedAs($user);
    }

    public function test_password_update_rejects_an_incorrect_current_password(): void
    {
        $user = $this->mockAdmin('Current-password-123!');
        $user->shouldNotReceive('save');
        $this->actingAs($user);

        $this->from(route('admin.dashboard'))
            ->put(route('admin.password'), [
                'current_password' => 'Wrong-password-123!',
                'password' => 'New-secure-password-456!',
                'password_confirmation' => 'New-secure-password-456!',
            ])
            ->assertRedirect(route('admin.dashboard'))
            ->assertSessionHasErrors('current_password');
    }

    public function test_password_update_requires_strong_confirmed_new_password(): void
    {
        $user = $this->mockAdmin('Current-password-123!');
        $user->shouldNotReceive('save');
        $this->actingAs($user);

        $this->put(route('admin.password'), [
            'current_password' => 'Current-password-123!',
            'password' => 'weak',
            'password_confirmation' => 'not-the-same',
        ])->assertSessionHasErrors('password');
    }

    private function mockAdmin(string $password): User
    {
        $user = Mockery::mock(User::class)->makePartial();
        $user->setRawAttributes([
            'id' => 7,
            'name' => 'Admin',
            'email' => 'admin@example.test',
            'password' => Hash::make($password),
            'remember_token' => 'existing-remember-token',
        ]);
        $user->exists = true;

        return $user;
    }
}
