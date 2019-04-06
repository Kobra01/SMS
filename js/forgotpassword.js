const content = document.querySelector('#main');
const form = document.querySelector('#form');
const email = document.querySelector('#email');
const urlLogin = 'api/mksec/forgot_password.php';
const load = document.createElement('DIV');

form.addEventListener('submit', onSubmit);
load.classList.add('card');
load.classList.add('spinner');

function onSubmit(e) {
    e.preventDefault();
    console.log('submitted');

    content.insertBefore(load, form.parentElement.nextSibling);

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
        email: email.value
    };
        
    fetch(urlLogin, {
        method: 'POST',
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
            const msg = document.createElement('DIV');
            msg.classList.add('card');
            msg.classList.add('success');
            msg.innerHTML = response.message;
            content.insertBefore(msg, form.parentElement.nextSibling);

            setTimeout(() => {
                content.removeChild(msg);
            }, 10000);
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

    email.value = '';
}