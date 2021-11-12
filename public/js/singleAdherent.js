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

// Pour voir le fichier envoyé dans l'input
    const allInputDocument = document.querySelectorAll('.tab-documents input')

    if(allInputDocument){
        [].forEach.call(allInputDocument, function(elem) {
            elem.addEventListener('change', function (e){
                if(e.target.files[0].size >= 1000000){
                    Swal.fire({
                        title: 'Erreur fichier !',
                        text: 'Taille de l\'image trop grande, merci de choisir une autre image !',
                        icon: 'error'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            e.target.value = '';
                        }
                    })
                } else {
                    Swal.fire({
                        title: 'Fichier valide !',
                        text: 'Le fichier est prêt à être enregistrer !',
                        icon: 'success'
                    })
                }
            })
        })
    }

// Pour créer une erreur si un champ required est pas remplie
    const buttonSendForm = document.querySelector('#adherent_submit')

    if(buttonSendForm){
        buttonSendForm.addEventListener('click', (ev) => {
            const arrayValueEmpty = [];
            [].forEach.call(document.querySelectorAll('input'), function(elem){
                if(elem.getAttribute('required')){
                    if(elem.value === ''){
                        ev.preventDefault()
                        arrayValueEmpty.push(elem.getAttribute('name_input'))
                        console.log(arrayValueEmpty)
                        let addHtml = ''
                        for (let i = 0; i < arrayValueEmpty.length; i++){
                            addHtml += '<li>' + arrayValueEmpty[i] + '</li>'
                        }
                        Swal.fire({
                            title: 'Formulaire non envoyé !',
                            html: 'Il manque des informations pour : <ul>' + addHtml + '</ul>',
                            icon: 'info'
                        })
                    } else if (document.getElementById('adherent_email') !== ''){
                        const regexEmail = new RegExp("^([a-zA-Z0-9\-_\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$");
                        const emailValue = document.getElementById('adherent_email').value
                        if(!regexEmail.test(emailValue)){
                            Swal.fire({
                                title: 'Formulaire non envoyé !',
                                html: 'Merci de rentrer un email valide !',
                                icon: 'info'
                            })
                        }
                    }
                }
            })
        })
    }

//Pour afficher les erreurs invalides du formulaire
    const errorFormDisplay = document.querySelector('.error-form-display')

    if(errorFormDisplay){
        const colError = document.querySelectorAll('.col ul');
        [].forEach.call(colError, (elem) => {
            let test = elem.parentNode.childNodes
                console.log(test)
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
                        location.reload()
                    } else if (result.isDenied) {
                        Swal.fire("La rencontre n'a pas été supprimée !", '', 'info')
                    }
                })
            })
        })
    }
})