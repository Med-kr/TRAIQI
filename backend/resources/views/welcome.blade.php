<x-guest-layout>
    @section('title', 'Traiqi')

    @php
        $features = [
            [
                'title' => 'Gestion scolaire simple',
                'copy' => 'Centralisez les utilisateurs, les classes, les matières et les années académiques dans une interface nette et fiable.',
            ],
            [
                'title' => 'Notes & évaluations',
                'copy' => 'Organisez les évaluations, publiez les résultats et suivez la progression de chaque élève sans friction.',
            ],
            [
                'title' => 'Communication parents',
                'copy' => 'Gardez un lien clair entre établissement et familles avec notifications, suivi et demandes de révision.',
            ],
            [
                'title' => 'Dashboards intelligents',
                'copy' => 'Chaque rôle dispose d’un espace lisible pour décider vite, enseigner mieux et accompagner avec précision.',
            ],
        ];

        $reasons = [
            'Architecture multi-école pensée pour la clarté, la sécurité et la croissance.',
            'Expérience fluide pour administration, enseignants, parents et élèves.',
            'Design premium inspiré par la confiance institutionnelle et l’élan de l’innovation.',
        ];

        $stats = [
            ['value' => '12+', 'label' => 'flux scolaires harmonisés'],
            ['value' => '98%', 'label' => 'visibilité sur les actions critiques'],
            ['value' => '24/7', 'label' => 'accès aux informations essentielles'],
            ['value' => '4 rôles', 'label' => 'espaces conçus pour chaque profil'],
        ];

        $testimonials = [
            [
                'name' => 'Nadia El Mansouri',
                'role' => 'Direction pédagogique',
                'quote' => 'Traiqi nous donne enfin une lecture simple, élégante et crédible de la vie scolaire au quotidien.',
            ],
            [
                'name' => 'Youssef Ait Lahcen',
                'role' => 'Enseignant',
                'quote' => 'Les évaluations et les notes sont plus rapides à gérer, et les parents comprennent mieux ce qui se passe.',
            ],
            [
                'name' => 'Salma B.',
                'role' => 'Parent d’élève',
                'quote' => 'Je retrouve l’essentiel sans me perdre. Les informations importantes sont là, au bon moment.',
            ],
        ];
    @endphp

    <div class="marketing-shell">
        <section class="marketing-section overflow-hidden pt-8 sm:pt-10 lg:pt-12">
            <div class="marketing-container">
                <div class="relative overflow-hidden rounded-[2rem] bg-traiqi-hero px-6 py-8 text-white shadow-[0_24px_80px_rgba(8,59,130,0.22)] sm:px-8 sm:py-10 lg:px-12 lg:py-14">
                    <span class="marketing-orbit -end-24 top-10 h-56 w-56"></span>
                    <span class="marketing-orbit -start-16 bottom-12 h-40 w-40"></span>

                    <div class="relative grid gap-10 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
                        <div class="space-y-7">
                            <span class="inline-flex items-center gap-2 rounded-full bg-white/12 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-white/88 backdrop-blur">
                                Education, innovation, confiance
                            </span>

                            <div class="flex items-start gap-4 sm:gap-5">
                                <span class="flex h-20 w-20 shrink-0 items-center justify-center rounded-[1.75rem] bg-white/12 p-3 backdrop-blur sm:h-24 sm:w-24">
                                    <x-application-logo class="h-full w-full" />
                                </span>

                                <div class="space-y-4">
                                    <p class="text-sm font-semibold uppercase tracking-[0.28em] text-white/72">Traiqi | طريقي</p>
                                    <h1 class="marketing-heading max-w-3xl text-white">Traiqi — Plateforme éducative intelligente</h1>
                                </div>
                            </div>

                            <div class="max-w-3xl space-y-3 text-base leading-8 text-white/86 sm:text-lg">
                                <p>Une expérience scolaire plus fluide pour les établissements, les enseignants et les familles.</p>
                                <p dir="rtl" class="font-arabic text-right text-white/82">منصة تعليمية حديثة تساعد المؤسسة والأسرة على متابعة التقدم بثقة ووضوح.</p>
                            </div>

                            <div class="flex flex-col gap-3 sm:flex-row">
                                <x-button href="#cta-banner" size="lg">Commencer</x-button>
                                <x-button :href="route('login')" variant="secondary" size="lg">Se connecter</x-button>
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="marketing-glass p-5 sm:col-span-2">
                                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-white/72">Vision</p>
                                <p class="mt-3 text-xl font-semibold leading-8">Un langage visuel de progression, de rigueur et d’avenir pour accompagner chaque parcours scolaire.</p>
                            </div>

                            <div class="marketing-glass p-5">
                                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-white/72">Multi-rôles</p>
                                <p class="mt-3 text-3xl font-semibold">Admin, enseignant, parent, élève</p>
                            </div>

                            <div class="marketing-glass p-5">
                                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-white/72">Clarté</p>
                                <p class="mt-3 text-3xl font-semibold">Données lisibles, actions rapides</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="features" class="marketing-section py-16 sm:py-20">
            <div class="marketing-container">
                <div class="max-w-3xl">
                    <span class="marketing-kicker">Fonctionnalités</span>
                    <h2 class="mt-5 marketing-subheading text-slate-950">Une base solide pour piloter la vie scolaire avec exigence et simplicité.</h2>
                    <p class="mt-4 text-base leading-8 text-slate-600">Chaque module répond à un usage réel du terrain: organiser, communiquer, mesurer, décider.</p>
                </div>

                <div class="marketing-grid mt-10 md:grid-cols-2 xl:grid-cols-4">
                    @foreach ($features as $feature)
                        <article class="marketing-card p-6 transition duration-300 hover:-translate-y-1 hover:shadow-[0_22px_48px_rgba(15,23,42,0.12)]">
                            <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-[#0A4FAF]/8 text-[#0A4FAF]">
                                <span class="h-2.5 w-2.5 rounded-full bg-[#18A558]"></span>
                            </span>
                            <h3 class="mt-5 text-xl font-semibold text-slate-950">{{ $feature['title'] }}</h3>
                            <p class="mt-3 text-sm leading-7 text-slate-600">{{ $feature['copy'] }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section id="why-traiqi" class="marketing-section pb-16 sm:pb-20">
            <div class="marketing-container">
                <div class="grid gap-6 lg:grid-cols-[0.95fr_1.05fr]">
                    <div class="marketing-card p-8 sm:p-10">
                        <span class="marketing-kicker">Pourquoi choisir Traiqi</span>
                        <h2 class="mt-5 marketing-subheading text-slate-950">Une plateforme pensée pour durer, pas juste pour impressionner.</h2>
                        <div class="mt-8 grid gap-5">
                            @foreach ($reasons as $reason)
                                <div class="flex gap-4 rounded-2xl bg-slate-50 p-4">
                                    <span class="mt-1 inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-[#18A558]/12 text-[#18A558]">
                                        <span class="h-2 w-2 rounded-full bg-current"></span>
                                    </span>
                                    <p class="text-sm leading-7 text-slate-700">{{ $reason }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="marketing-muted overflow-hidden p-8 sm:p-10">
                        <div class="grid gap-6">
                            <div class="rounded-[1.5rem] bg-white p-6 shadow-sm">
                                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-[#0A4FAF]">Approche</p>
                                <p class="mt-3 text-lg font-semibold text-slate-950">Entre élégance marocaine et précision EdTech.</p>
                                <p class="mt-3 text-sm leading-7 text-slate-600">Courbes, respiration, hiérarchie visuelle nette, palette institutionnelle vivante: tout est calibré pour inspirer sérieux et élan.</p>
                            </div>
                            <div class="grid gap-5 sm:grid-cols-2">
                                <div class="rounded-[1.5rem] bg-[#083B82] p-6 text-white">
                                    <p class="text-sm uppercase tracking-[0.18em] text-white/70">Confiance</p>
                                    <p class="mt-3 text-2xl font-semibold">Sécurité par rôle</p>
                                </div>
                                <div class="rounded-[1.5rem] bg-[#D9A441] p-6 text-slate-950">
                                    <p class="text-sm uppercase tracking-[0.18em] text-slate-800/70">Avenir</p>
                                    <p class="mt-3 text-2xl font-semibold">Pilotage orienté progression</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="stats" class="marketing-section pb-16 sm:pb-20">
            <div class="marketing-container">
                <div class="marketing-card overflow-hidden bg-traiqi-gold p-8 sm:p-10">
                    <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                        <div class="max-w-2xl">
                            <span class="marketing-kicker">Indicateurs</span>
                            <h2 class="mt-5 marketing-subheading text-slate-950">Une plateforme conçue pour rendre la progression visible.</h2>
                        </div>
                        <p class="max-w-xl text-sm leading-7 text-slate-700">Des tableaux de bord utiles, des flux plus calmes, une communication mieux structurée et une lecture plus claire de la performance.</p>
                    </div>

                    <div class="mt-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                        @foreach ($stats as $stat)
                            <div class="rounded-[1.5rem] bg-white/92 p-5 shadow-sm">
                                <p class="text-3xl font-semibold text-slate-950 sm:text-4xl">{{ $stat['value'] }}</p>
                                <p class="mt-2 text-sm text-slate-600">{{ $stat['label'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section id="testimonials" class="marketing-section pb-16 sm:pb-20">
            <div class="marketing-container">
                <div class="max-w-3xl">
                    <span class="marketing-kicker">Témoignages</span>
                    <h2 class="mt-5 marketing-subheading text-slate-950">Des retours qui parlent de confiance, de clarté et de sérénité.</h2>
                </div>

                <div class="marketing-grid mt-10 lg:grid-cols-3">
                    @foreach ($testimonials as $testimonial)
                        <article class="marketing-card p-7">
                            <p class="text-base leading-8 text-slate-700">“{{ $testimonial['quote'] }}”</p>
                            <div class="mt-6 border-t border-slate-200 pt-5">
                                <h3 class="font-semibold text-slate-950">{{ $testimonial['name'] }}</h3>
                                <p class="mt-1 text-sm text-slate-500">{{ $testimonial['role'] }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section id="cta-banner" class="marketing-section pb-16 sm:pb-20">
            <div class="marketing-container">
                <div class="overflow-hidden rounded-[2rem] bg-[#083B82] px-6 py-8 text-white shadow-[0_24px_70px_rgba(8,59,130,0.24)] sm:px-8 sm:py-10 lg:px-12 lg:py-12">
                    <div class="grid gap-6 lg:grid-cols-[1fr_auto] lg:items-center">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-white/70">Prêt à avancer</p>
                            <h2 class="mt-3 text-3xl font-semibold leading-tight sm:text-4xl">Offrez à votre établissement une expérience éducative à la hauteur de son ambition.</h2>
                            <p class="mt-4 max-w-2xl text-base leading-8 text-white/78">Traiqi réunit structure, visibilité et élégance pour accompagner l’apprentissage, la coordination et la décision.</p>
                        </div>

                        <div class="flex flex-col gap-3 sm:flex-row lg:flex-col">
                            <x-button :href="route('login')" size="lg">Se connecter</x-button>
                            <x-button href="#features" variant="secondary" size="lg">Découvrir la plateforme</x-button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-guest-layout>
