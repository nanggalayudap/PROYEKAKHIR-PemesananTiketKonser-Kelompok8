<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'account_type' => ['required', 'string', Rule::in(['customer', 'eo'])],
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'alpha_dash', 'min:3', 'max:50', 'unique:'.User::class.',username'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class.',email'],
            'whatsapp' => ['required', 'string', 'min:9', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'organization_name' => ['required_if:account_type,eo', 'nullable', 'string', 'max:255'],
            'office_address' => ['required_if:account_type,eo', 'nullable', 'string', 'max:500'],
            'eo_description' => ['required_if:account_type,eo', 'nullable', 'string', 'max:1000'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'account_type' => 'jenis akun',
            'name' => $this->input('account_type') === 'eo' ? 'nama penanggung jawab' : 'nama lengkap',
            'username' => 'username',
            'email' => $this->input('account_type') === 'eo' ? 'email resmi' : 'email',
            'whatsapp' => 'nomor WhatsApp',
            'organization_name' => 'nama perusahaan atau organisasi EO',
            'office_address' => 'alamat kantor',
            'eo_description' => 'deskripsi singkat EO',
            'password' => 'password',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'account_type.in' => 'Pilihan jenis akun tidak valid.',
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, tanda hubung (-), dan garis bawah (_).',
            'username.unique' => 'Username ini sudah digunakan. Silakan pilih username lain.',
            'email.unique' => 'Email ini sudah terdaftar. Silakan gunakan email lain atau login.',
            'whatsapp.regex' => 'Format nomor WhatsApp tidak valid.',
            'organization_name.required_if' => 'Nama perusahaan atau organisasi EO wajib diisi.',
            'office_address.required_if' => 'Alamat kantor EO wajib diisi.',
            'eo_description.required_if' => 'Deskripsi singkat EO wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ];
    }
}
