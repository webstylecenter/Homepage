/**
 * Created by petervandam on 18/11/2016.
 */
$(function() {
    setInterval(function() {
        setTimeout(function() {
            refreshPage();
        }, 5 * 1000);
    }, 60 * 1000);

    setInterval(function() {
        updateTime();
    }, 1000);

    setInterval(function() {
        updateWeather();
    }, 5 * 60 * 1000);

    refreshPage();
    updateWeather();
});

function refreshPage() {
    /** global: Image */
    var tempImage = new Image();
    var time = $.now();
    tempImage.src = '/screensaver/images/' + time  + '.jpg';
    tempImage.onload = function() {
        var randomNumber = Math.floor((Math.random() * 24) + 1);
        $('.notActive').css('background-image', 'url("/screensaver/images/' + time + '.jpg")');

        /** global: newsItems */
        $('.notActive .newsTitle').html(newsItems[randomNumber][0]);
        $('.notActive .newsDescription').html(newsItems[randomNumber][1]);
        switchBackgrounds();
    }
}

function switchBackgrounds() {
    $('.notActive').fadeIn(3000);
    $('.active').fadeOut(3000);
    $('.active, .notActive').toggleClass('active notActive');
}

function updateTime() {
    var dt = new Date();
    var hours = dt.getHours();
    var minutes = dt.getMinutes();

    if (hours < 10) { hours = '0' + hours; }
    if (minutes < 10) { minutes = '0' + minutes; }

    $('.currentTime').html(hours + ':' + minutes);
}

function updateWeather() {
    $('.screensaverWeatherContent').load('/weather/current/')
}
