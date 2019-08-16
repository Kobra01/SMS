const spinner = document.querySelector('.fullspinner');
const content = document.querySelector('#main');
var jwt;
const urlGetLessons = 'api/get_lessons.php';
let sub_settings = JSON.parse('{}');

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
        if (!response.error) {
            var temp_day = '0';
            sub_settings = JSON.parse(response.subject_settings);

            for (let i = 0; i < response.lessons.length; i++) {
                var lesson = response.lessons[i];

                if (temp_day != lesson.day) {
                    temp_day = lesson.day;
                    var cat = document.createElement('div');
                    cat.classList.add('cat');
                    switch (lesson.day) {
                        case '1':
                            cat.innerHTML = 'Montag';
                            break;
                        case '2':
                            cat.innerHTML = 'Dienstag';
                            break;
                        case '3':
                            cat.innerHTML = 'Mittwoch';
                            break;
                        case '4':
                            cat.innerHTML = 'Donnerstag';
                            break;
                        case '5':
                            cat.innerHTML = 'Freitag';
                    }
                    content.insertBefore(cat, spinner);
                }

                createCard(
                    lesson.subject_short,
                    lesson.number,
                    lesson.teacher,
                    lesson.room
                );
            }
            content.removeChild(spinner);
        }
    })
    .catch(error => console.error('Error:', error));

function createCard(subject, number, teacher, room) {
    var card = document.createElement('div');
    var icon = document.createElement('div');
    var icon_text = document.createElement('p');
    var text = document.createElement('div');
    var head = document.createElement('p');
    var head_right = document.createElement('p');
    var sub = document.createElement('p');
    var sub_right = document.createElement('p');
    card.classList.add('icard');
    icon.classList.add('icon');
    icon.style.backgroundColor = '#000000';
    text.classList.add('text');
    head.classList.add('head');
    head_right.classList.add('head-right');
    sub.classList.add('sub');
    sub_right.classList.add('sub-right');

    icon_text.innerHTML = subject;
    head.innerHTML = number + '. Stunde';
    head_right.innerHTML = ' ';
    sub.innerHTML = teacher;
    sub_right.innerHTML = room;

    icon.appendChild(icon_text);

    text.appendChild(head);
    text.appendChild(head_right);
    text.appendChild(sub);
    text.appendChild(sub_right);

    card.appendChild(icon);
    card.appendChild(text);

    content.insertBefore(card, spinner);
}
