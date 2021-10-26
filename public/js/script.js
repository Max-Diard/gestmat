// Pour créer le bouton de rencontre
let loadingMen = false
let loadingWomen = false

let meetWaitingWomen = '';
let meetWaitingMen = '';

//Pour recupérer ler informations de la personne sélectionner
let infoWoman = '';
let infoMan = '';
let alreadyMeet = false;

//Pour mettre les informations dans l'url
const url = new URL(window.location)
let myParams = url.searchParams



const lastnameWoman = document.querySelector('.thead-card-woman-lastname');
const firstnameWoman = document.querySelector('.thead-card-woman-firstname');
const yearWoman = document.querySelector('.thead-card-woman-years');
const agenceWoman = document.querySelector('.thead-card-woman-agence');

const lastnameMan = document.querySelector('.thead-card-man-lastname');
const firstnameMan = document.querySelector('.thead-card-man-firstname');
const yearMan = document.querySelector('.thead-card-man-years');
const agenceMan = document.querySelector('.thead-card-man-agence');
