// ============================================
// Faraj Fruit Supplier and Vendor
// Form Validation – validation.js
// Week 3: JavaScript Basics
// ============================================

// ── Helper: show error under a field ──
function showError(fieldId, message) {
    const field = document.getElementById(fieldId);
    let err = document.getElementById(fieldId + '_error');
    if (!err) {
        err = document.createElement('span');
        err.id = fieldId + '_error';
        err.style.cssText = 'color:#cc0000;font-size:12px;display:block;margin-top:4px;';
        field.parentNode.appendChild(err);
    }
    err.textContent = message;
    field.style.borderColor = '#cc0000';
}

// ── Helper: clear error ──
function clearError(fieldId) {
    const field = document.getElementById(fieldId);
    const err = document.getElementById(fieldId + '_error');
    if (err) err.textContent = '';
    if (field) field.style.borderColor = '#e0e0e0';
}

// ── Validate Login Form ──
function validateLogin(e) {
    e.preventDefault();
    let valid = true;

    const email    = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();

    clearError('email'); clearError('password');

    if (!email) {
        showError('email', 'Email is required.'); valid = false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showError('email', 'Enter a valid email address.'); valid = false;
    }

    if (!password) {
        showError('password', 'Password is required.'); valid = false;
    } else if (password.length < 8) {
        showError('password', 'Password must be at least 8 characters.'); valid = false;
    }

    if (valid) e.target.submit();
}

// ── Validate Register Form ──
function validateRegister(e) {
    e.preventDefault();
    let valid = true;

    const name     = document.getElementById('name').value.trim();
    const email    = document.getElementById('email').value.trim();
    const phone    = document.getElementById('phone').value.trim();
    const password = document.getElementById('password').value.trim();
    const confirm  = document.getElementById('confirm_password').value.trim();

    clearError('name'); clearError('email');
    clearError('phone'); clearError('password'); clearError('confirm_password');

    if (!name || name.length < 3) {
        showError('name', 'Full name must be at least 3 characters.'); valid = false;
    }
    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showError('email', 'Enter a valid email address.'); valid = false;
    }
    if (phone && !/^(\+254|0)[7][0-9]{8}$/.test(phone)) {
        showError('phone', 'Enter a valid Kenyan phone number e.g. 0712345678.'); valid = false;
    }
    if (!password || password.length < 8) {
        showError('password', 'Password must be at least 8 characters.'); valid = false;
    }
    if (password !== confirm) {
        showError('confirm_password', 'Passwords do not match.'); valid = false;
    }

    if (valid) e.target.submit();
}

// ── Validate Checkout Form ──
function validateCheckout(e) {
    e.preventDefault();
    let valid = true;

    const fields = ['recipient_name', 'phone', 'address', 'city'];
    fields.forEach(f => clearError(f));

    const recipient = document.getElementById('recipient_name').value.trim();
    const phone     = document.getElementById('phone').value.trim();
    const address   = document.getElementById('address').value.trim();
    const city      = document.getElementById('city').value.trim();

    if (!recipient || recipient.length < 3) {
        showError('recipient_name', 'Recipient name is required.'); valid = false;
    }
    if (!phone || !/^(\+254|0)[7][0-9]{8}$/.test(phone)) {
        showError('phone', 'Enter a valid Kenyan phone number.'); valid = false;
    }
    if (!address) {
        showError('address', 'Delivery address is required.'); valid = false;
    }
    if (!city) {
        showError('city', 'City is required.'); valid = false;
    }

    if (valid) e.target.submit();
}
