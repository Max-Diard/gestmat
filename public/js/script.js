let loadingMen = false
let loadingWomen = false
let meetWaitingWomen = '';
let meetWaitingMen = '';

// Tableau Adhérents all women
$(document).ready( function () {
    $('.table-women').DataTable({
        paging: false,
        searching: false,
        info: false
    });
} );

// Tableau Adhérents all men
$(document).ready( function () {
    $('.table-men').DataTable({
        paging: false,
        searching: false,
        info: false
    });
} );

//Tableau pour la recherche
$(document).ready( function () {
    $('.table-search').DataTable({
        paging: false,
        info: false
    });
} );

//Tableau pour les rencontres
$(document).ready( function () {
    $('.table-meet').DataTable({
        paging: false,
        info: false
    });
} );

// On attend que la page sois charger
window.addEventListener("DOMContentLoaded", (event) => {
    //Pour les onglets dans Single Adherent
    const buttonTab = document.querySelectorAll('.open-tab');

    if (buttonTab){
        [].forEach.call(buttonTab, function(elem){
            elem.addEventListener('click', function(ev){
                let tab = elem.getAttribute('data-tab');
                let allTab = document.querySelectorAll('.tab');
                [].forEach.call(allTab, function(singleTab){
                    singleTab.classList.add('no-tab');
                })
                document.querySelector('.' + tab).classList.remove('no-tab');
            })
        })
    }

    // Pour les détails de l'adhérent dans la liste
    const rowTable = document.querySelectorAll('.js-adherent');

    if(rowTable){
        [].forEach.call(rowTable, function(elem){
            elem.addEventListener('click', function(ev){
                ev.preventDefault();
                apiMeet(elem.getAttribute('data-id'));
            })
        })
    }

    //Pour la page single Adhérents
    const meetMoreButton = document.querySelectorAll('.meet-more');

    if(meetMoreButton){
        [].forEach.call(meetMoreButton, function (elem){
            elem.addEventListener('click', function(ev) {
                ev.preventDefault()
                informationMeet(elem.getAttribute('data-id'))
            })
        })
    }

    // Pour la page Rencontre
    const dateMeet = document.querySelector('.input-date-meet');
    const linkMeet = document.querySelector('.link-meet');

    if(dateMeet){
        dateMeet.addEventListener('input', function (ev){
            ev.preventDefault()
            linkMeet.href = '/meet/search/' + this.value
        })
    }
});

