/**
 * Modo diurno/noturno (mesmo comportamento do SVO/FAMA).
 *
 * - O tema vai em <html data-theme="light|dark">. Um script inline no <head> já aplica o tema
 *   salvo antes da pintura (sem "piscar"); este módulo cuida do botão e das mudanças.
 * - Qualquer elemento com [data-theme-toggle] alterna o tema.
 * - Sem escolha salva, segue o sistema operacional e acompanha se ele mudar.
 * - Dispara "theme:changed" no document (detail.theme) para quem precisar reagir.
 * - Atualiza os relógios [data-topbar-datetime] a cada minuto.
 */
export const THEME_STORAGE_KEY = 'almoxarifado-theme';
const THEME_ATTRIBUTE = 'data-theme';

const titles = {
    light: 'Alternar para o modo noturno',
    dark: 'Alternar para o modo diurno',
};

const readStoredTheme = () => {
    try {
        const theme = localStorage.getItem(THEME_STORAGE_KEY);

        return theme === 'light' || theme === 'dark' ? theme : null;
    } catch {
        return null;
    }
};

const storeTheme = (theme) => {
    try {
        localStorage.setItem(THEME_STORAGE_KEY, theme);
    } catch {
        // navegação privada ou armazenamento bloqueado: o tema vale só nesta página
    }
};

export const currentTheme = () =>
    document.documentElement.getAttribute(THEME_ATTRIBUTE) === 'dark' ? 'dark' : 'light';

const updateButtons = (theme) => {
    const title = titles[theme] ?? titles.light;

    document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
        button.setAttribute('aria-label', title);
        button.setAttribute('aria-pressed', String(theme === 'dark'));
        button.setAttribute('title', title);
    });
};

const applyTheme = (theme, persist = true) => {
    document.documentElement.setAttribute(THEME_ATTRIBUTE, theme);
    updateButtons(theme);

    if (persist) {
        storeTheme(theme);
    }

    document.dispatchEvent(new CustomEvent('theme:changed', { detail: { theme } }));
};

const formatNow = () =>
    new Intl.DateTimeFormat('pt-BR', {
        day: '2-digit',
        month: 'short',
        hour: '2-digit',
        minute: '2-digit',
    })
        .format(new Date())
        .replace('.', '');

const updateClocks = () => {
    document.querySelectorAll('[data-topbar-datetime]').forEach((element) => {
        element.textContent = formatNow();
    });
};

const init = () => {
    applyTheme(readStoredTheme() ?? currentTheme(), false);
    updateClocks();
    window.setInterval(updateClocks, 60_000);

    document.addEventListener('click', (event) => {
        const button = event.target.closest('[data-theme-toggle]');

        if (!button) {
            return;
        }

        event.preventDefault();
        applyTheme(currentTheme() === 'dark' ? 'light' : 'dark');
    });

    const media = window.matchMedia?.('(prefers-color-scheme: dark)');

    media?.addEventListener?.('change', (event) => {
        if (!readStoredTheme()) {
            applyTheme(event.matches ? 'dark' : 'light', false);
        }
    });
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init, { once: true });
} else {
    init();
}
