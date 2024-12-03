document.getElementById('registerForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const userData = {
        username: document.getElementById('username').value.trim(),
        password: document.getElementById('password').value.trim(),
        company_name: document.getElementById('companyName').value.trim(),
        credit_info: document.getElementById('creditInfo').value.trim(),
    };

    console.log('Registering user:', userData); // Debugging

    try {
        const response = await fetch('http://localhost:8888/api/register', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(userData),
        });

        const data = await response.json();
        alert(data.message);
    } catch (error) {
        console.error('Error registering user:', error);
        alert('An error occurred during registration. Please try again.');
    }
});

document.getElementById('loginForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const loginData = {
        username: document.getElementById('loginUsername').value.trim(),
        password: document.getElementById('loginPassword').value.trim(),
    };

    console.log('Logging in user:', loginData); // Debugging

    try {
        const response = await fetch('http://localhost:8888/api/login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(loginData),
        });

        const data = await response.json();

        if (data.success) {
            alert('Login successful!');
            sessionStorage.setItem('user', JSON.stringify(data.user));
            window.location.href = '/lemons'; // Redirect
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('Error logging in:', error);
        alert('An error occurred during login. Please try again.');
    }
});
