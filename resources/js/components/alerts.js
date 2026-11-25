/**
 * Avisos e confirmações com SweetAlert2 (no lugar do alert/confirm do navegador).
 *
 * Mensagens do servidor (flash):
 *   <script type="application/json" id="flash-messages">{"success": "...", "error": "..."}</script>
 *   O layout monta esse bloco a partir de session('status'|'success'|'error'|'warning'|'info')
 *   e dos erros de validação. Cada mensagem vira um aviso no canto da tela.
 *
 * Confirmação antes de enviar um formulário:
 *   <form data-confirm="Texto da pergunta"
 *         data-confirm-title="Título"            (opcional)
 *         data-confirm-button="Sim, revogar"     (opcional)
 *         data-confirm-variant="danger">         (opcional: danger | primary)
 *
 * Uso direto em outros scripts: window.Toast.fire({...}), window.confirmAction({...}).
 */
import Swal from 'sweetalert2';

window.Swal = Swal;

export const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 4500,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    },
});

window.Toast = Toast;

export const confirmAction = ({ title, text, button, variant = 'primary', icon = 'warning' }) =>
    Swal.fire({
        icon,
        title: title || 'Confirmar',
        text,
        showCancelButton: true,
        focusCancel: variant === 'danger',
        reverseButtons: true,
        confirmButtonText: button || 'Sim, continuar',
        cancelButtonText: 'Cancelar',
        customClass: {
            confirmButton: variant === 'danger' ? 'swal2-confirm--danger' : '',
        },
    }).then((result) => result.isConfirmed === true);

window.confirmAction = confirmAction;

const showFlashMessages = () => {
    const holder = document.getElementById('flash-messages');

    if (!holder) {
        return;
    }

    let messages;

    try {
        messages = JSON.parse(holder.textContent || '{}');
    } catch {
        return;
    }

    // um aviso por vez, na ordem: erro primeiro
    const order = ['error', 'warning', 'success', 'info'];
    const queue = order.filter((type) => messages[type]).map((type) => ({ icon: type, title: messages[type] }));

    queue.reduce((previous, toast) => previous.then(() => Toast.fire(toast)), Promise.resolve());
};

document.addEventListener('submit', (event) => {
    const form = event.target;

    if (!(form instanceof HTMLFormElement) || !form.dataset.confirm || form.dataset.confirmed === '1') {
        return;
    }

    event.preventDefault();

    const submitter = event.submitter;

    confirmAction({
        title: form.dataset.confirmTitle,
        text: form.dataset.confirm,
        button: form.dataset.confirmButton,
        variant: form.dataset.confirmVariant,
    }).then((confirmed) => {
        if (!confirmed) {
            return;
        }

        // envia de novo, já confirmado: o submit-once trava o botão neste segundo envio
        form.dataset.confirmed = '1';

        if (typeof form.requestSubmit === 'function') {
            form.requestSubmit(submitter && submitter.form === form ? submitter : undefined);
        } else {
            form.submit();
        }

        delete form.dataset.confirmed;
    });
});

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', showFlashMessages, { once: true });
} else {
    showFlashMessages();
}
