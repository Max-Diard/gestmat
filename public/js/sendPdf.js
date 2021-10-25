window.addEventListener("DOMContentLoaded", (event) => {

// Page pour envoyer les pdfs
    const buttonSendPaper = document.querySelector('.link-send-paper');
    const buttonSendEmail = document.querySelector('.link-send-email');

    if (buttonSendPaper) {
        buttonSendPaper.addEventListener('click', () => {
            const dataPaper = buttonSendPaper.getAttribute('data-paper');
            let textPaper = '';
            if (dataPaper > 30) {
                textPaper = 'Attention, vous êtes sur le point de créer un PDF pour plus de ' + dataPaper + ' personnes, nous vous recommandons de sélectionner une date !'
            } else {
                textPaper = 'Êtes-vous sûr de vouloir créer un PDF pour ' + dataPaper + ' personnes ?'
            }
            Swal.fire({
                title: 'Créer le PDF à envoyer ?',
                text: textPaper,
                icon: 'question',
                showDenyButton: true,
                confirmButtonText: 'Oui',
                denyButtonText: `Non`,
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location = '/meet/send/paper';
                }
            })
        })
    }

    if (buttonSendEmail) {
        buttonSendEmail.addEventListener('click', () => {
            const dataEmail = buttonSendEmail.getAttribute('data-email');
            let textEmail = '';
            if (dataEmail > 30) {
                textEmail = 'Attention, vous êtes sur le point d\'envoyer ' + dataEmail + ' emails, nous vous recommandons de sélectionner une date !'
            } else {
                textEmail = 'Êtes-vous sûr de vouloir d\'envoyer ' + dataEmail + ' emails ?'
            }
            Swal.fire({
                title: 'Envoyer les emails ?',
                text: textEmail,
                icon: 'question',
                showDenyButton: true,
                confirmButtonText: 'Oui',
                denyButtonText: `Non`,
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location = '/meet/send/email';
                }
            })
        })
    }

})