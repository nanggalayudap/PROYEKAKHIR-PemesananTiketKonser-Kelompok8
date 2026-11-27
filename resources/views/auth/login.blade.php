<x-guest-layout>
    <div
        x-data="{
            loginAs: 'customer',
            email: @js(old('email', '')),
            password: '',
            showPassword: false,
            capsLock: false,
            submitting: false,
            emailTouched: false,
            get emailInvalid() {
                return this.emailTouched && this.email.trim().length > 0 && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email.trim());
            }
        }"
    >
        <div class="mb-5 text-center">
            <h1 class="text-2xl font-extrabold tracking-tight text-[#23238E]">Masuk ke Eventra</h1>
            <p class="mt-1.5 text-sm text-[#646B8A]" x-text="loginAs === 'eo' ? 'Masuk ke dashboard Event Organizer untuk kelola konser & tiket.' : 'Kelola tiket konser dan pesananmu dengan mudah.'">
                Kelola tiket konser dan pesananmu dengan mudah.
            </p>
        </div>

        <!-- Account Mode Switcher (Customer / Event Organizer) -->
        <div class="mb-5 grid grid-cols-2 gap-2 rounded-2xl border border-[#E2E7F5] bg-[#F6F8FC] p-1.5">
            <button
                type="button"
                @click="loginAs = 'customer'"
                :aria-pressed="loginAs === 'customer'"
                :class="loginAs === 'customer'
                    ? 'bg-[#23238E] text-white shadow-sm'
                    : 'text-[#646B8A] hover:text-[#23238E]'"
                class="flex items-center justify-center gap-1.5 rounded-xl px-3 py-2 text-xs font-bold transition focus:outline-none"
            >
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
                <span>Customer</span>
            </button>
            <button
                type="button"
                @click="loginAs = 'eo'"
                :aria-pressed="loginAs === 'eo'"
                :class="loginAs === 'eo'
                    ? 'bg-[#23238E] text-white shadow-sm'
                    : 'text-[#646B8A] hover:text-[#23238E]'"
                class="flex items-center justify-center gap-1.5 rounded-xl px-3 py-2 text-xs font-bold transition focus:outline-none"
            >
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z" />
                    <path d="M13 5v2" />
                    <path d="M13 17v2" />
                    <path d="M13 11v2" />
                </svg>
                <span>Event Organizer</span>
            </button>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form
            method="POST"
            action="{{ route('login') }}"
            @submit="submitting = true"
            class="space-y-5"
        >
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-semibold text-[#18184A]" x-text="loginAs === 'eo' ? 'Email Resmi EO' : 'Email'">
                    Email
                </label>
                <div class="relative mt-1.5">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-[#646B8A]">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect width="20" height="16" x="2" y="4" rx="2" />
                            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
                        </svg>
                    </div>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        x-model="email"
                        @blur="emailTouched = true"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        :placeholder="loginAs === 'eo' ? 'official@perusahaan-eo.com' : 'nama@email.com'"
                        :class="emailInvalid ? 'border-rose-500 focus:border-rose-500 focus:ring-rose-300' : 'border-[#E2E7F5] focus:border-[#3C84D8] focus:ring-[#F5A4E3]'"
                        class="block w-full rounded-xl bg-[#F6F8FC]/60 py-2.5 pl-10 pr-3.5 text-sm text-[#18184A] placeholder-[#646B8A] shadow-sm transition focus:bg-white focus:outline-none focus:ring-2"
                    />
                </div>
                <p x-show="emailInvalid" x-cloak class="mt-1.5 text-xs font-medium text-[#BE123C]">
                    Format email sepertinya belum valid.
                </p>
                <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
            </div>

            <!-- Password -->
            <div>
                <div class="flex items-center justify-between">
                    <label for="password" class="block text-sm font-semibold text-[#18184A]">Password</label>
                    @if (Route::has('password.request'))
                        <a
                            href="{{ route('password.request') }}"
                            class="text-xs font-semibold text-[#3C84D8] hover:text-[#23238E] hover:underline focus:outline-none focus:ring-2 focus:ring-[#3C84D8] focus:ring-offset-2 rounded"
                        >
                            Lupa password?
                        </a>
                    @endif
                </div>

                <div class="relative mt-1.5">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-[#646B8A]">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect width="18" height="11" x="3" y="11" rx="2" ry="2" />
                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                        </svg>
                    </div>

                    <input
                        id="password"
                        :type="showPassword ? 'text' : 'password'"
                        name="password"
                        x-model="password"
                        @keydown="capsLock = $event.getModifierState && $event.getModifierState('CapsLock')"
                        @keyup="capsLock = $event.getModifierState && $event.getModifierState('CapsLock')"
                        @blur="capsLock = false"
                        required
                        autocomplete="current-password"
                        placeholder="Masukkan password"
                        class="block w-full rounded-xl border-[#E2E7F5] bg-[#F6F8FC]/60 py-2.5 pl-10 pr-11 text-sm text-[#18184A] placeholder-[#646B8A] shadow-sm transition focus:border-[#3C84D8] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#F5A4E3]"
                    />

                    <button
                        type="button"
                        @click="showPassword = !showPassword"
                        :aria-label="showPassword ? 'Sembunyikan password' : 'Tampilkan password'"
                        :aria-pressed="showPassword"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-[#646B8A] transition hover:text-[#23238E] focus:outline-none focus:text-[#3C84D8]"
                    >
                        <svg x-show="!showPassword" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                        <svg x-show="showPassword" x-cloak class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49" />
                            <path d="M14.084 14.158a3 3 0 0 1-4.242-4.242" />
                            <path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143" />
                            <path d="m2 2 20 20" />
                        </svg>
                    </button>
                </div>

                <p x-show="capsLock" x-cloak class="mt-1.5 inline-flex items-center gap-1.5 rounded-full bg-[#FCE2C1] px-2.5 py-0.5 text-xs font-semibold text-[#9A4D00]">
                    <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z" />
                        <path d="M12 9v4" />
                        <path d="M12 17h.01" />
                    </svg>
                    Caps Lock sedang aktif.
                </p>
                <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                    <input
                        id="remember_me"
                        type="checkbox"
                        name="remember"
                        class="h-4 w-4 rounded border-[#E2E7F5] text-[#23238E] shadow-sm focus:ring-[#3C84D8]"
                    >
                    <span class="ms-2 text-sm text-[#646B8A]">Ingat saya</span>
                </label>
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                :disabled="submitting"
                :class="{ 'opacity-75 cursor-not-allowed': submitting }"
                class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#23238E] px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-[#3C84D8] focus:outline-none focus:ring-2 focus:ring-[#3C84D8] focus:ring-offset-2 active:bg-[#18184A]"
            >
                <svg x-show="submitting" x-cloak class="h-4 w-4 animate-spin text-white" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 0 1 4 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span x-text="submitting ? 'Memproses...' : (loginAs === 'eo' ? 'Masuk sebagai Event Organizer' : 'Masuk sebagai Customer')">
                    Masuk sebagai Customer
                </span>
            </button>

            @if (Route::has('register'))
                <div class="border-t border-[#E2E7F5] pt-5 text-center text-sm text-[#646B8A]">
                    Belum punya akun?
                    <a
                        href="{{ route('register') }}"
                        class="font-semibold text-[#3C84D8] hover:text-[#23238E] hover:underline focus:outline-none focus:ring-2 focus:ring-[#3C84D8] focus:ring-offset-2 rounded"
                    >
                        Daftar sekarang
                    </a>
                </div>
            @endif
        </form>
    </div>
</x-guest-layout>
