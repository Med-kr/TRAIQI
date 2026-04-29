@extends('layouts.student')

@section('title', 'Profil élève')

@section('content')
    <section class="space-y-6 lg:space-y-8">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#0A4FAF]">Compte élève</p>
            <h1 class="mt-2 text-3xl font-semibold text-slate-950 sm:text-4xl">Mon profil</h1>
            <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Gère tes informations personnelles et la sécurité de ton compte.</p>
        </div>

        <div class="grid items-start gap-6 xl:grid-cols-[0.9fr_1.1fr]">
            <section class="admin-card p-6 sm:p-7">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Identité</p>
                <div class="mt-5 flex items-center gap-4">
                    <span class="flex h-16 w-16 items-center justify-center rounded-3xl bg-gradient-to-br from-[#0A4FAF] to-[#18A558] text-xl font-semibold text-white">
                        {{ \Illuminate\Support\Str::of($user->name)->explode(' ')->map(fn ($part) => mb_substr($part, 0, 1))->take(2)->implode('') }}
                    </span>
                    <div>
                        <p class="text-xl font-semibold text-slate-950">{{ $user->name }}</p>
                        <p class="mt-1 text-sm text-slate-500">{{ $user->email }}</p>
                        <p class="mt-1 text-sm text-slate-500">{{ $user->studentProfile?->classroom?->name ?? 'Classe non renseignée' }}</p>
                        <p class="mt-1 text-sm text-slate-500">{{ $user->studentProfile?->phone ?? 'Téléphone non renseigné' }}</p>
                    </div>
                </div>
            </section>

            <div class="space-y-6">
                <section class="admin-card p-6 sm:p-7">
                    @include('profile.partials.update-profile-information-form', ['user' => $user])
                </section>

                <section class="admin-card p-6 sm:p-7">
                    @include('profile.partials.update-password-form')
                </section>

                <section class="admin-card p-6 sm:p-7">
                    @include('profile.partials.delete-user-form')
                </section>
            </div>
        </div>
    </section>
@endsection
