document.addEventListener('DOMContentLoaded', function initPasswordCheck() {

    // ── Bouton œil : toggle visibilité + changement d'icône ──
    function makeEyeToggle(inputEl, btnId) {
        const btn = document.getElementById(btnId);
        if (!btn || !inputEl) return;

        btn.addEventListener('click', function () {
            const isVisible = inputEl.type === 'text';
            inputEl.type = isVisible ? 'password' : 'text';
            btn.classList.toggle('active', !isVisible);

            // Icône œil ouvert (password masqué) vs œil barré (password visible)
            btn.querySelector('svg').innerHTML = isVisible
                ? `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                   <circle cx="12" cy="12" r="3"/>`
                : `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8
                   a18.45 18.45 0 0 1 5.06-5.94"/>
                   <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8
                   a18.5 18.5 0 0 1-2.16 3.19"/>
                   <line x1="1" y1="1" x2="23" y2="23"/>`;
        });
    }

    const password        = document.getElementById('password');
    const passwordConfirm = document.getElementById('password-confirm');

    // Active les boutons œil (fonctionne sur connexion.php ET inscription.php)
    makeEyeToggle(password,        'toggle-password');
    makeEyeToggle(passwordConfirm, 'toggle-password-confirm');

    // ── Vérification live (inscription uniquement — nécessite #password-check) ──
    const passwordCheck = document.getElementById('password-check');
    if (!password || !passwordConfirm || !passwordCheck) return;

    function checkPassword() {
        const pw  = password.value;
        const pwc = passwordConfirm.value;

        if (pw.length < 8) {
            passwordCheck.textContent = '8 caractères minimum';
            passwordCheck.style.color = '#e53e3e';
            return;
        }

        if (pw !== pwc) {
            passwordCheck.textContent = 'Les mots de passe ne correspondent pas !';
            passwordCheck.style.color = '#e53e3e';
        } else {
            passwordCheck.textContent = 'Les mots de passe correspondent ✓';
            passwordCheck.style.color = '#38a169';
        }
    }

    password.addEventListener('input', checkPassword);
    passwordConfirm.addEventListener('input', checkPassword);
});
