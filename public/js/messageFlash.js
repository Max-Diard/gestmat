
window.addEventListener("DOMContentLoaded", (event) => {
    //Pop-up pour les messages flashs
    const alertNewAdherent = document.querySelector('.alert-new-adherent');
    const alertChangeAdherent = document.querySelector('.alert-change-adherent');
    const alertAddOption = document.querySelector('.alert-add-option');
    const alertRemoveOption = document.querySelector('.alert-remove-option');
    const alertErrorRemoveOption = document.querySelector('.alert-error-remove-option');
    const alertErrorNoAgence = document.querySelector('.alert-error-no-agence')

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
            text: "L'option ne peux pas être supprimée car utilisée pour un adhérent !",
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

});