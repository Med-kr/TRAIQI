@extends('layouts.teacher')

@section('title', 'Créer évaluation')

@section('content')
    <section class="space-y-6 lg:space-y-8">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[#0A4FAF]">Nouvelle évaluation</p>
            <h1 class="mt-2 text-3xl font-semibold text-slate-950 sm:text-4xl">Créer une évaluation</h1>
            <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600">Choisissez uniquement une classe et une matière qui vous sont affectées.</p>
        </div>

        <section class="admin-card p-6 sm:p-7">
            @if ($assignments->isEmpty())
                <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-5 text-sm text-slate-500">
                    Aucune affectation trouvée. L'administration doit d'abord vous affecter à une classe et une matière.
                </div>
            @else
                <form method="POST" action="{{ route('teacher.evaluations.store') }}" class="grid gap-5 md:grid-cols-2">
                    @csrf

                    <div class="md:col-span-2">
                        <x-input-label for="title" value="Titre" />
                        <x-text-input id="title" name="title" type="text" class="mt-2 block w-full" :value="old('title')" required />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="assignment_key" value="Classe et matière" />
                        <select id="assignment_key" class="admin-toolbar-input mt-2" onchange="const [classroom, subject] = this.value.split('|'); document.getElementById('classroom_id').value = classroom; document.getElementById('subject_id').value = subject;">
                            @foreach ($assignments as $assignment)
                                <option value="{{ $assignment->classroom_id }}|{{ $assignment->subject_id }}">
                                    {{ $assignment->classroom?->name ?? 'Classe' }} - {{ $assignment->subject?->name ?? 'Matière' }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" id="classroom_id" name="classroom_id" value="{{ old('classroom_id', $assignments->first()->classroom_id) }}">
                        <input type="hidden" id="subject_id" name="subject_id" value="{{ old('subject_id', $assignments->first()->subject_id) }}">
                        <x-input-error :messages="$errors->get('classroom_id')" class="mt-2" />
                        <x-input-error :messages="$errors->get('subject_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="date" value="Date" />
                        <x-text-input id="date" name="date" type="date" class="mt-2 block w-full" :value="old('date', now()->format('Y-m-d'))" required />
                        <x-input-error :messages="$errors->get('date')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="type" value="Type" />
                        <select id="type" name="type" class="admin-toolbar-input mt-2" required>
                            <option value="devoir" @selected(old('type') === 'devoir')>Devoir</option>
                            <option value="examen" @selected(old('type') === 'examen')>Examen</option>
                            <option value="controle_continu" @selected(old('type') === 'controle_continu')>Contrôle continu</option>
                        </select>
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>

                    <div class="flex items-end gap-3">
                        <x-button type="submit">Créer</x-button>
                        <x-button href="{{ route('teacher.dashboard') }}" variant="secondary">Annuler</x-button>
                    </div>
                </form>
            @endif
        </section>
    </section>
@endsection
