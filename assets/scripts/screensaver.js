/**
 * Created by petervandam on 18/11/2016.
 */
$(function() {
    setInterval(function() {
        refreshPage();
    }, 60 * 1000);
});

function refreshPage() {
    $('.notActive').attr('src', 'https://source.unsplash.com/category/nature/3840x2160?=' + $.now());
    setTimeout('switchBackgrounds()', 4000);
    requestNewFeedItems();
}

function switchBackgrounds() {
    $('.notActive').fadeIn(4000).delay(3000);
    $('.active').fadeOut(4000);
    $(".active, .notActive").toggleClass("active notActive");
}

