$(document).ready( function () {
    $('.table-women').DataTable({
        paging: false,
        info: false,
        initComplete: function() {
            var columnStatus = this.api().column(3);

            var selectStatus = $('<select class="filter"><option value=""></option></select>')
                .appendTo('#selectTriggerFilterWoman')
                .on('change', function() {
                    var val = $(this).val();
                    columnStatus.search(val).draw()
                });

            var officesStatuts = [];
            columnStatus.data().toArray().forEach(function(s) {
                s = s.split(',');
                s.forEach(function(d) {
                    if (!~officesStatuts.indexOf(d)) {
                        officesStatuts.push(d)
                        selectStatus.append('<option value="' + d + '">' + d + '</option>');
                    }
                })
            })
            var columnAgence = this.api().column(5);

            var selectAgence = $('<select class="filter"><option value=""></option></select>')
                .appendTo('#selectTriggerFilterWoman')
                .on('change', function() {
                    var val = $(this).val();
                    columnAgence.search(val).draw()
                });

            var officesAgence = [];
            columnAgence.data().toArray().forEach(function(s) {
                s = s.split(',');
                s.forEach(function(d) {
                    if (!~officesAgence.indexOf(d)) {
                        officesAgence.push(d)
                        selectAgence.append('<option value="' + d + '">' + d + '</option>');
                    }
                })
            })
            var columnDate = this.api().column(4);

            var selectDate = $('<select class="filter"><option value=""></option></select>')
                .appendTo('#selectTriggerFilterWoman')
                .append('<option value="0">Terminé</option>')
                .append('<option value="1">En cours</option>')
                .on('change', function() {
                    var val = $(this).val();
                    var date = new Date(Date.now())

                    let resultVal = []
                    columnDate.data().toArray().forEach(s =>{
                        var d = new Date(s)
                        if (val == 0 && d < date){
                            resultVal.push(s)
                        } else if (val != 0 && d > date) {
                            resultVal.push(s)

                        } else if (val == ''){
                            resultVal.push(s)
                        }
                    })
                    columnDate.search(resultVal.join('|'), true, false, true).draw()
                });
        }
    });


// Tableau Adhérents all men
    $('.table-men').DataTable({
        paging: false,
        info: false,
        initComplete: function() {
            var columnStatus = this.api().column(3);

            var selectStatus = $('<select class="filter"><option value=""></option></select>')
                .appendTo('#selectTriggerFilterMan')
                .on('change', function() {
                    var val = $(this).val();
                    console.log(val)
                    columnStatus.search(val).draw()
                });

            var officesStatuts = [];
            columnStatus.data().toArray().forEach(function(s) {
                s = s.split(',');
                s.forEach(function(d) {
                    if (!~officesStatuts.indexOf(d)) {
                        officesStatuts.push(d)
                        selectStatus.append('<option value="' + d + '">' + d + '</option>');
                    }
                })
            })
            var columnAgence = this.api().column(5);

            var selectAgence = $('<select class="filter"><option value=""></option></select>')
                .appendTo('#selectTriggerFilterMan')
                .on('change', function() {
                    var val = $(this).val();
                    console.log(val)
                    columnAgence.search(val).draw()
                });

            var officesAgence = [];
            columnAgence.data().toArray().forEach(function(s) {
                s = s.split(',');
                s.forEach(function(d) {
                    if (!~officesAgence.indexOf(d)) {
                        officesAgence.push(d)
                        selectAgence.append('<option value="' + d + '">' + d + '</option>');
                    }
                })
            })
            var columnDate = this.api().column(4);

            var selectDate = $('<select class="filter"><option value=""></option></select>')
                .appendTo('#selectTriggerFilterMan')
                .append('<option value="0">Terminé</option>')
                .append('<option value="1">En cours</option>')
                .on('change', function() {
                    var val = $(this).val();
                    var date = new Date(Date.now())

                    let resultVal = []
                    columnDate.data().toArray().forEach(s =>{
                        var d = new Date(s)
                        if (val == 0 && d < date){
                            resultVal.push(s)
                        } else if (val != 0 && d > date) {
                            resultVal.push(s)

                        } else if (val == ''){
                            resultVal.push(s)
                        }
                    })
                    columnDate.search(resultVal.join('|'), true, false, true).draw()
                });
        }
    });

// Tableau card Adhérents women
// $(document).ready( function () {
//     $('.table-card-woman').DataTable({
//         paging: false,
//         searching: false,
//         info: false
//     });
// } );

// // Tableau card Adhérents men
// $(document).ready( function () {
//     $('.table-card-man').DataTable({
//         paging: false,
//         searching: false,
//         info: false
//     });
// } );

// Tableau card meet Adhérents women
//     $('.table-card-woman-meet').DataTable({
//         paging: false,
//         searching: false,
//         info: false,
//         filter: false
//     });
//
// // Tableau card meet Adhérents men
//     $('.table-card-man-meet').DataTable({
//         paging: false,
//         searching: false,
//         info: false,
//         filter: false
//     });

//Tableau pour la recherche
    $('.table-search').DataTable({
        paging: false,
        info: false
    });

//Tableau pour les rencontres
    $('.table-meet').DataTable({
        paging: false,
        info: false
    });

//Tableau pour la liste des agences
    $('.table-agence').DataTable({
        paging: false,
        searching: false,
        info: false
    });

//Tableau pour la liste des utilisateurs
    $('.table-user').DataTable({
        paging: false,
        searching: false,
        info: false
    });
} );