// Function pour appeler l'api qui récupére l'adhérent sélectionner
function apiMeet(id){
    const modal = document.querySelector('.modal');
    const modalText = document.querySelector('.modal-text');
    const modalMeetWomen = document.querySelector('.modal-women-meet')
    const modalMeetMen = document.querySelector('.modal-men-meet')
    const modalButton = document.querySelector('.modal-button')

    const lastnameWoman = document.querySelector('.thead-card-woman-lastname');
    const firstnameWoman = document.querySelector('.thead-card-woman-firstname');
    const yearWoman = document.querySelector('.thead-card-woman-years');
    const meetWoman = document.querySelector('.thead-card-woman-meet');
    const agenceWoman = document.querySelector('.thead-card-woman-agence');

    const lastnameMan = document.querySelector('.thead-card-man-lastname');
    const firstnameMan = document.querySelector('.thead-card-man-firstname');
    const yearMan = document.querySelector('.thead-card-man-years');
    const meetMan = document.querySelector('.thead-card-man-meet');
    const agenceMan = document.querySelector('.thead-card-man-agence');

    const div = document.querySelector('.button-meet')
    div.innerHTML = '';
    const buttonModal = document.createElement('a');
    buttonModal.style.display = false;

    // Ajout d'un loader ?

    const request = new XMLHttpRequest();
    request.open("GET", "http://127.0.0.1:8000/api/adherent/" + id, true);
    request.addEventListener('readystatechange', function(){
        if (request.readyState === XMLHttpRequest.DONE) {
            if (request.status === 200) {
                // Fin du loader ?
                const recup = JSON.parse(request.response);

                if (recup.adherent[0].genre.name === 'Féminin'){
                    lastnameWoman.textContent = recup.adherent[0].lastname;
                    firstnameWoman.textContent = recup.adherent[0].firstname;
                    yearWoman.textContent = recup.adherent[0].age;
                    agenceWoman.textContent = recup.adherent[0].agence.name + ' - ' + recup.adherent[0].agence.address_town;
                    meetWaitingWomen = recup.adherent[0].id
                    loadingWomen = true
                    informationMeetWoman(recup);
                } else {
                    lastnameMan.textContent = recup.adherent[0].lastname;
                    firstnameMan.textContent = recup.adherent[0].firstname;
                    yearMan.textContent = recup.adherent[0].age;
                    agenceMan.textContent = recup.adherent[0].agence.name + ' - ' + recup.adherent[0].agence.address_town;
                    meetWaitingMen = recup.adherent[0].id
                    loadingMen = true
                    informationMeetMan(recup);
                }

                if(loadingWomen && loadingMen){
                    // Création du bouton pour créer la rencontre
                    buttonModal.className = 'button-create-meet'
                    buttonModal.textContent = 'Créer une nouvelle rencontre'
                    buttonModal.href = '#'
                    div.appendChild(buttonModal)
                    buttonModal.style.display = true;

                    buttonModal.addEventListener('click', function(ev){
                        ev.preventDefault()

                        // Affichage de la modal
                        modal.style.display = 'block';

                        modalMeetWomen.textContent = 'Femme : ' + lastnameWoman.textContent + ' ' + firstnameWoman.textContent
                        modalMeetMen.textContent = 'Homme : ' + lastnameMan.textContent + ' ' + firstnameMan.textContent

                        // Création des buttons de la modal
                        let buttonYes = document.createElement('a');
                        const buttonNo = document.createElement('a');

                        buttonYes.textContent = 'Oui'
                        buttonYes.href = 'meet/new/' + meetWaitingWomen + '-' + meetWaitingMen
                        buttonNo.textContent = 'Non'
                        buttonNo.href = '#'

                        modalButton.appendChild(buttonYes)
                        modalButton.appendChild(buttonNo)

                        const inputDate = document.createElement('input')
                        inputDate.type = 'date'
                        inputDate.value = Date.now()
                        modalText.appendChild(inputDate)

                        inputDate.addEventListener('input', function (){
                            buttonYes.href += '-' + this.value
                        })

                        buttonNo.addEventListener('click', function(ev){
                            ev.preventDefault()
                            modal.style.display = 'none';
                            modalText.removeChild(inputDate)
                            modalButton.removeChild(buttonYes)
                            modalButton.removeChild(buttonNo)
                        })
                    })
                }
            }
        }
    })
    request.send();
}

// Function appeler dans la function'api' ppour afficher les rencontres de l'adhérent sélectionner ici pour une femme
function informationMeetWoman(recup){
    const divMeetWoman = document.querySelector('.woman-meeting');

    if(divMeetWoman.hasChildNodes()){
        while(divMeetWoman.hasChildNodes()){
            divMeetWoman.removeChild(divMeetWoman.lastChild)
        }
    }

    if (recup.meet.length > 0){
        let j = 0
        while(j < recup.meet.length){
            const row = document.createElement('tr')

            const celliD = document.createElement('td')
            celliD.textContent = recup.meet[j].id

            const cellReturnAt = document.createElement('td')
            if (recup.meet[j].returnAt){
                const returnAt = new Date(recup.meet[j].returnAt)
                cellReturnAt.textContent =  new Intl.DateTimeFormat('fr-FR').format(returnAt)
            }else {
                cellReturnAt.textContent = ''
            }

            const cellLastname = document.createElement('td')
            cellLastname.textContent = recup.meet[j].adherent_man.lastname

            const cellFirstname = document.createElement('td')
            cellFirstname.textContent = recup.meet[j].adherent_man.firstname

            const cellAgence = document.createElement('td')
            cellAgence.textContent = recup.meet[j].adherent_man.agence.name

            const cellStarted = document.createElement('td')
            const startedAt = new Date(recup.meet[j].startedAt)
            cellStarted.textContent = new Intl.DateTimeFormat('fr-FR').format(startedAt)

            const cellMore = document.createElement('td')
            const cellLinkMore = document.createElement('a')
            cellLinkMore.href = '#'
            cellLinkMore.textContent = 'Voir plus'

            let idMeet = recup.meet[j].id

            cellLinkMore.addEventListener('click', function(ev){
                ev.preventDefault()
                informationMeet(idMeet)
            })

            cellMore.appendChild(cellLinkMore)

            const cellDelete = document.createElement('td')
            const cellLinkDelete = document.createElement('a')
            cellLinkDelete.href = '/meet/' + recup.meet[j].id + '/remove/ask'
            cellLinkDelete.textContent = 'Supprimer'

            cellDelete.appendChild(cellLinkDelete)

            row.appendChild(celliD)
            row.appendChild(cellReturnAt)
            row.appendChild(cellLastname)
            row.appendChild(cellFirstname)
            row.appendChild(cellAgence)
            row.appendChild(cellStarted)
            row.appendChild(cellMore)
            row.appendChild(cellDelete)

            divMeetWoman.appendChild(row)
            j++;
        }
    } else {
        const row = document.createElement('tr')

        const noMeet = document.createElement('td')
        noMeet.colSpan = 8
        noMeet.textContent = 'Pas de rencontre pour le moment'

        row.appendChild(noMeet)
        divMeetWoman.appendChild(row)
    }
}

