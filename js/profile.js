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

if (jwtdata.type == 'STNT') {
    let studentDataUrl = 'api/get_student_data.php';
    fetch(studentDataUrl, {
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
            if (!response.error) {
                const studentdata = document.createElement('DIV');
                studentdata.classList.add('card');

                studentdata.innerHTML =
                    '<br>' +
                    '<h2>Schüler Daten:</h2>' +
                    '<br>' +
                    '<b>Name:</b> ' +
                    response.pub_name +
                    '<br>' +
                    '<br>' +
                    '<b>Jahrgang:</b> ' +
                    response.year +
                    '<br>' +
                    '<br>' +
                    '<b>Klasse:</b> ' +
                    response.class +
                    '<br>' +
                    '<br>';
                content.insertBefore(studentdata, spinner);
            }
        })
        .catch(error => console.error('Error:', error));
}

content.removeChild(spinner);

function decodeToken(token) {
    var payload = JSON.parse(atob(token.split('.')[1]));
    return payload;
}
