// Register User
document.getElementById('registerForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const userData = {
        username: document.getElementById('username').value.trim(),
        password: document.getElementById('password').value.trim(),
        company_name: document.getElementById('companyName').value.trim(),
        credit_info: document.getElementById('creditInfo').value.trim(),
    };

    try {
        const response = await fetch('/api/register', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(userData),
        });

        const data = await response.json();
        alert(data.message);

        if (data.success) {
            // Reload the page to show the user as logged in
            window.location.reload();
        }
    } catch (error) {
        console.error('Error registering user:', error);
        alert('An error occurred during registration.');
    }
});

// Login User
document.getElementById('loginForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const loginData = {
        username: document.getElementById('loginUsername').value.trim(),
        password: document.getElementById('loginPassword').value.trim(),
    };

    try {
        const response = await fetch('/api/login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(loginData),
        });

        const data = await response.json();

        if (data.success) {
            alert('Login successful!');
            sessionStorage.setItem('user', JSON.stringify(data.user));
            window.location.reload();
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('Error logging in:', error);
        alert('An error occurred during login.');
    }
});

// Tab Switching
function showTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(tab => tab.style.display = 'none');
    document.getElementById(tabId).style.display = 'block';
}

// Slider Interaction
function showAlternateLogo() {
    document.getElementById('overlay-logo').style.display = 'block';
}

function hideAlternateLogo() {
    document.getElementById('overlay-logo').style.display = 'none';
}

function navigateTo(page) {
    const baseUrl = window.location.origin;
    window.location.href = `${baseUrl}/${page}`;
}

// Logout Functionality
function logout() {
    sessionStorage.removeItem('user');
    window.location.reload();
}

// Populate Username
document.addEventListener('DOMContentLoaded', function () {
    const user = JSON.parse(sessionStorage.getItem('user'));
    if (user) {
        document.getElementById('username-display').textContent = `Hello, ${user.username}`;
    }
});
