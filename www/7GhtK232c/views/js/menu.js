// Affichage du menu pour mobile //
function showMenu() {
    
    let show;
    show = document.querySelector('.burger-menu');
    show.classList.toggle('burger-menu-open');    
}

// Main script // 
document.addEventListener('DOMContentLoaded', function() {
    let btnMenu = document.getElementById('menuClosed');    

    btnMenu.addEventListener('click',showMenu);
});