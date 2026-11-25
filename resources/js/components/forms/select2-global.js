/**
 * Select2 com busca no servidor (mesmo padrão do SVO): <select data-svo-select2="1" data-select2-url="...">.
 * No SVO o select2 vem por CDN; aqui vem no pacote do Vite, porque a PoC não baixa nada de fora da rede.
 * O pacote do select2 se registra no jQuery que recebe: passamos o do AdminLTE (window.jQuery).
 */
import select2Factory from 'select2';
import 'select2/dist/css/select2.min.css';
import '@ttskch/select2-bootstrap4-theme/dist/select2-bootstrap4.min.css';
import '../../../css/components/select2.css';

const language = {
    errorLoading: () => 'Não foi possível carregar a lista.',
    inputTooShort: () => 'Digite para buscar',
    loadingMore: () => 'Carregando mais…',
    noResults: () => 'Nada encontrado',
    searching: () => 'Buscando…',
    removeAllItems: () => 'Limpar',
};

function bootstrapSelect2(root = document) {
    const $ = window.jQuery;
    if (!$) {
        return;
    }
    if (typeof $.fn.select2 !== 'function') {
        select2Factory(window, $);
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    $(root).find('select[data-svo-select2="1"]').each(function () {
        const $select = $(this);
        if ($select.data('svo-select2-initialized')) {
            return;
        }

        $select.select2({
            placeholder: $select.data('placeholder') || 'Selecione',
            allowClear: false, // o "x" do tema bootstrap4 fica fora do lugar no select2 4.1; para trocar, escolha outra opção
            theme: $select.data('theme') || 'bootstrap4',
            language,
            width: '100%',
            ajax: {
                url: $select.data('select2Url'),
                type: 'POST',
                dataType: 'json',
                delay: 250,
                headers: { 'X-CSRF-TOKEN': csrfToken },
                data: (params) => ({ search: params.term || '', page: params.page || 1 }),
                processResults: (data, params) => {
                    params.page = params.page || 1;
                    const items = Array.isArray(data?.data) ? data.data : [];

                    return {
                        results: items.map((item) => ({ id: item.id, text: item.name || item.text || String(item.id), data: item.data })),
                        pagination: { more: Number(data?.meta?.current_page ?? 1) < Number(data?.meta?.last_page ?? 1) },
                    };
                },
                cache: true,
            },
        });

        // o select2 dispara pelo jQuery: repassa como evento nativo para quem escuta "change" sem jQuery
        $select.on('select2:select select2:clear', () => this.dispatchEvent(new Event('change', { bubbles: true })));
        $select.data('svo-select2-initialized', true);
    });
}

// select2 4.x com jQuery 3.6+ não põe o cursor na busca ao abrir: sem isso, digitar logo depois de clicar não busca nada
document.addEventListener('DOMContentLoaded', () => {
    window.jQuery?.(document).on('select2:open', () => {
        window.setTimeout(() => document.querySelector('.select2-container--open .select2-search__field')?.focus(), 0);
    });
});

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => bootstrapSelect2());
} else {
    bootstrapSelect2();
}
