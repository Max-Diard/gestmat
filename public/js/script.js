const routeUrl = document.querySelector('header').getAttribute('data-url');

// Pour créer le bouton de rencontre
let loadingMen = false
let loadingWomen = false

// Récupérer l'id de l'adhérent
let meetWaitingWomen = '';
let meetWaitingMen = '';

//Pour recupérer ler informations de la personne sélectionner
let infoWoman = '';
let infoMan = '';
let alreadyMeet = false;

//Pour mettre les informations dans l'url
const url = new URL(window.location)
let myParams = url.searchParams

// Récupérer les agences de l'utilisateur
let nameAgence = document.querySelectorAll('.name-agence-datatable');
let arrayNameAgence = [];
[].forEach.call(nameAgence, function(elem){
    arrayNameAgence.push(elem.getAttribute('name-agence'));
})

const lastnameWoman = document.querySelector('.thead-card-woman-lastname');
const firstnameWoman = document.querySelector('.thead-card-woman-firstname');
const yearWoman = document.querySelector('.thead-card-woman-years');
const agenceWoman = document.querySelector('.thead-card-woman-agence');

const lastnameMan = document.querySelector('.thead-card-man-lastname');
const firstnameMan = document.querySelector('.thead-card-man-firstname');
const yearMan = document.querySelector('.thead-card-man-years');
const agenceMan = document.querySelector('.thead-card-man-agence');
