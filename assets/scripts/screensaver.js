/**
 * Created by petervandam on 18/11/2016.
 */
$(function() {
    setInterval(function() {
        refreshPage();
    }, 60 * 1000);
});

function refreshPage() {
    $('.notActive').css('background-image', 'url("https://source.unsplash.com/category/nature/3840x2160?t=' + $.now() + '")');
    setTimeout(function() {
        switchBackgrounds();
    }, 4000);
    requestNewFeedItems();
}

function switchBackgrounds() {
    $('.notActive').fadeIn(3000).delay(3000);
    $('.active').fadeOut(3000);
    $(".active, .notActive").toggleClass("active notActive");
}

