window.addEventListener("DOMContentLoaded", (event) => {
    // Page pour la recherche inter-agence
    const filterButton = document.querySelector('.filter-button')
    const modalFilter = document.querySelector('.modal-filter')

    if(filterButton){
        filterButton.addEventListener('click', function(){
            if (modalFilter.getAttributeNames().includes('hidden')){
                modalFilter.removeAttribute('hidden')
            } else {
                modalFilter.setAttribute('hidden', 'true')
            }
        })
    }

    const createMeetInterAgence = document.querySelectorAll('.create-meet-search-inter-agence');

    if (createMeetInterAgence) {
        [].forEach.call(createMeetInterAgence, function (elem) {
            const idAdherent = elem.getAttribute('data-adherent-id');
            const genreAdherent = elem.getAttribute('data-adherent-genre');
            elem.addEventListener('click', () => {
                let hash = ''
                if (genreAdherent === 'Féminin') {
                    hash = 'woman=' + idAdherent

                } else {
                    hash = 'men=' + idAdherent
                }
                window.location = '/adherent#?' + hash
            })
        })
    }


})