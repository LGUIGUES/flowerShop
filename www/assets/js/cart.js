/* Fonction pour le panier */
function getTotalCartQty() {

    $.getJSON('index.php?page=cart',updateViewCart);
}

function updateViewCart(totalQtyCartProduct) {

    $('#targetCartQty').empty();
    $('#targetCartQty').append(totalQtyCartProduct);
}

/* Main Code */
$(function() {
    
    $.getJSON('index.php?page=cart',updateViewCart);
});