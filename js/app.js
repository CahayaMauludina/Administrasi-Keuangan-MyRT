const username = localStorage.getItem('username');
const role = localStorage.getItem('role');
const login = localStorage.getItem('login');

if (!login) {
	window.location.href = 'login.php';
}

document.getElementById('username').innerHTML = username;

document.querySelector('#logout').addEventListener('click', () => {
	localStorage.clear();
	window.location.href = 'login';
})