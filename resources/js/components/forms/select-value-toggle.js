/**
 * Mostra ou esconde partes do formulário conforme o valor de um campo (select ou rádio).
 *
 *   <div data-show-when="subject_type=ip|cidr"> ... </div>
 *
 * - O campo é procurado pelo name, dentro do mesmo <form>.
 * - Enquanto escondido, os campos de dentro ficam desabilitados e não vão no envio
 *   (assim a validação do servidor não recebe valor de uma opção que não foi escolhida).
 */
const parseRule = (element) => {
    const [name, values = ''] = (element.dataset.showWhen || '').split('=');

    return { name: name.trim(), values: values.split('|').map((value) => value.trim()) };
};

const fieldValue = (form, name) => {
    const fields = form.querySelectorAll(`[name="${CSS.escape(name)}"]`);

    for (const field of fields) {
        if (field.type === 'radio' || field.type === 'checkbox') {
            if (field.checked) {
                return field.value;
            }
        } else {
            return field.value;
        }
    }

    return '';
};

const refresh = (form) => {
    form.querySelectorAll('[data-show-when]').forEach((element) => {
        const { name, values } = parseRule(element);
        const visible = values.includes(fieldValue(form, name));

        element.hidden = !visible;
        element.querySelectorAll('input, select, textarea').forEach((input) => {
            input.disabled = !visible;
        });
    });
};

const init = () => {
    const forms = new Set();
    document.querySelectorAll('[data-show-when]').forEach((element) => {
        const form = element.closest('form');

        if (form) {
            forms.add(form);
        }
    });

    forms.forEach((form) => {
        const watched = new Set([...form.querySelectorAll('[data-show-when]')].map((element) => parseRule(element).name));

        form.addEventListener('change', (event) => {
            if (watched.has(event.target.name)) {
                refresh(form);
            }
        });

        refresh(form);
    });
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init, { once: true });
} else {
    init();
}