//Ici pour un homme
function informationMeetMan(recup){
    const divMeetMan = document.querySelector('.man-meeting');

    if(divMeetMan.hasChildNodes()){
        while(divMeetMan.hasChildNodes()){
            divMeetMan.removeChild(divMeetMan.lastChild)
        }
    }

    if(recup.meet.length > 0){
        let j = 0
        while(j < recup.meet.length){
            const row = document.createElement('tr')

            const celliD = document.createElement('td')
            celliD.textContent = recup.meet[j].id

            const cellReturnAt = document.createElement('td')
            if (recup.meet[j].returnAt){
                const returnAt = new Date(recup.meet[j].returnAt)
                cellReturnAt.textContent =  new Intl.DateTimeFormat('fr-FR').format(returnAt)
            }else {
                cellReturnAt.textContent = ''
            }

            const cellLastname = document.createElement('td')
            cellLastname.textContent = recup.meet[j].adherent_woman.lastname

            const cellFirstname = document.createElement('td')
            cellFirstname.textContent = recup.meet[j].adherent_woman.firstname

            const cellAgence = document.createElement('td')
            cellAgence.textContent = recup.meet[j].adherent_woman.agence.name

            const cellStarted = document.createElement('td')
            const startedAt = new Date(recup.meet[j].startedAt)
            cellStarted.textContent = new Intl.DateTimeFormat('fr-FR').format(startedAt)

            const cellMore = document.createElement('td')
            const cellLinkMore = document.createElement('a')
            cellLinkMore.href = '#'
            cellLinkMore.textContent = 'Voir plus'

            cellMore.appendChild(cellLinkMore)

            const cellDelete = document.createElement('td')
            const cellLinkDelete = document.createElement('a')
            cellLinkDelete.href = '#'
            cellLinkDelete.textContent = 'Supprimer'

            cellDelete.appendChild(cellLinkDelete)

            row.appendChild(celliD)
            row.appendChild(cellReturnAt)
            row.appendChild(cellLastname)
            row.appendChild(cellFirstname)
            row.appendChild(cellAgence)
            row.appendChild(cellStarted)
            row.appendChild(cellMore)
            row.appendChild(cellDelete)

            divMeetMan.appendChild(row)

            j++;
        }
    } else {
        const row = document.createElement('tr')

        const noMeet = document.createElement('td')
        noMeet.colSpan = 8
        noMeet.textContent = 'Pas de rencontre pour le moment'

        row.appendChild(noMeet)
        divMeetMan.appendChild(row)
    }
}

