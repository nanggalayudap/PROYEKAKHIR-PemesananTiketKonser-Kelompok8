<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('customers can register and are redirected to login', function () {
    $response = $this->post('/register', [
        'account_type' => 'customer',
        'name' => 'Budi Santoso',
        'username' => 'budi_santoso',
        'email' => 'budi@example.com',
        'whatsapp' => '081234567890',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $this->assertGuest();
    $response->assertRedirect(route('login', absolute: false));
    $response->assertSessionHas('status');

    $user = User::where('email', 'budi@example.com')->firstOrFail();
    expect($user->username)->toBe('budi_santoso')
        ->and($user->whatsapp)->toBe('081234567890')
        ->and(Hash::check('password123', $user->password))->toBeTrue()
        ->and($user->hasRole('customer'))->toBeTrue()
        ->and($user->hasRole('admin'))->toBeFalse();
});

test('event organizers can register with organization details and get organizer role', function () {
    $response = $this->post('/register', [
        'account_type' => 'eo',
        'name' => 'Siti Rahma',
        'organization_name' => 'PT Harmoni Musik',
        'username' => 'harmoni_eo',
        'email' => 'official@harmoni.com',
        'whatsapp' => '081987654321',
        'office_address' => 'Jl. Sudirman No. 10, Jakarta',
        'eo_description' => 'Penyelenggara konser musik indie dan pop.',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $this->assertGuest();
    $response->assertRedirect(route('login', absolute: false));
    $response->assertSessionHas('status');

    $user = User::where('email', 'official@harmoni.com')->firstOrFail();
    expect($user->organization_name)->toBe('PT Harmoni Musik')
        ->and($user->office_address)->toBe('Jl. Sudirman No. 10, Jakarta')
        ->and($user->eo_description)->toBe('Penyelenggara konser musik indie dan pop.')
        ->and($user->hasRole('organizer'))->toBeTrue()
        ->and($user->hasRole('admin'))->toBeFalse();
});

test('users cannot manipulate role to admin during registration', function () {
    $response = $this->post('/register', [
        'account_type' => 'admin',
        'role' => 'admin',
        'name' => 'Attacker',
        'username' => 'attacker_01',
        'email' => 'attacker@example.com',
        'whatsapp' => '081234567890',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertSessionHasErrors('account_type');
    expect(User::where('email', 'attacker@example.com')->exists())->toBeFalse();
});

test('registration validates unique email and username', function () {
    User::factory()->create([
        'username' => 'existing_user',
        'email' => 'existing@example.com',
    ]);

    $response = $this->post('/register', [
        'account_type' => 'customer',
        'name' => 'Duplicate User',
        'username' => 'existing_user',
        'email' => 'existing@example.com',
        'whatsapp' => '081234567890',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertSessionHasErrors(['username', 'email']);
});
