$.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) {
        var min = parseInt( $('#min').val(), 10 );
        var max = parseInt( $('#max').val(), 10 );
        var age = parseFloat( data[3] ) || 0; // use data for the age column

        return (isNaN(min) && isNaN(max)) ||
            (isNaN(min) && age <= max) ||
            (min <= age && isNaN(max)) ||
            (min <= age && age <= max);
    }
);

$.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) {
        var sizeMin =  parseFloat($('#size-min').val(), 10);
        var sizeMax =  parseFloat($('#size-max').val(),10);
        var size =  data[5] || 0; // use data for the age column

        return (isNaN(sizeMin) && isNaN(sizeMax)) ||
            (isNaN(sizeMin) && size <= sizeMax) ||
            (sizeMin <= size && isNaN(sizeMax)) ||
            (sizeMin <= size && size <= sizeMax);
    }
);

$.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) {
        var weightMin =  parseFloat($('#weight-min').val(), 10);
        var weightMax =  parseFloat($('#weight-max').val(),10);
        var weight =  data[6] || 0; // use data for the age column

        return (isNaN(weightMin) && isNaN(weightMax)) ||
            (isNaN(weightMin) && weight <= weightMax) ||
            (weightMin <= weight && isNaN(weightMax)) ||
            (weightMin <= weight && weight <= weightMax);
    }
);

