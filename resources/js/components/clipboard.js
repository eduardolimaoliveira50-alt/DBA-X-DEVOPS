/**
 * Copiar para a área de transferência: <button data-copy="texto">.
 * A PoC roda também em HTTP, onde navigator.clipboard não existe: cai no método antigo.
 */
const fallbackCopy = (text) => {
    const area = document.createElement('textarea');
    area.value = text;
    area.setAttribute('readonly', '');
    area.style.position = 'fixed';
    area.style.opacity = '0';
    document.body.appendChild(area);
    area.select();

    let copied = false;

    try {
        copied = document.execCommand('copy');
    } catch {
        copied = false;
    }

    area.remove();

    return copied;
};

const copy = async (text) => {
    if (navigator.clipboard && window.isSecureContext) {
        try {
            await navigator.clipboard.writeText(text);

            return true;
        } catch {
            // segue para o método antigo
        }
    }

    return fallbackCopy(text);
};

document.addEventListener('click', async (event) => {
    const button = event.target.closest('[data-copy]');

    if (!button) {
        return;
    }

    event.preventDefault();

    const copied = await copy(button.dataset.copy);

    window.Toast?.fire({
        icon: copied ? 'success' : 'error',
        title: copied ? 'Copiado' : 'Não foi possível copiar',
        timer: 1600,
    });
});
