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
