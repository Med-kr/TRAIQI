import Alpine from 'alpinejs';
import ar from '../translations/ar.json';
import fr from '../translations/fr.json';
import en from '../translations/en.json';
import tz from '../translations/tz.json';

export const LANGUAGES = {
    ar: { label: 'العربية', dir: 'rtl', font: 'var(--font-arabic)', flag: '🇲🇦' },
    fr: { label: 'Français', dir: 'ltr', font: 'var(--font-latin)', flag: '🇫🇷' },
    en: { label: 'English', dir: 'ltr', font: 'var(--font-latin)', flag: '🌐' },
    tz: { label: 'ⵜⴰⵎⴰⵣⵉⵖⵜ', dir: 'ltr', font: 'var(--font-tifinagh)', flag: '⵿' },
};

const translations = { ar, fr, en, tz };

const getValue = (object, path) => path.split('.').reduce((current, key) => current?.[key], object);

const syncDocument = (lang, theme) => {
    const config = LANGUAGES[lang] ?? LANGUAGES.fr;
    document.documentElement.setAttribute('lang', lang);
    document.documentElement.setAttribute('dir', config.dir);
    document.documentElement.setAttribute('data-theme', theme);
    document.documentElement.style.setProperty('--active-font', config.font);
};

export function registerTraiqiStore() {
    const savedLang = localStorage.getItem('traiqi_lang');
    const savedTheme = localStorage.getItem('traiqi_theme') || document.documentElement.getAttribute('data-theme') || 'light';
    const lang = savedLang && LANGUAGES[savedLang] ? savedLang : 'fr';

    syncDocument(lang, savedTheme);

    Alpine.store('traiqi', {
        lang,
        theme: savedTheme,
        sidebarOpen: false,
        sidebarCollapsed: window.innerWidth < 1280 && window.innerWidth > 768,
        languages: LANGUAGES,
        translations,

        t(key) {
            const current = getValue(this.translations[this.lang], key);
            const frenchFallback = getValue(this.translations.fr, key);

            if (this.lang === 'tz') {
                return current ?? frenchFallback ?? key;
            }

            return current ?? frenchFallback ?? key;
        },

        languageDirection(langCode = this.lang) {
            return this.languages[langCode]?.dir === 'rtl' ? this.t('lang.direction_rtl') : this.t('lang.direction_ltr');
        },

        applyLanguage(langCode) {
            if (!this.languages[langCode]) {
                return;
            }

            this.lang = langCode;
            localStorage.setItem('traiqi_lang', langCode);
            syncDocument(langCode, this.theme);
            window.dispatchEvent(new CustomEvent('traiqi:language-changed', { detail: { lang: langCode } }));
        },

        toggleTheme() {
            this.theme = this.theme === 'dark' ? 'light' : 'dark';
            localStorage.setItem('traiqi_theme', this.theme);
            document.documentElement.setAttribute('data-theme', this.theme);
        },

        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
        },

        closeSidebar() {
            this.sidebarOpen = false;
        },

        toggleCollapse() {
            this.sidebarCollapsed = !this.sidebarCollapsed;
        },
    });

    window.addEventListener('resize', () => {
        const store = Alpine.store('traiqi');

        if (window.innerWidth <= 768) {
            store.sidebarOpen = false;
        }

        if (window.innerWidth < 1280 && window.innerWidth > 768) {
            store.sidebarCollapsed = true;
        }
    });
}
