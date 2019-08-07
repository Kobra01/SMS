const spinner = document.querySelector('.fullspinner');
const content = document.querySelector('#main');
let jwt;
const urlGetLessons = 'api/get_lessons.php';

// check if jwt exist
if (sessionStorage.getItem('jwt')) {
    jwt = sessionStorage.getItem('jwt');
}
if (localStorage.getItem('jwt')) {
    jwt = localStorage.getItem('jwt');
}
if (jwt == null) {
    window.location.href = 'login.html';
}

fetch(urlGetLessons, {
    method: 'GET',
    mode: 'cors',
    headers: {
        'Content-Type': 'application/json',
        Authorization: 'Bearer ' + jwt
    }
})
    .then(res => res.json())
    .then(response => {
        console.log('Success:', JSON.stringify(response));
    })
    .catch(error => console.error('Error:', error));
