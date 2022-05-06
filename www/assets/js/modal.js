/* Functions */
function getLegalNotice() {

    $.getJSON('index.php?page=modal',loadLegalNotice);
}


function loadLegalNotice(legalNotice) {
    
    $('.scroll').addClass('no-scroll');
    $('.legal-notice-off').addClass('legal-notice-on');
    $('#targetLegalNotice').empty();
    $('#targetLegalNotice').append('<h2>'+legalNotice['title_content']+'</h2>'+'</br>'+'<div class="dynamic">'+legalNotice['text_content']+'</div>');

}

function closeLegalNotice() {

    $('.scroll').removeClass('no-scroll');
    $('.legal-notice-off').removeClass('legal-notice-on');
}

/* Main code */
$(function() {

    $('#openModal').on('click',getLegalNotice);
    $('#closeModal').on('click',closeLegalNotice);
});