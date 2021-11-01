window.addEventListener("DOMContentLoaded", (event) => {

//Pour les onglets dans Single Adherent
    const buttonTab = document.querySelectorAll('.open-tab');

    if (buttonTab) {
        [].forEach.call(buttonTab, function (elem) {
            elem.addEventListener('click', function (ev) {
                let tab = elem.getAttribute('data-tab');
                let allTab = document.querySelectorAll('.tab');
                [].forEach.call(allTab, function (singleTab) {
                    singleTab.classList.add('no-tab');
                })
                document.querySelector('.' + tab).classList.remove('no-tab');
            })
        })
    }

// Pour créer une erreur si un champ required est pas remplie
    const buttonSendForm = document.querySelector('#adherent_submit')

    if(buttonSendForm){
        buttonSendForm.addEventListener('click', (ev) => {
                        const test = [];
            [].forEach.call(document.querySelectorAll('input'), function(elem){
                if(elem.getAttribute('required')){
                    if(elem.value === ''){
                        ev.preventDefault()
                        test.push(elem.getAttribute('name_input'))
                        let addHtml = ''
                        for (let i = 0; i < test.length; i++){
                            addHtml += '<li>' + test[i] + '</li>'
                        }

                        Swal.fire({
                            title: 'Il manque des informations',
                            html: 'Il manque des informations pour: <ul>' + addHtml + '</ul>',
                            icon: 'info'
                        })
                    }
                }
            })
        })
    }
//Pour le button meet de la page single Adhérents
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