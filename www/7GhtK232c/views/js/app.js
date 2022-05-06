/* Fonction interrupteur afficher/cacher mot de passe */
function viewHidePassword() {

    let inputPass = document.getElementById('password');   
    
    if(inputPass.getAttribute('type') === 'password')
    {
        showPass();

    } else {

        hidePass();
    } 
}

/* Fonction pour afficher le mot de passe */
function showPass() {

    let iconHide = document.getElementById('viewPassword');
    iconHide.classList.add('hide-pass');    

    let show = document.getElementById('password');
    show.setAttribute('type','text');
}

/* Fonction pour cacher le mot de passe */
function hidePass() {

    let iconShow = document.getElementById('viewPassword');
    iconShow.classList.remove('hide-pass');   
    
    let hide = document.getElementById('password');
    hide.setAttribute('type','password');
}

/* Fonction interrupteur afficher/cacher nouveau mot de passe */
function viewHideNewPassword() {

    let inputPass = document.getElementById('newPassword');
    
    if(inputPass.getAttribute('type') === 'password')
    {
        showNewPass();
    } else {
        hideNewPass();
    }
}

/* Fonction pour afficher le nouveau mot de passe */
function showNewPass() {

    let iconHide = document.getElementById('viewNewPassword');
    iconHide.classList.add('hide-pass');    

    let show = document.getElementById('newPassword');
    show.setAttribute('type','text');
}

/* Fonction pour cacher le nouveau mot de passe */
function hideNewPass() {

    let iconShow = document.getElementById('viewNewPassword');
    iconShow.classList.remove('hide-pass');    
    
    let hide = document.getElementById('newPassword');
    hide.setAttribute('type','password');
}

// Fonction pour afficher/cacher le remplacement de l'image dans la fiche produit //
function showReplaceImage() {
    
    let showInputFileImage;
    showInputFileImage = document.querySelector('.replace-image');
    showInputFileImage.classList.toggle('replace-image-open');
}

// Main script // 
document.addEventListener('DOMContentLoaded', function() {
    
    /* on cherche les boutons à écouter que l'on met dans une variable */    
    let viewHidePass = document.getElementById('viewPassword');    
    let viewNewHidePass = document.getElementById('viewNewPassword'); 
    let btnReplaceImage = document.getElementById('replaceImage');

    /* Si l'élément est bien présent, on le met à l'écoute */
    if(viewHidePass) {

        viewHidePass.addEventListener('click',viewHidePassword);
    }

    if(viewNewHidePass) {

        viewNewHidePass.addEventListener('click',viewHideNewPassword);
    }
    
    if(btnReplaceImage) {

        btnReplaceImage.addEventListener('change',showReplaceImage);
    }
});