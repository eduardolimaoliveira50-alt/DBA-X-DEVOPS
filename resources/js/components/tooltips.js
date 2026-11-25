/**
 * Dicas (tooltip) do Bootstrap 4 em [data-toggle="tooltip"]. O jQuery e o Bootstrap vêm do
 * AdminLTE (scripts comuns no fim do <body>), que rodam antes deste módulo.
 */
const init = () => {
    const $ = window.jQuery;

    if (!$ || typeof $.fn?.tooltip !== 'function') {
        return;
    }

    $('[data-toggle="tooltip"]').tooltip({ container: 'body', boundary: 'window' });

    // a dica não fica presa na tela depois do clique
    $(document).on('click', '[data-toggle="tooltip"]', function () {
        $(this).tooltip('hide');
    });
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init, { once: true });
} else {
    init();
}
