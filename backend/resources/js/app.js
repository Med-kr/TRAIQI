import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

const traiqiConfig = window.traiqiConfig || {
    locale: 'ar',
    locales: {},
    routes: {},
    csrfToken: '',
};

const persistTheme = (theme) => {
    document.documentElement.dataset.theme = theme;
    document.documentElement.classList.toggle('dark', theme === 'dark');
    localStorage.setItem('traiqi.theme', theme);
};

const persistLocaleDirection = (locale) => {
    const localeConfig = traiqiConfig.locales[locale] || traiqiConfig.locales[traiqiConfig.locale] || {};
    document.documentElement.lang = locale;
    document.documentElement.dir = localeConfig.dir || 'ltr';
    document.documentElement.dataset.font = localeConfig.font || 'latin';
    localStorage.setItem('traiqi.locale', locale);
};

document.addEventListener('alpine:init', () => {
    Alpine.store('traiqi', {
        theme: localStorage.getItem('traiqi.theme')
            || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'),
        locale: localStorage.getItem('traiqi.locale') || traiqiConfig.locale,
        sidebarOpen: false,
        sidebarCollapsed: localStorage.getItem('traiqi.sidebar') === 'collapsed',
        languageOpen: false,
        profileMenuOpen: false,

        init() {
            persistTheme(this.theme);
            persistLocaleDirection(this.locale);
        },

        toggleTheme() {
            this.theme = this.theme === 'dark' ? 'light' : 'dark';
            persistTheme(this.theme);
        },

        async switchLanguage(locale) {
            if (!traiqiConfig.locales[locale] || locale === this.locale) {
                this.languageOpen = false;
                return;
            }

            this.locale = locale;
            persistLocaleDirection(locale);

            await fetch(traiqiConfig.routes.locale, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': traiqiConfig.csrfToken,
                },
                body: JSON.stringify({ locale }),
            });

            window.location.reload();
        },

        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
        },

        closeSidebar() {
            this.sidebarOpen = false;
        },

        toggleSidebarMode() {
            this.sidebarCollapsed = !this.sidebarCollapsed;
            localStorage.setItem('traiqi.sidebar', this.sidebarCollapsed ? 'collapsed' : 'expanded');
        },

        async markNotificationAsRead(id, endpoint) {
            await fetch(endpoint, {
                method: 'PATCH',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': traiqiConfig.csrfToken,
                },
            });

            window.location.reload();
        },
    });
});

Alpine.start();
