const content = document.querySelector('#main');
const load = document.querySelector('.spinner');
const topic = document.querySelector('.topic');

console.log('confirm email');

const url = 'api/mksec/confirm_email.php?code=' + getQueryVariable('code');

fetch(url, {
    method: 'GET',
    mode: 'cors',
    headers: {
        'Content-Type': 'application/json'
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
            content.insertBefore(msg, topic.nextSibling);

            setTimeout(() => {
                content.removeChild(msg);
            }, 10000);
        } else {
            const msg = document.createElement('DIV');
            msg.classList.add('card');
            msg.classList.add('success');
            msg.innerHTML = response.message;
            content.insertBefore(msg, topic.nextSibling);

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
        content.insertBefore(msg, topic.nextSibling);

        setTimeout(() => {
            content.removeChild(msg);
        }, 7000);
        content.removeChild(load);
    });

function getQueryVariable(variable) {
    var query = window.location.search.substring(1);
    var vars = query.split('&');
    for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split('=');
        if (pair[0] == variable) {
            return pair[1];
        }
    }
    return false;
}