// Function pour appeler l'api qui récupére les infos du meet
function informationMeet(id){
    const modalAdherent = document.querySelector('.modal-adherent');

    const womanLastname = document.querySelector('.woman-lastname');
    const womanFirstname = document.querySelector('.woman-firstname');
    const womanAgence = document.querySelector('.woman-agence');
    const womanDateReturn = document.querySelector('.woman-date-returnAt');
    const womanAction = document.querySelector('.woman-action');
    const womanComments = document.querySelector('.woman-comments');

    const dateMeet = document.querySelector('.date-meet');
    const idMeet = document.querySelector('.id-meet');

    const manLastname = document.querySelector('.man-lastname');
    const manFirstname = document.querySelector('.man-firstname');
    const manAgence = document.querySelector('.man-agence');
    const manDateReturn = document.querySelector('.man-date-returnAt');
    const manAction = document.querySelector('.man-action');
    const manComments = document.querySelector('.man-comments');

    const closeModal = document.querySelector('.close-modal');
    const sendButton = document.querySelector('.send-form');

    closeModal.addEventListener('click', function (ev){
        ev.preventDefault()
        modalAdherent.style.display = 'none'
    })

    modalAdherent.style.display = 'block'

    // Ajout d'un loader ?

    const request = new XMLHttpRequest();
    request.open("GET", "http://127.0.0.1:8000/api/meet/" + id, true);
    request.addEventListener('readystatechange', function(){
        if (request.readyState === XMLHttpRequest.DONE) {
            if (request.status === 200) {
                // Fin du loader ?
                const recup = JSON.parse(request.response);
                //Information de la rencontre côté femme
                womanLastname.textContent = recup[0].adherent_woman.lastname;
                womanFirstname.textContent = recup[0].adherent_woman.firstname;
                womanAgence.textContent = recup[0].adherent_woman.agence.name + ' - ' + recup[0].adherent_woman.agence.address_town;

                if (recup[0].returnAt_woman != null){
                    const returnAtWoman = new Date(recup[0].returnAt_woman)
                    womanDateReturn.value = returnAtWoman.toISOString().split('T')[0];
                }else {
                    womanDateReturn.value = ''
                }
                if (recup[0].status_meet_woman != null){
                    womanAction.value = recup[0].status_meet_woman.name;
                } else{
                    womanAction.value = ''
                }
                womanComments.textContent = recup[0].comments_woman;

                //Information de la rencontre général
                const startedAt = new Date(recup[0].startedAt)
                dateMeet.textContent = new Intl.DateTimeFormat('fr-FR').format(startedAt)
                idMeet.textContent = recup[0].id;

                //Information de la rencontre côté homme
                manLastname.textContent = recup[0].adherent_man.lastname;
                manFirstname.textContent = recup[0].adherent_man.firstname;
                manAgence.textContent = recup[0].adherent_man.agence.name + ' - ' + recup[0].adherent_man.agence.address_town;

                if (recup[0].returnAt_man != null){
                    const returnAtMan = new Date(recup[0].returnAt_man)
                    manDateReturn.value = returnAtMan.toISOString().split('T')[0];
                }else {
                    manDateReturn.value = '';
                }

                if (recup[0].status_meet_man != null){
                    manAction.value = recup[0].status_meet_man.name;
                } else{
                    manAction.value = ''
                }
                manComments.textContent = recup[0].comments_man;


                sendButton.href = '/api/update_meet/'

                sendButton.addEventListener('click', function(ev){
                    ev.preventDefault()
                    updateMeet(
                        recup[0].id,
                        womanAction.value,
                        womanDateReturn.value,
                        womanComments.value,
                        manAction.value,
                        manDateReturn.value,
                        manComments.value,
                    )
                })
            }
        }
    })
    request.send();

}

// Function pour envoyer à l'api les informations de la rencontre
function updateMeet(
    id,
    statutsMeetWoman,
    returnWoman,
    commentsWoman,
    statutsMeetMan,
    returnMan,
    commentsMan,
){
    const request = new XMLHttpRequest();
    request.open('POST', 'http://127.0.0.1:8000/api/update_meet/', true)
    request.setRequestHeader("content-type", "application/json; charset=utf-8")
    request.send(JSON.stringify({
        'id': id,
        'status_meet_woman' : statutsMeetWoman,
        'returnAt_woman' : returnWoman,
        'comments_woman' : commentsWoman,
        'status_meet_man' : statutsMeetMan,
        'returnAt_man' : returnMan,
        'comments_man' : commentsMan
        }))
}