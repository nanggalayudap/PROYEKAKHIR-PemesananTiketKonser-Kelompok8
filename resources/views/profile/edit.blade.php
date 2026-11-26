<x-eventra-layout title="Profil">
    <h1 class="text-2xl font-bold tracking-tight">Profil</h1>

    <div class="mx-auto mt-6 max-w-2xl space-y-6">
        <div class="rounded-lg border border-stone-200 bg-white p-6">
            @include('profile.partials.update-profile-information-form')
        </div>
        <div class="rounded-lg border border-stone-200 bg-white p-6">
            @include('profile.partials.update-password-form')
        </div>
        <div class="rounded-lg border border-stone-200 bg-white p-6">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-eventra-layout>