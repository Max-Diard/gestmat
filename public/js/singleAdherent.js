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
})