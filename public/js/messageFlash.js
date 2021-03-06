
window.addEventListener("DOMContentLoaded", (event) => {
    //Pop-up pour les messages flashs
    const alertNewAdherent = document.querySelector('.alert-new-adherent');
    const alertChangeAdherent = document.querySelector('.alert-change-adherent');
    const alertAddOption = document.querySelector('.alert-add-option');
    const alertRemoveOption = document.querySelector('.alert-remove-option');
    const alertErrorRemoveOption = document.querySelector('.alert-error-remove-option');
    const alertErrorNoAgence = document.querySelector('.alert-error-no-agence')
    const meetDeleteNoAgence = document.querySelectorAll('.meet-delete-no-agence')
    const meetMoreNoAgence = document.querySelectorAll('.meet-more-no-agence')
    const successSendMail = document.querySelector('.alert-success-send-mail')
    const alertForm = document.querySelector('.alert-form-error')

    if(alertNewAdherent){
        Swal.fire({
            title: 'Adhérent crée(e)',
            text: "Félicitations, vous avez créer un nouvel adhérent !",
            icon: 'success'
        })
    }

    if(alertChangeAdherent){
        Swal.fire({
            title: 'Adhérent modifié(e)',
            text: "L'adhérent(e) a bien été modifié(e)",
            icon: 'success'
        })
    }

    if(alertAddOption){
        Swal.fire({
            title: 'Option enregistrée',
            text: "La nouvelle option à bien était enregistrée",
            icon: 'success'
        })
    }

    if(alertRemoveOption){
        Swal.fire({
            title: 'Option supprimée',
            text: "L'option à bien était supprimée",
            icon: 'success'
        })
    }

    if(alertErrorRemoveOption){
        Swal.fire({
            title: 'Opération impossible !',
            text: "L'option ne peut pas être supprimée car utilisée pour un adhérent !",
            icon: 'error'
        })
    }

    if(alertErrorNoAgence){
        Swal.fire({
            title: 'Pas d\'agence attribuée !',
            text: 'Vous n\'avez pas encore d\'agence associée à votre compte, merci de contacter l\'administrateur !',
            icon: 'error'
        })
    }

    if(meetDeleteNoAgence){
        [].forEach.call(meetDeleteNoAgence, function(elem){
            elem.addEventListener('click', () => {
                Swal.fire({
                    title: 'Donnée non disponible !',
                    text: "Vous n'avez pas accès à cette donnée !",
                    icon: 'info'
                })
            })
        })
    }

    if(meetMoreNoAgence){
        [].forEach.call(meetMoreNoAgence, function(elem){
            elem.addEventListener('click', () => {
                Swal.fire({
                    title: 'Donnée non disponible !',
                    text: "Vous n'avez pas accès à cette donnée !",
                    icon: 'info'
                })
            })
        })
    }

    if(successSendMail){
        Swal.fire({
            title: 'Email envoyé !',
            text: "Les emails ont bien été envoyé !",
            icon: 'success'
        })
    }

    if(alertForm){
        Swal.fire({
            title: 'Formulaire non enregistré !',
            text: 'Une erreur s\'est produit dans le formulaire, merci de vérifier vos informations.',
            icon: 'info'
        })
    }
});