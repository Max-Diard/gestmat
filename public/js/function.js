
// Function pour appeler l'api qui récupére l'adhérent sélectionner
function apiMeet(id){
    const cardSelectedWoman = document.querySelector('.card-selected-woman')
    const cardSelectedMan = document.querySelector('.card-selected-man')

    const nameAgence = document.querySelector('.name-agence-datatable').getAttribute('name-agence')

    const lastnameWoman = document.querySelector('.thead-card-woman-lastname');
    const firstnameWoman = document.querySelector('.thead-card-woman-firstname');
    const yearWoman = document.querySelector('.thead-card-woman-years');
    const agenceWoman = document.querySelector('.thead-card-woman-agence');

    const lastnameMan = document.querySelector('.thead-card-man-lastname');
    const firstnameMan = document.querySelector('.thead-card-man-firstname');
    const yearMan = document.querySelector('.thead-card-man-years');
    const agenceMan = document.querySelector('.thead-card-man-agence');

    const div = document.querySelector('.button-meet')
    div.innerHTML = '';
    const buttonModal = document.createElement('a');
    buttonModal.style.display = false;

    //Loader
    const loaderMeet = document.querySelector('body');

    const divContainerLoader = document.createElement('div')
    divContainerLoader.className = 'container-loader'
    const divLoader = document.createElement('div');
    divLoader.className = 'loader';

    divContainerLoader.appendChild(divLoader)

    loaderMeet.appendChild(divContainerLoader)

    const request = new XMLHttpRequest();
    request.open("GET", "http://127.0.0.1:8000/api/adherent/" + id, true);
    request.addEventListener('readystatechange', function(){
        if (request.readyState === XMLHttpRequest.DONE) {
            if (request.status === 200) {
                //Fin du loader
                loaderMeet.removeChild(divContainerLoader)

                const recup = JSON.parse(request.response);
                if (recup.adherent[0].genre.name === 'Féminin'){
                    const listClass = cardSelectedWoman.classList

                    if(nameAgence != recup.adherent[0].agence.name){
                        if(listClass[1] === 'in-agence-woman'){
                            cardSelectedWoman.classList.remove('in-agence-woman')
                        }
                        cardSelectedWoman.classList.add('not-in-agence-woman')
                    } else {
                        if(listClass[1] === 'not-in-agence-woman'){
                            cardSelectedWoman.classList.remove('not-in-agence-woman')
                        }
                        cardSelectedWoman.classList.add('in-agence-woman')
                    }

                    lastnameWoman.textContent = recup.adherent[0].lastname;
                    firstnameWoman.textContent = recup.adherent[0].firstname;
                    yearWoman.textContent = recup.adherent[0].age;
                    agenceWoman.textContent = recup.adherent[0].agence.name;
                    meetWaitingWomen = recup.adherent[0].id
                    loadingWomen = true

                    myParams.set('woman', recup.adherent[0].id)
                    newUrl = url.toString()
                    const womanUrl = newUrl.split('adherent')[1]

                    if(myParams.get('men')){
                        window.location.hash = '?woman=' + myParams.get('woman') + '&men=' + myParams.get('men')
                    } else if(myParams.get('woman')){
                        window.location.hash = '?woman=' + myParams.get('woman')
                    } else{
                        window.location.hash = womanUrl
                    }

                    infoWoman = recup

                    informationMeetWoman(recup, cardSelectedWoman);
                }
                else {
                    const listClass = cardSelectedMan.classList

                    if(nameAgence != recup.adherent[0].agence.name){
                        if(listClass[1] === 'in-agence-man'){
                            cardSelectedMan.classList.remove('in-agence-man')
                        }
                        cardSelectedMan.classList.add('not-in-agence-man')
                    } else {
                        if(listClass[1] === 'not-in-agence-man'){
                            cardSelectedMan.classList.remove('not-in-agence-man')
                        }
                        cardSelectedMan.classList.add('in-agence-man')
                    }

                    lastnameMan.textContent = recup.adherent[0].lastname;
                    firstnameMan.textContent = recup.adherent[0].firstname;
                    yearMan.textContent = recup.adherent[0].age;
                    agenceMan.textContent = recup.adherent[0].agence.name;
                    meetWaitingMen = recup.adherent[0].id
                    loadingMen = true

                    myParams.set('men', recup.adherent[0].id)
                    newUrl = url.toString()
                    const manUrl = newUrl.split('adherent')[1]

                    if(myParams.get('woman')){
                        window.location.hash = '?woman=' + myParams.get('woman') + '&men=' + myParams.get('men')
                    } else if(myParams.get('men')){
                        window.location.hash = '?men=' + myParams.get('men')
                    } else{
                        window.location.hash = manUrl
                    }
                    infoMan = recup;

                    informationMeetMan(recup, cardSelectedMan);

                }
                if(loadingWomen && loadingMen){
                    // On regarde si il n'y a pas un bouton d'affiché avant de l'afficher
                    if(div.hasChildNodes()){
                        div.removeChild(div.lastChild);
                    }

                    // Création du bouton pour créer la rencontre
                    buttonModal.className = 'btn btn-little button-create-meet'
                    buttonModal.textContent = 'Créer une nouvelle rencontre'
                    buttonModal.href = '#'
                    div.appendChild(buttonModal)
                    buttonModal.style.display = true;

                    //On regarde si une rencontre n'a pas déjà été effectué entre les 2 personnes sélectionner
                    if(infoWoman.meet.length > 0){
                        for (let i = 0; i < infoWoman.meet.length; i++){
                            if(infoWoman.meet[i].adherent_man.id == infoMan.adherent[0].id){
                                alreadyMeet = true
                                break;
                            }
                            else {
                                alreadyMeet = false
                            }
                        }
                    }

                    buttonModal.addEventListener('click', function(ev){
                        ev.preventDefault()

                        const today = new Date();
                        const dateToday = today.toISOString().split('T')[0];

                        let test =  '<p>' + lastnameWoman.textContent + ' ' + firstnameWoman.textContent + ' et ' + lastnameMan.textContent + ' ' + firstnameMan.textContent + '?</p>' +
                            '<input type="date" class="swal2-input" id="expiry-date" value="'+ dateToday + '" required>';

                        if (alreadyMeet === true){
                            test += '<p style="margin-top : 1em">Attention, une rencontre a déjà été éffectuée entre ces deux personnes !</p>';
                        } else if(infoWoman.adherent[0].status_meet.name != 'Disponible' && infoMan.adherent[0].status_meet.name != 'Disponible'){
                            test += '<p style="margin-top : 1em">Attention, ces deux personnes ne sont pas disponible pour le moment !</p>';
                        } else if(infoWoman.adherent[0].status_meet.name != 'Disponible') {
                            test += '<p style="margin-top : 1em">Attention, ' + lastnameWoman.textContent + ' ' + firstnameWoman.textContent + ' n\'est pas disponible pour le moment !</p>';
                        } else if(infoMan.adherent[0].status_meet.name != 'Disponible') {
                            test += '<p style="margin-top : 1em">Attention, ' + lastnameMan.textContent + ' ' + firstnameMan.textContent + ' n\'est pas disponible pour le moment !</p>';
                        }

                        Swal.fire({
                            title: 'Vous voulez créer une rencontre entre :',
                            showDenyButton: true,
                            confirmButtonText: 'Oui',
                            denyButtonText: `Non`,
                            icon: 'question',
                            html: test

                        }).then((result) => {
                            if (result.isConfirmed) {
                                const inputDate = document.querySelector('#expiry-date')
                                newMeet(meetWaitingWomen, meetWaitingMen, inputDate.value)
                                apiMeet(meetWaitingWomen)
                                apiMeet(meetWaitingMen)

                            } else if (result.isDenied) {
                                Swal.fire("La rencontre n'a pas été effectuée !", '', 'info')
                            }
                        })
                    })
                }
            }
        }
    })
    request.send();
}

