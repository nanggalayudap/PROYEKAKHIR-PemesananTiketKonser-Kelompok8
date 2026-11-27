<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Throwable;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $isOrganizer = $validated['account_type'] === 'eo';
        $roleName = $isOrganizer ? 'organizer' : 'customer';

        try {
            $user = DB::transaction(function () use ($validated, $isOrganizer, $roleName): User {
                $user = User::create([
                    'name' => $validated['name'],
                    'username' => $validated['username'],
                    'email' => $validated['email'],
                    'whatsapp' => $validated['whatsapp'],
                    'organization_name' => $isOrganizer ? $validated['organization_name'] : null,
                    'office_address' => $isOrganizer ? $validated['office_address'] : null,
                    'eo_description' => $isOrganizer ? $validated['eo_description'] : null,
                    'password' => Hash::make($validated['password']),
                ]);

                $role = Role::firstOrCreate(['name' => $roleName]);
                $user->assignRole($role);

                return $user;
            });

            event(new Registered($user));

            $accountLabel = $isOrganizer ? 'Event Organizer (EO)' : 'Customer';

            return redirect()
                ->route('login')
                ->with('status', "Registrasi sebagai {$accountLabel} berhasil! Silakan masuk menggunakan akun Anda.");
        } catch (Throwable $e) {
            Log::error('User registration failed', [
                'email' => $validated['email'] ?? null,
                'account_type' => $validated['account_type'] ?? null,
                'exception' => $e->getMessage(),
            ]);

            return back()
                ->withInput($request->except(['password', 'password_confirmation']))
                ->with('register_error', 'Terjadi kesalahan saat memproses registrasi. Silakan coba lagi.');
        }
    }
}
