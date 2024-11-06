document.addEventListener('DOMContentLoaded', () => {
    const loginBtn = document.getElementById('loginBtn');
    const registerBtn = document.getElementById('registerBtn');
    const loginModal = document.getElementById('loginModal');
    const registerModal = document.getElementById('registerModal');
    const closeBtns = document.getElementsByClassName('close');
    const navbarToggle = document.getElementById('navbarToggle');
    const navbarLinks = document.querySelector('.navbar-links');
    const authButtons = document.querySelector('.auth-buttons');

    // Toggle mobile menu
    navbarToggle.addEventListener('click', () => {
        navbarLinks.classList.toggle('active');
        authButtons.classList.toggle('active');
    });

    // Open modals
    loginBtn.addEventListener('click', () => loginModal.style.display = 'block');
    registerBtn.addEventListener('click', () => registerModal.style.display = 'block');

    // Close modals
    Array.from(closeBtns).forEach(btn => {
        btn.addEventListener('click', () => {
            loginModal.style.display = 'none';
            registerModal.style.display = 'none';
        });
    });

    // Close modals when clicking outside
    window.addEventListener('click', (event) => {
        if (event.target == loginModal) loginModal.style.display = 'none';
        if (event.target == registerModal) registerModal.style.display = 'none';
    });

    // Handle form submissions
    document.querySelector('#loginModal form').addEventListener('submit', handleLogin);
    document.querySelector('#registerModal form').addEventListener('submit', handleRegister);
});

function handleLogin(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);

    fetch('login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            document.getElementById('loginModal').style.display = 'none';
            // Redirect or update UI as needed
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

function handleRegister(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);

    fetch('register.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            document.getElementById('registerModal').style.display = 'none';
            // Redirect or update UI as needed
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}
