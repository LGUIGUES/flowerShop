/* Function */
function moveLeftSlide() {    
   
    let moveRight;
    moveRight = document.querySelector('.slide-1');
    moveRight.classList.remove('slide-translate-right');   
    moveRight.classList.add('slide-translate-left');
    
    moveRight = document.querySelector('.slide-2');
    moveRight.classList.remove('slide-translate-right');   
    moveRight.classList.add('slide-translate-left');
}

function moveRightSlide() {
    
    let moveLeft;    
    moveLeft = document.querySelector('.slide-1');
    moveLeft.classList.remove('slide-translate-left');    
    moveLeft.classList.add('slide-translate-right'); 
    
    moveLeft = document.querySelector('.slide-2');
    moveLeft.classList.remove('slide-translate-left');    
    moveLeft.classList.add('slide-translate-right');
}

/** IMPORTANT --> DOMCONTENTLOADER **/
document.addEventListener('DOMContentLoaded', function() {
    
    /* on cherche les boutons à écouter que l'on met dans une variable */  
    let arrowBack = document.getElementById('arrowBack');
    let arrowForward = document.getElementById('arrowForward');   

    /* on met en écoute au click les boutons et on appel la fonction correspondante */
        
    if(arrowBack) {

        arrowBack.addEventListener('click',moveLeftSlide);
    }

    if(arrowForward) {

        arrowForward.addEventListener('click',moveRightSlide); 
    }      
});