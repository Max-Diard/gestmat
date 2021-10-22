window.addEventListener("DOMContentLoaded", (event) => {

// Pour les détails de l'adhérent dans la liste
    const rowTable = document.querySelectorAll('.js-adherent');
    const womenRowTable = document.querySelectorAll('.women');
    const menRowTable = document.querySelectorAll('.men');

    if (url.hash.includes('men')) {
        const manInUrl = url.hash.indexOf('men')
        const resultMan = url.hash.slice(manInUrl)
        const startId = resultMan.indexOf('=')
        const idManInUrl = resultMan.slice(startId + 1)

        apiMeet(idManInUrl)
    }

    if (url.hash.includes('woman')) {

        const womanInUrl = url.hash.indexOf('woman')
        if (url.hash.indexOf('&') != -1) {
            const endWomanInUrl = url.hash.indexOf('&')
            const resultWoman = url.hash.slice(womanInUrl, endWomanInUrl)
            const startId = resultWoman.indexOf('=')
            const idWomanInUrl = resultWoman.slice(startId + 1)

            apiMeet(idWomanInUrl)
        } else {
            const startId = url.hash.indexOf('=')
            const idWomanInUrl = url.hash.slice(startId + 1)

            apiMeet(idWomanInUrl)
        }
    }

    if (rowTable) {
        [].forEach.call(rowTable, function (elem) {
            elem.addEventListener('click', function (ev) {
                let tabMan = elem.parentNode.classList.contains('men');
                if (tabMan === true) {
                    [].forEach.call(menRowTable, function(element) {
                        element.classList.remove('active')
                    });
                }
                let tabWoman = elem.parentNode.classList.contains('women');
                if (tabWoman === true) {
                    [].forEach.call(womenRowTable, function (element) {
                        element.classList.remove('active')
                    });
                }

                elem.parentNode.classList.add('active');

                ev.preventDefault();
                apiMeet(elem.getAttribute('data-id'));
            })
        })
    }

//Pour la page single Adhérents
    const meetMoreButton = document.querySelectorAll('.meet-more');
    const meetDeleteButton = document.querySelectorAll('.meet-delete');

    if (meetMoreButton) {
        [].forEach.call(meetMoreButton, function (elem) {
            elem.addEventListener('click', function (ev) {
                ev.preventDefault()
                informationMeet(elem.getAttribute('data-id'))
            })
        })
    }

    if (meetDeleteButton) {
        [].forEach.call(meetDeleteButton, function (elem) {
            elem.addEventListener('click', function (ev) {
                ev.preventDefault()
                Swal.fire({
                    title: 'Vous voulez vraiment supprimer cette rencontre ?',
                    showDenyButton: true,
                    showCancelText: 'Non',
                    confirmButtonText: 'Oui',
                    confirmButtonOnClick: '#',
                    denyButtonText: `Non`,
                    icon: 'question'
                }).then((result) => {
                    if (result.isConfirmed) {
                        removeMeet(elem.getAttribute('data-id'))

                    } else if (result.isDenied) {
                        Swal.fire("La rencontre n'a pas été supprimée !", '', 'info')
                    }
                })
            })
        })
    }
})