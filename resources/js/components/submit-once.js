/**
 * Um envio por vez: ao enviar um formulário, o botão mostra "carregando" e novos cliques
 * são ignorados até a página trocar. Evita regra/usuário duplicado por clique duplo.
 *
 * - Vale para todos os formulários; para desligar em um: data-submit-once="false".
 * - Respeita confirmações (alerts.js): se o envio foi cancelado antes (defaultPrevented),
 *   nada é travado. Por isso escuta no window: o evento chega nele DEPOIS dos ouvintes do
 *   document, qualquer que seja a ordem em que o Vite carregar os módulos (este arquivo vai
 *   num pedaço compartilhado com o login e roda antes do alerts.js).
 * - Ao voltar pela história do navegador (bfcache), os botões são liberados.
 */
const SPINNER = '<span class="btn-spinner" aria-hidden="true"></span>';

const lock = (form, button) => {
    form.dataset.submitting = '1';

    if (!button) {
        return;
    }

    button.classList.add('is-loading');
    button.setAttribute('aria-busy', 'true');

    if (!button.querySelector('.btn-spinner')) {
        button.insertAdjacentHTML('afterbegin', SPINNER);
    }
};

const unlockAll = () => {
    document.querySelectorAll('form[data-submitting]').forEach((form) => delete form.dataset.submitting);
    document.querySelectorAll('.is-loading').forEach((button) => {
        button.classList.remove('is-loading');
        button.removeAttribute('aria-busy');
    });
};

window.addEventListener('submit', (event) => {
    const form = event.target;

    if (!(form instanceof HTMLFormElement) || event.defaultPrevented || form.dataset.submitOnce === 'false') {
        return;
    }

    if (form.dataset.submitting === '1') {
        event.preventDefault();

        return;
    }

    lock(form, event.submitter ?? form.querySelector('[type="submit"]'));
});

window.addEventListener('pageshow', (event) => {
    if (event.persisted) {
        unlockAll();
    }
});