// Function appeler dans la function 'apiMeet' ppour afficher les rencontres de l'adhérent sélectionner ici pour une femme
function informationMeetWoman(recup, card){
    const divMeetWoman = document.querySelector('.woman-meeting');

    if(divMeetWoman.hasChildNodes()){
        while(divMeetWoman.hasChildNodes()){
            divMeetWoman.removeChild(divMeetWoman.lastChild)
        }
    }

    if (recup.meet.length > 0){
        let j = recup.meet.length - 1 ;
        while(j != -1){
            const row = document.createElement('tr')

            const celliD = document.createElement('td')
            celliD.textContent = recup.meet[j].id

            const cellReturnAt = document.createElement('td')
            if (recup.meet[j].returnAt_woman){
                const returnAt = new Date(recup.meet[j].returnAt_woman)
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
                if(card.classList[1] === 'in-agence-woman'){
                    ev.preventDefault()
                    informationMeet(idMeet)
                } else {
                    Swal.fire({
                        title: 'Donnés non disponible',
                        text: 'Vous n\'avez pas accès à cette donnée !',
                        icon: 'info'
                    })
                }
            })

            cellMore.appendChild(cellLinkMore)

            const cellDelete = document.createElement('td')
            const cellLinkDelete = document.createElement('a')

            cellLinkDelete.href = '#'
            cellLinkDelete.classList.add('suppr-meet')
            cellLinkDelete.innerHTML = '<img src="/build/images/delete.svg" alt="supprimer">'
            cellLinkDelete.addEventListener('click', function(){
                if(card.classList[1] === 'in-agence-woman'){
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
                            removeMeet(idMeet)
                            recup.meet.forEach(s => {
                                if (s.id === idMeet){
                                    apiMeet(s.adherent_woman.id)
                                    apiMeet(s.adherent_man.id)
                                }
                            })
                        }
                    })
                } else {
                    Swal.fire({
                        title: 'Suppression impossible',
                        text: 'Impossible de supprimer cette rencontre',
                        icon: 'info'
                    })
                }
            })

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
            j--;
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

