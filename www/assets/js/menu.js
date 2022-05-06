/* Fonctions pour Menu Burger */

/* Affiche la modale du menu burger */
function burgerMenu() {
    
    let showHide;
    showHide = document.querySelector('.burger-menu');
    showHide.classList.toggle('burger-menu-open');    
}

/* Affiche la modale du menu utilisateur */
function userMenu() {

    let showHideUserMenu;
    showHideUserMenu = document.querySelector('.user-menu');
    showHideUserMenu.classList.toggle('user-menu-open');    
}

/** IMPORTANT --> DOMCONTENTLOADER **/
document.addEventListener('DOMContentLoaded', function() {
    
    /* on cherche les boutons à écouter que l'on met dans une variable */   
    let btnMenu = document.getElementById('menuClosed');
    let btnCloseMenu = document.getElementById('closeMenu');
    let userBtnMenu = document.getElementById('userMenuClosed');
    let userBtnCloseMenu = document.getElementById('userCloseMenu');    

    /* on met en écoute au click les boutons et on appel la fontion correspondante */
    btnMenu.addEventListener('click',burgerMenu);
    btnCloseMenu.addEventListener('click',burgerMenu);
    userBtnMenu.addEventListener('click',userMenu);
    userBtnCloseMenu.addEventListener('click',userMenu);    
});