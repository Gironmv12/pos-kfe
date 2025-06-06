import { login } from "../api/auth";

document.getElementById('login-form').addEventListener('submit', function (e) {
    e.preventDefault();

    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    login(email, password).then(response => {
    localStorage.setItem('token', response.data.token);
    localStorage.setItem('rol', response.data.rol);

    if (response.data.rol === 'empleado' || response.data.rol === 'administrador') {
        window.location.href = '/pos';
    } else {
        alert('Rol no reconocido');
    }
}).catch(error => {
    alert('Error de login');
    console.error(error);
});
});