(function () {
    'use strict';

    // Password Toggle Logic
    document.querySelectorAll('.password-toggle').forEach(toggle => {
        toggle.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    });

    // Password Match Validation for profileForm
    const profileForm = document.getElementById('profileForm');
    if (profileForm) {
        const newPass = document.getElementById('newPassword');
        const confirmPass = document.getElementById('confirmPassword');
        const passError = document.getElementById('passwordError');

        profileForm.addEventListener('submit', function(e) {
            // Only validate if user entered a new password
            if (newPass.value || confirmPass.value) {
                if (newPass.value !== confirmPass.value) {
                    e.preventDefault();
                    passError.style.display = 'block';
                    confirmPass.style.borderColor = '#EF4444';
                    return;
                }
            }
            passError.style.display = 'none';
            confirmPass.style.borderColor = '';
        });
    }

    // Add User Form Simulation
    const addUserForm = document.getElementById('addUserForm');
    if (addUserForm) {
        addUserForm.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('New administrator invitation sent successfully!');
            this.reset();
        });
    }

    // Revoke Access Simulation
    document.querySelectorAll('.btn-revoke').forEach(btn => {
        btn.addEventListener('click', function() {
            if (confirm('Are you sure you want to revoke access for this administrator?')) {
                this.closest('tr').style.opacity = '0.5';
                this.innerHTML = 'Revoked';
                this.disabled = true;
            }
        });
    });
})();
