window.addEventListener("DOMContentLoaded", (event) => {

// Pour la page Rencontre
    const dateMeetStart = document.querySelector('.input-date-meet-start');
    const dateMeetEnd = document.querySelector('.input-date-meet-end');
    const linkSearchMeet = document.querySelector('.link-search-meet-date');
    const buttonMeetMore = document.querySelectorAll('.page-meet-more');

    if (dateMeetStart) {
        if (dateMeetStart.value === '') {
            const date = window.location.pathname.slice(-21);
            dateMeetStart.value = date.slice(0, 10);
        }
        if (dateMeetEnd.value === '') {
            dateMeetEnd.value = window.location.pathname.slice(-10);
        }
    }

    if (linkSearchMeet) {
        linkSearchMeet.addEventListener('click', () => {
            if (dateMeetStart.value != '' && dateMeetEnd.value != '') {
                window.location = '/meet/search/' + dateMeetStart.value + '/' + dateMeetEnd.value;
            } else {
                Swal.fire({
                    title: 'Recherche impossible',
                    text: 'Attention, vous devez sélectionner 2 dates !',
                    icon: 'error'
                })
            }
        })
    }

    if (buttonMeetMore) {
        [].forEach.call(buttonMeetMore, function (elem) {
            elem.addEventListener('click', function (ev) {
                const id = elem.getAttribute('data-id')
                informationMeet(id)
            })
        })
    }
})