// Function appeler dans la function 'apiMeet' ppour afficher les rencontres de l'adhérent sélectionner ici pour un homme
function informationMeetMan(recup, card){
    const divMeetMan = document.querySelector('.man-meeting');

    if(divMeetMan.hasChildNodes()){
        while(divMeetMan.hasChildNodes()){
            divMeetMan.removeChild(divMeetMan.lastChild)
        }
    }

    if(recup.meet.length > 0){
        let j = recup.meet.length - 1 ;
        while(j != -1){
            const row = document.createElement('tr')

            const celliD = document.createElement('td')
            celliD.textContent = recup.meet[j].id

            const cellReturnAt = document.createElement('td')
            if (recup.meet[j].returnAt_man){
                const returnAt = new Date(recup.meet[j].returnAt_man)
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

            let idMeet = recup.meet[j].id

            cellLinkMore.addEventListener('click', function (ev) {
                if(card.classList[1] === 'in-agence-man') {
                    ev.preventDefault()
                    informationMeet(idMeet)

                } else {
                    Swal.fire({
                        title: 'Donnée non disponible',
                        text: 'Vous n\'avez pas accès à cette donnée !',
                        icon: 'info'
                    })
                }
            })

            cellMore.appendChild(cellLinkMore)

            const cellDelete = document.createElement('td')
            const cellLinkDelete = document.createElement('a')
            cellLinkDelete.href = '#'
            cellLinkDelete.classList.add('suppr-meet')
            cellLinkDelete.innerHTML = '<img src="build/images/delete.svg" alt="Supprimer">'
            cellLinkDelete.addEventListener('click', function(){
                if(card.classList[1] === 'in-agence-man'){
                    Swal.fire({
                        title: 'Vous voulez vraiment supprimer cette rencontre ?',
                        showDenyButton: true,
                        showCancelText: 'Non',
                        confirmButtonText: 'Oui',
                        confirmButtonOnClick: '#',
                        denyButtonText: `Non`,
                        icon: 'question'
                    }).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed) {
                            removeMeet(idMeet)
                            apiMeet(recup.adherent[0].id)
                        }
                    })
                }else{
                    Swal.fire({
                        title: 'Suppression impossible',
                        text: 'Impossible de supprimer cette rencontre',
                        icon: 'info'
                    })
                }
            })

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

            j--;
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
    const womanLinkPdf = document.querySelector('.link-pdf-woman');
    const womanPosition = document.querySelector('.meet-woman-position');

    const dateMeet = document.querySelector('.date-meet');
    const idMeet = document.querySelector('.id-meet');

    const manLastname = document.querySelector('.man-lastname');
    const manFirstname = document.querySelector('.man-firstname');
    const manAgence = document.querySelector('.man-agence');
    const manDateReturn = document.querySelector('.man-date-returnAt');
    const manAction = document.querySelector('.man-action');
    const manComments = document.querySelector('.man-comments');
    const manLinkPdf = document.querySelector('.link-pdf-man');
    const manPosition = document.querySelector('.meet-man-position');

    const closeModal = document.querySelector('.close-modal');
    const sendButton = document.querySelector('.send-form');

    closeModal.addEventListener('click', function (ev){
        ev.preventDefault()
        modalAdherent.style.display = 'none'
    })

    modalAdherent.style.display = 'block'

    // Loader
    const loaderMeet = document.querySelector('body');

    const divContainerLoader = document.createElement('div')
    divContainerLoader.className = 'container-loader'

    const divLoader = document.createElement('div');
    divLoader.className = 'loader';

    divContainerLoader.appendChild(divLoader)

    loaderMeet.appendChild(divContainerLoader)

    const request = new XMLHttpRequest();
    request.open("GET", "http://127.0.0.1:8000/api/meet/" + id, true);
    request.addEventListener('readystatechange', function(){
        if (request.readyState === XMLHttpRequest.DONE) {
            if (request.status === 200) {
                // Fin du loader
                loaderMeet.removeChild(divContainerLoader)

                const recup = JSON.parse(request.response)[0];

                //Information de la rencontre côté femme
                womanLastname.textContent = recup.adherent_woman.lastname;
                womanFirstname.textContent = recup.adherent_woman.firstname;
                womanAgence.textContent = recup.adherent_woman.agence.name;
                if (recup.actionMeetWoman != null){
                    womanAction.value = recup.actionMeetWoman.name
                } else {
                    womanAction.value = '';
                }

                if (recup.returnAt_woman != null){
                    const returnAtWoman = new Date(recup.returnAt_woman)
                    womanDateReturn.value = returnAtWoman.toISOString().split('T')[0];
                }else {
                    womanDateReturn.value = ''
                }
                womanPosition.value = recup.adherent_woman.status_meet.name;
                womanComments.textContent = recup.comments_woman;

                womanLinkPdf.href = '/meet/file/' + recup.adherent_woman.id + '-' + recup.adherent_man.id
                womanLinkPdf.target = '_blank'

                //Information de la rencontre général
                const startedAt = new Date(recup.startedAt)
                dateMeet.textContent = new Intl.DateTimeFormat('fr-FR').format(startedAt)
                idMeet.textContent = recup.id;

                //Information de la rencontre côté homme
                manLastname.textContent = recup.adherent_man.lastname;
                manFirstname.textContent = recup.adherent_man.firstname;
                manAgence.textContent = recup.adherent_man.agence.name;
                if (recup.actionMeetMan != null){
                    manAction.value = recup.actionMeetMan.name
                } else {
                    manAction.value = '';
                }

                if (recup.returnAt_man != null){
                    const returnAtMan = new Date(recup.returnAt_man)
                    manDateReturn.value = returnAtMan.toISOString().split('T')[0];
                }else {
                    manDateReturn.value = '';
                }
                manPosition.value = recup.adherent_man.status_meet.name;
                manComments.textContent = recup.comments_man;

                manLinkPdf.href = '/meet/file/' + recup.adherent_man.id + '-' + recup.adherent_woman.id
                manLinkPdf.target = '_blank'

                sendButton.href = '/api/update_meet/'

                sendButton.addEventListener('click', function(ev){
                    ev.preventDefault()
                    updateMeet(
                        recup.id,
                        womanPosition.value,
                        womanAction.value,
                        womanDateReturn.value,
                        womanComments.value,

                        manPosition.value,
                        manAction.value,
                        manDateReturn.value,
                        manComments.value,
                    )
                    Swal.fire(
                        'Information Envoyé!',
                        'Les imformations de la rencontre ont bien était envoyé!',
                        'success',
                    ).then((result) => {
                        if (result.isConfirmed){
                            location.reload();
                        }
                    })
                    // modalAdherent.style.display = 'none'
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
    actionWoman,
    returnWoman,
    commentsWoman,
    statutsMeetMan,
    actionMan,
    returnMan,
    commentsMan,
){
    const request = new XMLHttpRequest();
    request.open('POST', 'http://127.0.0.1:8000/api/update_meet/', true)
    request.setRequestHeader("content-type", "application/json; charset=utf-8")
    request.send(JSON.stringify({
        'id': id,
        'status_meet_woman' : statutsMeetWoman,
        'action_woman' : actionWoman,
        'returnAt_woman' : returnWoman,
        'comments_woman' : commentsWoman,
        'status_meet_man' : statutsMeetMan,
        'action_man' : actionMan,
        'returnAt_man' : returnMan,
        'comments_man' : commentsMan
    }))
}

// Permet de créer une rencontre en Api
function newMeet(woman, man, date){
    const request = new XMLHttpRequest();
    request.open('POST', 'http://127.0.0.1:8000/api/new_meet/', true)
    request.setRequestHeader("content-type", "application/json; charset=utf-8")
    request.send(JSON.stringify({
        woman: woman,
        man: man,
        date: date
    }))
}

// Remove rencontre en api
function removeMeet(id){
    const request = new XMLHttpRequest();
    request.open('DELETE', 'http://127.0.0.1:8000/api/meet/delete/' + id, true)
    request.setRequestHeader("content-type", "application/json; charset=utf-8")
    request.send(JSON.stringify({
        'id': id
    }))
    // window.reload;
}