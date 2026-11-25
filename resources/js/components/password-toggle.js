/**
 * Mostrar/ocultar senha: <button data-password-toggle="#id-do-campo">.
 */
document.addEventListener('click', (event) => {
    const button = event.target.closest('[data-password-toggle]');

    if (!button) {
        return;
    }

    const input = document.querySelector(button.dataset.passwordToggle);

    if (!input) {
        return;
    }

    event.preventDefault();

    const show = input.type === 'password';
    input.type = show ? 'text' : 'password';

    const icon = button.querySelector('i');
    icon?.classList.toggle('fa-eye', !show);
    icon?.classList.toggle('fa-eye-slash', show);

    const label = show ? 'Ocultar senha' : 'Mostrar senha';
    button.setAttribute('aria-label', label);
    button.setAttribute('title', label);
    button.setAttribute('aria-pressed', String(show));
    input.focus();
});
