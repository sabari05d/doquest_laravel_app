// Password Show/Hide
document.addEventListener('DOMContentLoaded', () => {
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');

    if (togglePassword && password && toggleIcon) {
        togglePassword.addEventListener('click', () => {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            toggleIcon.classList.toggle('bi-eye');
            toggleIcon.classList.toggle('bi-eye-slash');
        });
    }
});

// Password Show/Hide