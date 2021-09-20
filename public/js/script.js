let loadingMen = false
let loadingWomen = false
let meetWaitingWomen = '';
let meetWaitingMen = '';

$(document).ready( function () {
    $('.table-women').DataTable({
        paging: false,
        searching: false
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
});


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
                while(i < recup.length){
                    if (recup[i].genre.name === 'Féminin'){
                        lastnameWoman.textContent = recup[i].lastname;
                        firstnameWoman.textContent = recup[i].firstname;
                        yearWoman.textContent = recup[i].age;
                        agenceWoman.textContent = recup[i].agence.name + ' - ' + recup[i].agence.address_town;
                        meetWaitingWomen = recup[i].id
                        loadingWomen = true
                        i++;
                    } else {
                        lastnameMan.textContent = recup[i].lastname;
                        firstnameMan.textContent = recup[i].firstname;
                        yearMan.textContent = recup[i].age;
                        agenceMan.textContent = recup[i].agence.name + ' - ' + recup[i].agence.address_town;
                        meetWaitingMen = recup[i].id
                        loadingMen = true
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

                        const date = document.createElement('input')
                        date.type = 'date'
                        date.value = Date.now()
                        modalText.appendChild(date)

                        date.addEventListener('input', function (){
                            buttonYes.href += '-' + this.value
                        })

                        buttonNo.addEventListener('click', function(ev){
                            ev.preventDefault()
                            modal.style.display = 'none';
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