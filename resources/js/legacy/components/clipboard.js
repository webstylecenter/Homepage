/** global: textToCopy */
var textToCopy = '';

$(function () {
    $(document).on('click', '.js-copy-to-clipboard, .dropCopy', function() {
        /** global: textToCopy */
        textToCopy = $(this).attr('data-clipboard-text');
        document.execCommand('copy');
    });
});

document.addEventListener('copy', function(e){
    /** global: textToCopy */
    e.clipboardData.setData('text/plain', textToCopy);
    e.preventDefault();
});