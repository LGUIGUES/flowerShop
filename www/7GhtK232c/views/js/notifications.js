/* Variables */
let showHide;
let totalNotifications;
let messageNotification;
let customerNotification;
let orderNotification;

/* Fonctions */
function listenNotification() {
    $.getJSON('index.php?page=totalNotifications',displayNotifications);
}

function displayNotifications(totalNotifications) {

    $('#totalNotification').empty();
    $('#totalNotification').append(`${totalNotifications}`);
}

function showModalNotifications() {

    showHide;
    showHide = document.querySelector('.modal-notifications');
    showHide.classList.toggle('modal-notifications-open');

    getMessageNotification();
}

function getMessageNotification() {

    $.getJSON('index.php?page=messageNotification',showMessageNotification);
}

function showMessageNotification(messageNotification) {

    if(messageNotification != '') {

        $('#targetNotifications').empty();
        for(const message of messageNotification) {            

            $('#targetNotifications').append(
                '<p class="text-user">De <a href="index.php?page=messaging&idThread='
                +message['id_thread_user']+'"><span class="user">'
                +(message['id_user'] != 0 ? message['firstname']+' '+message['lastname'] : message['email'])
                +'</span></a><span class="date-message">'
                +message['date_add']+'</span><br>'
                +message['shop_message']);
        }   
    }
    else {
        
        $('#targetNotifications').empty();
        $('#targetNotifications').append('<span class="empty-notifications">Pas de nouveaux messages</span>');
    }
}

function getCustomerNotification() {

    $.getJSON('index.php?page=customerNotification',showCustomerNotification);
}

function showCustomerNotification(customerNotification) {
    
    if(customerNotification != '') {

        $('#targetNotifications').empty();
        for(const customer of customerNotification) {

            $('#targetNotifications').append(
                '<p class="text-user"><a href="index.php?page=viewDetailsUser&id='
                +customer['id']+'"><span class="user">'
                +'#'+customer['id']+' '+customer['firstname']+' '+customer['lastname']+' '+customer['date_add']
                +'</span></a>');
        }   
    }
    else {

        $('#targetNotifications').empty();
        $('#targetNotifications').append('<span class="empty-notifications">Pas de nouveaux clients</span>');
    }   
}

function getOrderNotification() {

    $.getJSON('index.php?page=orderNotification',showOrderNotification);
}

function showOrderNotification(orderNotification) {
    
    if(orderNotification != '') {

        $('#targetNotifications').empty();
        for(const order of orderNotification) {

            $('#targetNotifications').append(
                '<p class="text-user"><a href="index.php?page=viewOrderDetails&idOrder='
                +order['id_order']+'"><span class="user">'
                +'#'+order['id_order']+' '+order['firstname']+' '+order['lastname']+' '+order['total_amount']+' €'
                +'</span></a>');
        }   
    }
    else {

        $('#targetNotifications').empty();
        $('#targetNotifications').append('<span class="empty-notifications">Pas de nouvelles commandes</span>');
    }   
}

/* Main code */
$(function() {

    $('#modalNotifications').on('click',showModalNotifications);
    $('#messageNotification').on('click',getMessageNotification);
    $('#customerNotification').on('click',getCustomerNotification);
    $('#orderNotification').on('click',getOrderNotification);

    //setInterval('listenNotification()', 3000);
    listenNotification();
});