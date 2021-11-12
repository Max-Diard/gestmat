
const inputImage = document.querySelector('#agence_link_picture')

if(inputImage){
    console.log(inputImage)
    inputImage.addEventListener('change', function (e){
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
}