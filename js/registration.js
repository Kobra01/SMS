const url = 'api/mksec/create_user.php';

const content = document.querySelector('#main');
const form = document.querySelector('#form');
const type = document.querySelector('#type');
const username = document.querySelector('#username');
const school = document.querySelector('#school');
const firstname = document.querySelector('#firstname');
const lastname = document.querySelector('#lastname');
const email = document.querySelector('#email');
const password = document.querySelector('#password');
const repeatpassword = document.querySelector('#repeatpassword');
const agbs = document.querySelector('#agbs');
const pp = document.querySelector('#pp');
const load = document.createElement('DIV');

form.addEventListener('submit', onSubmit);
load.classList.add('card');
load.classList.add('spinner');


function onSubmit(e) {
    e.preventDefault();
    console.log('submitted');

    content.insertBefore(load, form.parentElement.nextSibling);

    if (type.value === '' || username.value === '' || school.value === '' || firstname.value === '' || lastname.value === '' || email.value === '' || password.value === '' || repeatpassword.value === '' || agbs.checked === false || pp.checked === false) {
        console.log('fields are missing');

        const msg = document.createElement('DIV');
        msg.classList.add('card');
        msg.classList.add('warning');
        msg.innerHTML = 'Bitte alle Felder ausfüllen!';
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

    //Check password
    if (password.value != repeatpassword.value) {
        console.log('passwords are different');

        const msg = document.createElement('DIV');
        msg.classList.add('card');
        msg.classList.add('warning');
        msg.innerHTML = 'Passwörter stimmen nicht überein!';
        content.insertBefore(msg, form.parentElement.nextSibling);

        setTimeout(() => {
            content.removeChild(msg);
        }, 3000);
        content.removeChild(load);
        return;
    }

    const data = {
        type: type.value,
        username: username.value,
        school: school.value,
        firstname: firstname.value,
        lastname: lastname.value,
        email: email.value,
        password: password.value
    };
      
    fetch(url, {
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

            setTimeout(() => {
                content.removeChild(msg);
            }, 10000);
        } else {
            const msg = document.createElement('DIV');
            msg.classList.add('card');
            msg.classList.add('success');
            msg.innerHTML = response.message;
            content.insertBefore(msg, form.parentElement.nextSibling);

            setTimeout(() => {
                content.removeChild(msg);
            }, 15000);
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

    username.value = '';
    firstname.value = '';
    lastname.value = '';
    email.value = '';
    password.value = '';

}

