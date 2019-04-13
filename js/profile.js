const spinner = document.querySelector('.fullspinner');
const content = document.querySelector('#main');
let jwt;

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

const personaldata = document.createElement('DIV');
personaldata.classList.add('card');

let jwtdata = decodeToken(jwt).data;
const userdata =
    '<br>' +
    '<h2>Persönliche Daten:</h2>' +
    '<br>' +
    '<b>Schule:</b> ' +
    jwtdata.school +
    ' (' +
    jwtdata.type +
    ')<br>' +
    '<br>' +
    '<b>Name:</b> ' +
    jwtdata.lastname +
    ', ' +
    jwtdata.firstname +
    '<br>' +
    '<br>' +
    '<b>Email:</b> ' +
    jwtdata.email +
    '<br>' +
    '<br>' +
    '<b>Benutzername:</b> ' +
    jwtdata.username +
    '<br>' +
    '<br>';

personaldata.innerHTML = userdata;
content.insertBefore(personaldata, spinner);

const changePassword = document.createElement('DIV');
changePassword.classList.add('card');
changePassword.innerHTML =
    '<br><h4>Passwort ändern? Dann klick <a href="forgotpassword.html" style="color: blue; text-decoration: underline;">hier</a></h4><br>';
content.insertBefore(changePassword, spinner);

function decodeToken(token) {
    var payload = JSON.parse(atob(token.split('.')[1]));
    return payload;
}
