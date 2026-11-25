/**
 * Máscara de CNPJ enquanto digita: <input data-mask="cnpj">. O servidor aceita com ou sem máscara.
 */
const format = (value) => {
    const d = value.replace(/\D/g, '').slice(0, 14);
    return d
        .replace(/^(\d{2})(\d)/, '$1.$2')
        .replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3')
        .replace(/\.(\d{3})(\d)/, '.$1/$2')
        .replace(/(\d{4})(\d)/, '$1-$2');
};

document.addEventListener('input', (event) => {
    const input = event.target.closest?.('input[data-mask="cnpj"]');
    if (input) {
        input.value = format(input.value);
    }
});
