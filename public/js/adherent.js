window.addEventListener("DOMContentLoaded", (event) => {

// Pour les détails de l'adhérent dans la liste
    const rowTable = document.querySelectorAll('.js-adherent');
    const womenRowTable = document.querySelectorAll('.women');
    const menRowTable = document.querySelectorAll('.men');

    if (url.hash.includes('men')) {
        const manInUrl = url.hash.indexOf('men')
        const resultMan = url.hash.slice(manInUrl)
        const startId = resultMan.indexOf('=')
        const idManInUrl = resultMan.slice(startId + 1)

        rowTable.forEach(i => {
            if(i.getAttribute('data-id') === idManInUrl){
                i.parentNode.classList.add('active')
            }
        })

        apiMeet(idManInUrl, document.querySelector('#table-men tbody tr.active'))
    }

    if (url.hash.includes('woman')) {

        const womanInUrl = url.hash.indexOf('woman')
        if (url.hash.indexOf('&') != -1) {
            const endWomanInUrl = url.hash.indexOf('&')
            const resultWoman = url.hash.slice(womanInUrl, endWomanInUrl)
            const startId = resultWoman.indexOf('=')
            const idWomanInUrl = resultWoman.slice(startId + 1)

            rowTable.forEach(i => {
                if(i.getAttribute('data-id') === idWomanInUrl){
                    i.parentNode.classList.add('active')
                }
            })

            apiMeet(idWomanInUrl, document.querySelector('#table-women tbody tr.active'))
        } else {
            const startId = url.hash.indexOf('=')
            const idWomanInUrl = url.hash.slice(startId + 1)

            rowTable.forEach(i => {
                if(i.getAttribute('data-id') === idWomanInUrl){
                    i.parentNode.classList.add('active')
                }
            })

            apiMeet(idWomanInUrl, document.querySelector('#table-women tbody tr.active'))
        }
    }

    if (rowTable) {
        [].forEach.call(rowTable, function (elem) {
            elem.addEventListener('click', function (ev) {
                let tabMan = elem.parentNode.classList.contains('men');
                if (tabMan === true) {
                    [].forEach.call(menRowTable, function(element) {
                        element.classList.remove('active')
                    });
                }
                let tabWoman = elem.parentNode.classList.contains('women');
                if (tabWoman === true) {
                    [].forEach.call(womenRowTable, function (element) {
                        element.classList.remove('active')
                    });
                }

                elem.parentNode.classList.add('active');

                apiMeet(elem.getAttribute('data-id'), elem.parentNode);
            })
        })
    }

})