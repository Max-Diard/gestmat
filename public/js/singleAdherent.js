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

// Pour changer le format de téléphone à l'affichage
//     const phoneForm = document.querySelectorAll('.phone_form')
//
//     if(phoneForm){
//         [].forEach.call(phoneForm, function (elem) {
//             if(elem.value){
//                 if(elem.value.length === 10){
//                     elem.value = changeFormatTel(elem.value)
//                 }
//             }
//         })
//     }

// Pour changer le format du prix
//     const amountForm = document.querySelector('.amount_form')
//
//     if(amountForm){
//         amountForm.value = changeAmount(amountForm.value)
//         console.log(amountForm.value)
//     }
//     function changeAmount(prix){
//         let number = prix.length
//         if(number > 3){
//             return new Intl.NumberFormat().format(prix)
//         }
//     }

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