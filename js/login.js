let jwt;
const urlCheck = 'api/mksec/check.php';

if (sessionStorage.getItem('jwt')) {
    jwt = sessionStorage.getItem('jwt');
}
if (localStorage.getItem('jwt')) {
    jwt = localStorage.getItem('jwt');
}

if (jwt) {
    fetch(urlCheck, {
        method: 'GET',
        mode: "cors",
        headers:{
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + jwt
        }
    }).then(res => res.json())
    .then(response =>  {
        console.log('Success:', JSON.stringify(response));

        if (response.error) {
            sessionStorage.clear();
            localStorage.clear();
        } else {
            window.location.href = 'feed.html';
        }
    })
    .catch(error => console.error('Error:', error));
}

const content = document.querySelector('#main');
const form = document.querySelector('#form');
const username = document.querySelector('#username');
const school = document.querySelector('#school');
const email = document.querySelector('#email');
const password = document.querySelector('#password');
const stayloggedin = document.querySelector('#stay');
const urlLogin = 'api/mksec/login.php';
const load = document.createElement('DIV');

form.addEventListener('submit', onSubmit);
load.classList.add('card');
load.classList.add('spinner');

function onSubmit(e) {
    e.preventDefault();
    console.log('submitted');

    content.insertBefore(load, form.parentElement.nextSibling);

    if (email.value === '') {

        if (username.value === '' || school.value === '' || password.value === '') {
            console.log('fields are missing');
            const msg = document.createElement('DIV');
            msg.classList.add('card');
            msg.classList.add('warning');
            msg.innerHTML = 'Bitte alle benötigten Felder ausfüllen!';
            content.insertBefore(msg, form.parentElement.nextSibling);

            setTimeout(() => {
                content.removeChild(msg);
            }, 3000);
            content.removeChild(load);
            return;
        }

        const data = {
            username: username.value,
            school: school.value,
            password: password.value
        };
          
        fetch(urlLogin, {
            method: 'POST', // or 'PUT'
            mode: "cors",
            body: JSON.stringify(data), // data can be `string` or {object}!
            headers:{
                'Content-Type': 'application/json'
            }
        }).then(res => res.json())
        .then(response =>  {
            console.log('Success:', JSON.stringify(response));

            if (response.error) {
                const msg = document.createElement('DIV');
                msg.classList.add('card');
                msg.classList.add('error');
                msg.innerHTML = response.message;
                content.insertBefore(msg, form.parentElement.nextSibling);

                sessionStorage.clear();
                localStorage.clear();                

                setTimeout(() => {
                    content.removeChild(msg);
                }, 10000);
            } else {
                if (stayloggedin.checked === true) {
                    localStorage.setItem('jwt', response.jwt);
                } else {
                    sessionStorage.setItem('jwt', response.jwt);
                }
                window.location.href = 'feed.html';
            }
            content.removeChild(load);
        })
        .catch(error => {
            console.error('Error:', error);
            const msg = document.createElement('DIV');
            msg.classList.add('card');
            msg.classList.add('error');
            msg.innerHTML = 'Fehler';
            content.insertBefore(msg, form.parentElement.nextSibling);

            setTimeout(() => {
                content.removeChild(msg);
            }, 7000);
            content.removeChild(load);
        });
        
    } else {

        if (email.value === '' || password.value === '') {
            console.log('fields are missing');
            const msg = document.createElement('DIV');
            msg.classList.add('card');
            msg.classList.add('warning');
            msg.innerHTML = 'Bitte alle benötigten Felder ausfüllen!';
            content.insertBefore(msg, form.parentElement.nextSibling);

            setTimeout(() => {
                content.removeChild(msg);
            }, 3000);
            content.removeChild(load);
            return;
        }
    
        //Validate Email
        if (email.value.indexOf('@') == -1) {
            console.log('email not correct');
            const msg = document.createElement('DIV');
            msg.classList.add('card');
            msg.classList.add('warning');
            msg.innerHTML = 'Bitte gültige Email eingeben!';
            content.insertBefore(msg, form.parentElement.nextSibling);

            setTimeout(() => {
                content.removeChild(msg);
            }, 3000);
            content.removeChild(load);
            return;
        }

        const data = {
            email: email.value,
            password: password.value
        };
          
        fetch(urlLogin, {
            method: 'POST', // or 'PUT'
            mode: "cors",
            body: JSON.stringify(data), // data can be `string` or {object}!
            headers:{
                'Content-Type': 'application/json'
            }
        }).then(res => res.json())
        .then(response =>  {
            console.log('Success:', JSON.stringify(response));

            if (response.error) {
                const msg = document.createElement('DIV');
                msg.classList.add('card');
                msg.classList.add('error');
                msg.innerHTML = response.message;
                content.insertBefore(msg, form.parentElement.nextSibling);

                sessionStorage.clear();
                localStorage.clear(); 

                setTimeout(() => {
                    content.removeChild(msg);
                }, 10000);
            } else {
                if (stayloggedin.checked === true) {
                    localStorage.setItem('jwt', response.jwt);
                } else {
                    sessionStorage.setItem('jwt', response.jwt);
                }
                window.location.href = 'feed.html';
            }
            content.removeChild(load);
        })
        .catch(error => {
            console.error('Error:', error);
            const msg = document.createElement('DIV');
            msg.classList.add('card');
            msg.classList.add('error');
            msg.innerHTML = 'Fehler';
            content.insertBefore(msg, form.parentElement.nextSibling);

            setTimeout(() => {
                content.removeChild(msg);
            }, 7000);
            content.removeChild(load);
        });

    }

    username.value = '';
    email.value = '';
    password.value = '';

}