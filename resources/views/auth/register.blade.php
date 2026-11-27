<x-guest-layout max-width="xl">
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-extrabold tracking-tight text-[#23238E]">Buat Akun Eventra</h1>
        <p class="mt-1.5 text-sm text-[#646B8A]">Pilih jenis akun yang sesuai dengan kebutuhanmu.</p>
    </div>

    @if (session('register_error'))
        <div class="mb-5 rounded-xl border border-[#BE123C]/20 bg-[#FFE4E6] px-4 py-3 text-sm font-medium text-[#BE123C]">
            {{ session('register_error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-5 rounded-xl border border-[#BE123C]/20 bg-[#FFE4E6] px-4 py-3 text-sm text-[#BE123C]">
            <p class="font-semibold">Registrasi gagal. Mohon periksa kembali data yang Anda masukkan.</p>
        </div>
    @endif

    <form
        method="POST"
        action="{{ route('register') }}"
        x-data="{
            accountType: @js(old('account_type', 'customer')),
            password: '',
            passwordConfirmation: '',
            showPassword: false,
            showConfirmPassword: false,
            submitting: false,
            get passwordMismatch() {
                return this.passwordConfirmation.length > 0 && this.password !== this.passwordConfirmation;
            }
        }"
        @submit="submitting = true"
        class="space-y-5"
    >
        @csrf
        <input type="hidden" name="account_type" :value="accountType">

        <!-- Account Type Selector Cards -->
        <div>
            <span class="block text-sm font-semibold text-[#18184A] mb-2.5">Daftar Sebagai</span>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <!-- Customer Card -->
                <button
                    type="button"
                    @click="accountType = 'customer'"
                    :aria-pressed="accountType === 'customer'"
                    :class="accountType === 'customer'
                        ? 'border-[#23238E] bg-[#FCE2C1]/30 ring-2 ring-[#F5A4E3] shadow-sm'
                        : 'border-[#E2E7F5] bg-[#F6F8FC]/60 hover:border-[#3C84D8] hover:bg-white'"
                    class="flex items-start gap-3 rounded-2xl border-2 p-3.5 text-left transition focus:outline-none"
                >
                    <span
                        :class="accountType === 'customer' ? 'bg-[#23238E] text-white' : 'bg-white text-[#3C84D8] border border-[#E2E7F5]'"
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl transition"
                    >
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                    </span>
                    <span class="min-w-0">
                        <span class="block text-sm font-bold text-[#18184A]">Customer</span>
                        <span class="mt-0.5 block text-xs leading-snug text-[#646B8A]">Cari konser, pesan tiket, dan lihat riwayat transaksi.</span>
                    </span>
                </button>

                <!-- Event Organizer Card -->
                <button
                    type="button"
                    @click="accountType = 'eo'"
                    :aria-pressed="accountType === 'eo'"
                    :class="accountType === 'eo'
                        ? 'border-[#23238E] bg-[#F5A4E3]/20 ring-2 ring-[#3C84D8]/40 shadow-sm'
                        : 'border-[#E2E7F5] bg-[#F6F8FC]/60 hover:border-[#3C84D8] hover:bg-white'"
                    class="flex items-start gap-3 rounded-2xl border-2 p-3.5 text-left transition focus:outline-none"
                >
                    <span
                        :class="accountType === 'eo' ? 'bg-[#23238E] text-white' : 'bg-white text-[#3C84D8] border border-[#E2E7F5]'"
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl transition"
                    >
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z" />
                            <path d="M13 5v2" />
                            <path d="M13 17v2" />
                            <path d="M13 11v2" />
                        </svg>
                    </span>
                    <span class="min-w-0">
                        <span class="block text-sm font-bold text-[#18184A]">Event Organizer</span>
                        <span class="mt-0.5 block text-xs leading-snug text-[#646B8A]">Buat konser, atur tiket, dan kelola pemesanan event.</span>
                    </span>
                </button>
            </div>
            <x-input-error :messages="$errors->get('account_type')" class="mt-1.5" />
        </div>

        <!-- Form Fields -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <!-- Name / PIC Name -->
            <div class="sm:col-span-2" :class="accountType === 'eo' ? 'sm:col-span-1' : 'sm:col-span-2'">
                <label for="name" class="block text-sm font-semibold text-[#18184A]" x-text="accountType === 'eo' ? 'Nama Penanggung Jawab' : 'Nama Lengkap'">
                    Nama Lengkap
                </label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    autocomplete="name"
                    :placeholder="accountType === 'eo' ? 'Nama lengkap PIC / penanggung jawab' : 'Masukkan nama lengkap'"
                    class="mt-1.5 block w-full rounded-xl border-[#E2E7F5] bg-[#F6F8FC]/60 px-3.5 py-2.5 text-sm text-[#18184A] placeholder-[#646B8A] shadow-sm transition focus:border-[#3C84D8] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#F5A4E3]"
                />
                <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
            </div>

            <!-- Organization Name (EO Only) -->
            <div x-show="accountType === 'eo'" x-cloak>
                <label for="organization_name" class="block text-sm font-semibold text-[#18184A]">Nama Perusahaan / Organisasi EO</label>
                <input
                    id="organization_name"
                    type="text"
                    name="organization_name"
                    value="{{ old('organization_name') }}"
                    :required="accountType === 'eo'"
                    :disabled="accountType !== 'eo'"
                    placeholder="Contoh: PT Harmoni Musik Nusantara"
                    class="mt-1.5 block w-full rounded-xl border-[#E2E7F5] bg-[#F6F8FC]/60 px-3.5 py-2.5 text-sm text-[#18184A] placeholder-[#646B8A] shadow-sm transition focus:border-[#3C84D8] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#F5A4E3]"
                />
                <x-input-error :messages="$errors->get('organization_name')" class="mt-1.5" />
            </div>

            <!-- Username -->
            <div>
                <label for="username" class="block text-sm font-semibold text-[#18184A]">Username</label>
                <input
                    id="username"
                    type="text"
                    name="username"
                    value="{{ old('username') }}"
                    required
                    autocomplete="username"
                    placeholder="contoh: budi_santoso"
                    class="mt-1.5 block w-full rounded-xl border-[#E2E7F5] bg-[#F6F8FC]/60 px-3.5 py-2.5 text-sm text-[#18184A] placeholder-[#646B8A] shadow-sm transition focus:border-[#3C84D8] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#F5A4E3]"
                />
                <x-input-error :messages="$errors->get('username')" class="mt-1.5" />
            </div>

            <!-- WhatsApp -->
            <div>
                <label for="whatsapp" class="block text-sm font-semibold text-[#18184A]">Nomor WhatsApp</label>
                <input
                    id="whatsapp"
                    type="tel"
                    name="whatsapp"
                    value="{{ old('whatsapp') }}"
                    required
                    placeholder="081234567890"
                    class="mt-1.5 block w-full rounded-xl border-[#E2E7F5] bg-[#F6F8FC]/60 px-3.5 py-2.5 text-sm text-[#18184A] placeholder-[#646B8A] shadow-sm transition focus:border-[#3C84D8] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#F5A4E3]"
                />
                <x-input-error :messages="$errors->get('whatsapp')" class="mt-1.5" />
            </div>

            <!-- Email -->
            <div class="sm:col-span-2">
                <label for="email" class="block text-sm font-semibold text-[#18184A]" x-text="accountType === 'eo' ? 'Email Resmi' : 'Email'">
                    Email
                </label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                    :placeholder="accountType === 'eo' ? 'official@perusahaan-eo.com' : 'nama@email.com'"
                    class="mt-1.5 block w-full rounded-xl border-[#E2E7F5] bg-[#F6F8FC]/60 px-3.5 py-2.5 text-sm text-[#18184A] placeholder-[#646B8A] shadow-sm transition focus:border-[#3C84D8] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#F5A4E3]"
                />
                <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
            </div>

            <!-- Office Address (EO Only) -->
            <div x-show="accountType === 'eo'" x-cloak class="sm:col-span-2">
                <label for="office_address" class="block text-sm font-semibold text-[#18184A]">Alamat Kantor</label>
                <textarea
                    id="office_address"
                    name="office_address"
                    rows="2"
                    :required="accountType === 'eo'"
                    :disabled="accountType !== 'eo'"
                    placeholder="Masukkan alamat lengkap kantor EO"
                    class="mt-1.5 block w-full rounded-xl border-[#E2E7F5] bg-[#F6F8FC]/60 px-3.5 py-2.5 text-sm text-[#18184A] placeholder-[#646B8A] shadow-sm transition focus:border-[#3C84D8] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#F5A4E3]"
                >{{ old('office_address') }}</textarea>
                <x-input-error :messages="$errors->get('office_address')" class="mt-1.5" />
            </div>

            <!-- Short EO Description (EO Only) -->
            <div x-show="accountType === 'eo'" x-cloak class="sm:col-span-2">
                <label for="eo_description" class="block text-sm font-semibold text-[#18184A]">Deskripsi Singkat EO</label>
                <textarea
                    id="eo_description"
                    name="eo_description"
                    rows="2"
                    :required="accountType === 'eo'"
                    :disabled="accountType !== 'eo'"
                    placeholder="Ceritakan secara singkat profil dan pengalaman event organizer Anda"
                    class="mt-1.5 block w-full rounded-xl border-[#E2E7F5] bg-[#F6F8FC]/60 px-3.5 py-2.5 text-sm text-[#18184A] placeholder-[#646B8A] shadow-sm transition focus:border-[#3C84D8] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#F5A4E3]"
                >{{ old('eo_description') }}</textarea>
                <x-input-error :messages="$errors->get('eo_description')" class="mt-1.5" />
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-semibold text-[#18184A]">Password</label>
                <div class="relative mt-1.5">
                    <input
                        id="password"
                        :type="showPassword ? 'text' : 'password'"
                        name="password"
                        x-model="password"
                        required
                        autocomplete="new-password"
                        placeholder="Minimal 8 karakter"
                        class="block w-full rounded-xl border-[#E2E7F5] bg-[#F6F8FC]/60 py-2.5 pl-3.5 pr-10 text-sm text-[#18184A] placeholder-[#646B8A] shadow-sm transition focus:border-[#3C84D8] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#F5A4E3]"
                    />
                    <button
                        type="button"
                        @click="showPassword = !showPassword"
                        :aria-label="showPassword ? 'Sembunyikan password' : 'Tampilkan password'"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-[#646B8A] hover:text-[#23238E] focus:outline-none"
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
                <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-[#18184A]">Konfirmasi Password</label>
                <div class="relative mt-1.5">
                    <input
                        id="password_confirmation"
                        :type="showConfirmPassword ? 'text' : 'password'"
                        name="password_confirmation"
                        x-model="passwordConfirmation"
                        required
                        autocomplete="new-password"
                        placeholder="Ulangi password"
                        :class="passwordMismatch ? 'border-rose-500 focus:border-rose-500 focus:ring-rose-300' : 'border-[#E2E7F5] focus:border-[#3C84D8] focus:ring-[#F5A4E3]'"
                        class="block w-full rounded-xl bg-[#F6F8FC]/60 py-2.5 pl-3.5 pr-10 text-sm text-[#18184A] placeholder-[#646B8A] shadow-sm transition focus:bg-white focus:outline-none focus:ring-2"
                    />
                    <button
                        type="button"
                        @click="showConfirmPassword = !showConfirmPassword"
                        :aria-label="showConfirmPassword ? 'Sembunyikan konfirmasi password' : 'Tampilkan konfirmasi password'"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-[#646B8A] hover:text-[#23238E] focus:outline-none"
                    >
                        <svg x-show="!showConfirmPassword" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                        <svg x-show="showConfirmPassword" x-cloak class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49" />
                            <path d="M14.084 14.158a3 3 0 0 1-4.242-4.242" />
                            <path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143" />
                            <path d="m2 2 20 20" />
                        </svg>
                    </button>
                </div>
                <p x-show="passwordMismatch" x-cloak class="mt-1.5 text-xs font-medium text-[#BE123C]">
                    Konfirmasi password belum sesuai.
                </p>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
            </div>
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
            <span x-text="submitting ? 'Memproses...' : (accountType === 'eo' ? 'Daftar sebagai Event Organizer' : 'Daftar sebagai Customer')">
                Daftar sebagai Customer
            </span>
        </button>

        <div class="border-t border-[#E2E7F5] pt-4 text-center text-sm text-[#646B8A]">
            Sudah punya akun?
            <a
                href="{{ route('login') }}"
                class="font-semibold text-[#3C84D8] hover:text-[#23238E] hover:underline focus:outline-none focus:ring-2 focus:ring-[#3C84D8] focus:ring-offset-2 rounded"
            >
                Masuk di sini
            </a>
        </div>
    </form>
</x-guest-layout>
