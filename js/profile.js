const spinner = document.querySelector('.fullspinner');
const load = document.createElement('DIV');
load.classList.add('card');
load.classList.add('spinner');
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

// --------- Passwort ändern ---------

const changePassword = document.createElement('DIV');
changePassword.classList.add('card');
changePassword.innerHTML =
    '<br><h4>Passwort ändern? Dann klick <a href="forgotpassword.html" style="color: blue; text-decoration: underline;">hier</a></h4><br>';
content.insertBefore(changePassword, spinner);

// --------- Schüler Daten laden ---------

if (jwtdata.type == 'STNT') {
    let studentDataUrl = 'api/get_student_data.php';
    let createStudenUrl = 'api/create_student.php';
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

            const studentdata = document.createElement('DIV');
            studentdata.classList.add('card');

            if (!response.error) {
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
                    '<br>';
            } else if (response.error && response.error_code == 1) {
                studentdata.innerHTML =
                    '<br/>' +
                    '<h3>Neu hier?:</h3>' +
                    '<br/>' +
                    '<form id="create_student">' +
                    '    <label for="year">Dann gib dein Jahrgang hier an:</label>' +
                    '    <select name="year" id="year">' +
                    '        <option value="7">7</option>' +
                    '        <option value="8">8</option>' +
                    '        <option value="9">9</option>' +
                    '        <option value="10">10</option>' +
                    '        <option value="11">11</option>' +
                    '        <option value="12">12</option>' +
                    '    </select>' +
                    '    <br/>' +
                    '    <input type="submit" value="Speichern" />' +
                    '</form>';
                const createstudentform = document.querySelector(
                    '#create_student'
                );
                createstudentform.addEventListener('submit', onCreateStudent);
            } else {
                studentdata.classList.add('error');
                studentdata.innerHTML = response.message;
            }
            content.insertBefore(studentdata, spinner);
        })
        .catch(error => console.error('Error:', error));
}

//content.removeChild(spinner);

function onCreateStudent(e) {
    e.preventDefault();
    console.log('submitted');

    content.insertBefore(load, studentdata.nextSibling);

    if (document.querySelector('#year').value == '') {
        console.log('field is missing');
        const msg = document.createElement('DIV');
        msg.classList.add('card');
        msg.classList.add('warning');
        msg.innerHTML = 'Bitte alle benötigten Felder ausfüllen!';
        content.insertBefore(msg, studentdata.nextSibling);

        setTimeout(() => {
            content.removeChild(msg);
        }, 3000);
        content.removeChild(load);
        return;
    } else {
        const data = {
            year: document.querySelector('#year').value
        };

        fetch(createStudenUrl, {
            method: 'POST',
            mode: 'cors',
            body: JSON.stringify(data), // data can be `string` or {object}!
            headers: {
                'Content-Type': 'application/json',
                Authorization: 'Bearer ' + jwt
            }
        })
            .then(res => res.json())
            .then(response => {
                console.log('Success:', JSON.stringify(response));

                if (response.error) {
                    const msg = document.createElement('DIV');
                    msg.classList.add('card');
                    msg.classList.add('error');
                    msg.innerHTML = response.message;
                    content.insertBefore(msg, studentdata.nextSibling);

                    setTimeout(() => {
                        content.removeChild(msg);
                    }, 5000);
                } else {
                    location.reload();
                }
                content.removeChild(load);
            })
            .catch(error => {
                console.error('Error:', error);
                const msg = document.createElement('DIV');
                msg.classList.add('card');
                msg.classList.add('error');
                msg.innerHTML = 'Fehler';
                content.insertBefore(msg, studentdata.nextSibling);

                setTimeout(() => {
                    content.removeChild(msg);
                }, 5000);
                content.removeChild(load);
            });
    }
}

function decodeToken(token) {
    var payload = JSON.parse(atob(token.split('.')[1]));
    return payload;
}