$(document).ready( function () {
    $('#table-women').DataTable({
        paging: false,
        info: false,
        "scrollX": false,
        "scrollY": "373px",
        "scrollCollapse": true,
        "language": {
            "emptyTable": "Pas encore de donées dans ce tableau"
        },
        initComplete: function () {
            //Status
            var columnStatus = this.api().column(3);

            var selectStatus = $('<select class="filter"><option value="">État</option></select>')
                .appendTo('#selectTriggerFilterWoman')
                .on('change', function () {
                    var val = $(this).val();
                    columnStatus.search(val).draw()
                });

            var officesStatuts = [];
            columnStatus.data().toArray().forEach(function (s) {
                s = s.split(',');
                s.forEach(function (d) {
                    if (!~officesStatuts.indexOf(d)) {
                        officesStatuts.push(d)
                        selectStatus.append('<option value="' + d + '">' + d + '</option>');
                    }
                })
            })

            //Agence
            var columnAgence = this.api().column(5);

            var selectAgence = $('<select class="filter"><option value="">Agence</option></select>')
                .appendTo('#selectTriggerFilterWoman')
                .on('change', function () {
                    var val = $(this).val();
                    columnAgence.search(val).draw()
                });

            var officesAgence = [];
            columnAgence.data().toArray().forEach(function (s) {
                s = s.split(',');
                s.forEach(function (d) {
                    if (!~officesAgence.indexOf(d)) {
                        officesAgence.push(d)
                        selectAgence.append('<option value="' + d + '">' + d + '</option>');
                    }
                })
            })

            //Fin de contrat
            var columnDate = this.api().column(4);

            var selectDate = $('<select class="filter"><option value="">Contrat</option></select>')
                .appendTo('#selectTriggerFilterWoman')
                .append('<option value="0">Terminé</option>')
                .append('<option value="1" selected>En cours</option>')
                .on('change', function () {
                    var val = $(this).val();
                    var date = new Date(Date.now())

                    let resultVal = []
                    columnDate.data().toArray().forEach(s => {
                        var d = new Date(s)
                        if (val == 0 && d < date) {
                            resultVal.push(s)
                        } else if (val != 0 && d > date) {
                            resultVal.push(s)

                        } else if (val == '') {
                            resultVal.push(s)
                        }
                    })
                    columnDate.search(resultVal.join('|'), true, false, true).draw()
                });

            // Remplir le tableu avec les dates 'en cours'
            let resultInProgress = [];
            var dateNow = new Date(Date.now())
            columnDate.data().toArray().forEach(s =>{
                var d = new Date(s)
                if (d > dateNow) {
                    resultInProgress.push(s)
                }
            })
            columnDate.search(resultInProgress.join('|'), true, false, true).draw();
        },
    });

// Tableau Adhérents all men
    $('#table-men').DataTable({
        paging: false,
        info: false,
        "scrollX":        false,
        "scrollY":        "373px",
        "scrollCollapse": true,
        "language": {
            "emptyTable": "Pas encore de donées dans ce tableau"
        },
        initComplete: function() {
            // Status
            var columnStatus = this.api().column(3);

            var selectStatus = $('<select class="filter"><option value="">État</option></select>')
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

            // Agence
            var columnAgence = this.api().column(5);

            var selectAgence = $('<select class="filter"><option value="">Agence</option></select>')
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

            // Fin de contrat
            var columnDate = this.api().column(4);

            var selectDate = $('<select class="filter"><option value="">Contrat</option></select>')
                .appendTo('#selectTriggerFilterMan')
                .append('<option value="0">Terminé</option>')
                .append('<option value="1" selected>En cours</option>')
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

            // Remplir le tableu avec les dates 'en cours'
            let resultInProgress = [];
            var dateNow = new Date(Date.now())
            columnDate.data().toArray().forEach(s =>{
                var d = new Date(s)
                if (d > dateNow) {
                    resultInProgress.push(s)
                }
            })
            columnDate.search(resultInProgress.join('|'), true, false, true).draw();

        },

    });

//Tableau pour la recherche
    $('#table-search thead td').each(function () {
        var title = $(this).text();
        $(this).html('<input class="search-filter-text" type="text" placeholder="'+title+'" />');
    });

    var tableSearch = $('#table-search').DataTable({
        paging: false,
        "language": {
            "emptyTable": "Pas encore de donées dans ce tableau"
        },
        initComplete: function () {
            // Apply the search
            this.api().columns().every(function (){
                var that = this;
                $('input', this.header()).on('keyup change clear', function (){
                    if (that.search() !== this.value){
                        that
                            .search(this.value)
                            .draw();
                    }
                });
            });
        }
    });

//Tableau pour la recherche inter-agence
    $('#table-search-inter-agence thead td').each(function () {
        var title = $(this).text();
        $(this).html('<input class="search-filter-text" type="text" placeholder="'+title+'" />');
    });

    var tableSearchInterAgence = $('#table-search-inter-agence').DataTable({
        paging: false,
        "language": {
            "emptyTable": "Pas encore de donées dans ce tableau"
        },
        "bAutoWidth": true,
        initComplete: function () {
            // Apply the search
            this.api().columns().every(function (){
                var that = this;
                $('input', this.header()).on('keyup change clear', function (){
                    if (that.search() !== this.value){
                        that
                            .search(this.value)
                            .draw();
                    }
                });
            });

            var columnGenre = this.api().column(0);

            var selectGenre = $('<select class="filter"><option value="">Genre</option></select>')
                .appendTo('#selectTriggerSearchInterAgence')
                .on('change', function () {
                    var val = $(this).val();
                    columnGenre.search(val).draw()
                });

            var officesGenre = [];

            columnGenre.data().toArray().forEach(function (s) {
                s = s.split(',');
                s.forEach(function (d) {
                    if (!~officesGenre.indexOf(d)) {
                        officesGenre.push(d)
                        selectGenre.append('<option value="' + d + '">' + d + '</option>');
                    }
                })
            })

            var columnTown = this.api().column(4);

            var selectTown = $('<select class="filter"><option value="">Ville</option></select>')
                .appendTo('#selectTriggerSearchInterAgence')
                .on('change', function () {
                    var val = $(this).val();
                    columnTown.search(val).draw()
                });

            var officesTown = [];
            columnTown.data().toArray().forEach(function (s) {
                s = s.split(',');
                s.forEach(function (d) {
                    if (!~officesTown.indexOf(d)) {
                        officesTown.push(d)
                        selectTown.append('<option value="' + d + '">' + d + '</option>');
                    }
                })
            })

            var columnStatusContract = this.api().column(8);

            var selectStatusContract = $('<select class="filter"><option value="">Statut contrat</option></select>')
                .appendTo('#selectTriggerSearchInterAgence')
                .on('change', function () {
                    var val = $(this).val();
                    columnStatusContract.search(val).draw()
                });

            var officesStatusContract = [];
            columnStatusContract.data().toArray().forEach(function (s) {
                s = s.split(',');
                s.forEach(function (d) {
                    if (!~officesStatusContract.indexOf(d)) {
                        officesStatusContract.push(d)
                        selectStatusContract.append('<option value="' + d + '">' + d + '</option>');
                    }
                })
            })

            var columnPosition = this.api().column(9);

            var selectPosition = $('<select class="filter"><option value="">Position</option></select>')
                .appendTo('#selectTriggerSearchInterAgence')
                .on('change', function () {
                    var val = $(this).val();
                    columnPosition.search(val).draw()
                });

            var officesPosition = [];
            columnPosition.data().toArray().forEach(function (s) {
                s = s.split(',');
                s.forEach(function (d) {
                    if (!~officesPosition.indexOf(d)) {
                        officesPosition.push(d)
                        selectPosition.append('<option value="' + d + '">' + d + '</option>');
                    }
                })
            })

            var columnAgence = this.api().column(10);

            var selectAgence = $('<select class="filter"><option value="">Agence</option></select>')
                .appendTo('#selectTriggerSearchInterAgence')
                .on('change', function () {
                    var val = $(this).val();
                    columnAgence.search(val).draw()
                });

            var officesAgence = [];
            columnAgence.data().toArray().forEach(function (s) {
                s = s.split(',');
                s.forEach(function (d) {
                    if (!~officesAgence.indexOf(d)) {
                        officesAgence.push(d)
                        selectAgence.append('<option value="' + d + '">' + d + '</option>');
                    }
                })
            })
        }
    });
    $('#min, #max').keyup( function() {
        tableSearchInterAgence.draw();
    } );
    $('#size-min, #size-max').keyup( function() {
        tableSearchInterAgence.draw();
    } );
    $('#weight-min, #weight-max').keyup( function() {
        tableSearchInterAgence.draw();
    } );

//Tableau pour les rencontres
    $('#table-meet').DataTable({
        paging: false,
        info: false,
        searching: true,
        "oLanguage": {
            "sSearch": "Votre recherche"
        },
        "language": {
            "emptyTable": "Pas encore de rencontre pendant cette période"
        }
    });

//Tableau pour la liste des agences
    $('#table-agence').DataTable({
        paging: false,
        searching: false,
        info: false,
        "language": {
            "emptyTable": "Pas encore de donées dans ce tableau"
        }
    });

//Tableau pour la liste des utilisateurs
    $('#table-user').DataTable({
        paging: false,
        searching: false,
        info: false,
        "language": {
            "emptyTable": "Pas encore de donées dans ce tableau"
        }
    });
} );