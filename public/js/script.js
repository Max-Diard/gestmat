let loadingMen = false
let loadingWomen = false
let meetWaitingWomen = '';
let meetWaitingMen = '';

// Tableau Adhérents all
$(document).ready( function () {
    $('.table-women').DataTable({
        paging: false,
        searching: false,
        info: false
    });
} );

$(document).ready( function () {
    $('.table-men').DataTable({
        paging: false,
        searching: false,
        info: false
    });
} );

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

    // Pour les détails de l'adhérent
    const rowTable = document.querySelectorAll('.js-adherent');

    if(rowTable){
        [].forEach.call(rowTable, function(elem){
            elem.addEventListener('click', function(ev){
                ev.preventDefault();
                api(elem.getAttribute('data-id'));
            })
        })
    }

    //Pour la page single Adhérents
    const meetMoreButton = document.querySelectorAll('.meet-more');

    if(meetMoreButton){
        [].forEach.call(meetMoreButton, function (elem){
            elem.addEventListener('click', function(ev) {
                ev.preventDefault()
                // informationMeet(elem.getAttribute('data-id'))
            })
        })
    }

});

// Function pour appeler l'api qui récupére l'adhérent sélectionner
function api(id){
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
                let i = 0;
                while(i < recup.adherent.length){
                    if (recup.adherent[i].genre.name === 'Féminin'){
                        lastnameWoman.textContent = recup.adherent[i].lastname;
                        firstnameWoman.textContent = recup.adherent[i].firstname;
                        yearWoman.textContent = recup.adherent[i].age;
                        agenceWoman.textContent = recup.adherent[i].agence.name + ' - ' + recup.adherent[i].agence.address_town;
                        meetWaitingWomen = recup.adherent[i].id
                        loadingWomen = true
                        informationMeetWoman(recup);
                        i++;
                    } else {
                        lastnameMan.textContent = recup.adherent[i].lastname;
                        firstnameMan.textContent = recup.adherent[i].firstname;
                        yearMan.textContent = recup.adherent[i].age;
                        agenceMan.textContent = recup.adherent[i].agence.name + ' - ' + recup.adherent[i].agence.address_town;
                        meetWaitingMen = recup.adherent[i].id
                        loadingMen = true
                        informationMeetMan(recup);
                        i++;

                    }
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

// Function appeler dans la function'api' ppour afficher les rencontres de l'adhérent sélectionner
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
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };

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