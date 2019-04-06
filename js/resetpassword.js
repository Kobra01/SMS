const url = 'api/mksec/reset_password.php';

const content = document.querySelector('#main');
const form = document.querySelector('#form');
const password = document.querySelector('#password');
const repeatpassword = document.querySelector('#repeatpassword');
const load = document.createElement('DIV');

form.addEventListener('submit', onSubmit);
load.classList.add('card');
load.classList.add('spinner');


function onSubmit(e) {
    e.preventDefault();
    console.log('submitted');

    content.insertBefore(load, form.parentElement.nextSibling);

    if (password.value === '' || repeatpassword.value === '') {
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
        code: getQueryVariable('code'),
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

    password.value = '';
    repeatpassword.value = '';
}

function getQueryVariable(variable)
{
       var query = window.location.search.substring(1);
       var vars = query.split("&");
       for (var i=0;i<vars.length;i++) {
               var pair = vars[i].split("=");
               if(pair[0] == variable){return pair[1];}
       }
       return(false);
}