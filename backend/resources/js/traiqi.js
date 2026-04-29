const registerTraiqiStores = () => {
  if (window.__traiqiStoresRegistered__) {
    return;
  }

  window.__traiqiStoresRegistered__ = true;
  const alpine = window.Alpine;

  alpine.store('ui', {
    mobileSidebarOpen: false,
    toggleSidebar() {
      this.mobileSidebarOpen = !this.mobileSidebarOpen;
    },
    closeSidebar() {
      this.mobileSidebarOpen = false;
    },
  });

  alpine.store('i18n', {
    langs: {
      ar: { label: 'العربية', dir: 'rtl', font: "'Cairo', sans-serif", flag: '🇲🇦' },
      fr: { label: 'Français', dir: 'ltr', font: "'Outfit', sans-serif", flag: '🇫🇷' },
      en: { label: 'English', dir: 'ltr', font: "'Outfit', sans-serif", flag: '🌐' },
      tz: { label: 'ⵜⴰⵎⴰⵣⵉⵖⵜ', dir: 'ltr', font: "'Noto Sans Tifinagh', sans-serif", flag: '⵿' },
    },
    translations: {},
    current: localStorage.getItem('traiqi_lang') || 'fr',

    async init() {
      await this.load('fr');
      await this.load(this.current);
      this.apply(this.current);
    },

    async load(code) {
      if (!this.translations[code]) {
        const response = await fetch(`/lang/${code}.json`);
        this.translations[code] = await response.json();
      }
    },

    async switch(code) {
      await this.load(code);
      this.current = code;
      localStorage.setItem('traiqi_lang', code);
      this.apply(code);
    },

    apply(code) {
      const cfg = this.langs[code];
      document.documentElement.setAttribute('lang', code);
      document.documentElement.setAttribute('dir', cfg.dir);
      document.documentElement.style.setProperty('--active-font', cfg.font);
    },

    t(key) {
      const currentTranslations = this.translations[this.current] || {};
      const fallbackTranslations = this.translations.fr || {};

      return currentTranslations[key] || fallbackTranslations[key] || key;
    },
  });

  alpine.store('theme', {
    current: localStorage.getItem('traiqi_theme') || 'light',
    init() {
      this.apply(this.current);
    },
    toggle() {
      this.current = this.current === 'light' ? 'dark' : 'light';
      localStorage.setItem('traiqi_theme', this.current);
      this.apply(this.current);
    },
    apply(theme) {
      document.documentElement.setAttribute('data-theme', theme);
    },
  });
};

if (window.Alpine) {
  registerTraiqiStores();
} else {
  document.addEventListener('alpine:init', registerTraiqiStores, { once: true });
}
