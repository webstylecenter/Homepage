$(function () {
    if ($('.screensaver--newsticker-title').html()) {
        setInterval(function () {
            setTimeout(refreshPage, 5 * 1000);
        }, 3 * 60 * 1000);

        setInterval(updateTime, 1000);
        setInterval(updateWeather, 5 * 60 * 1000);
        setInterval(nextNewsItem, 20 * 1000);
        setTimeout(showItems, 3000);

        refreshPage();
        updateWeather();
    }
});

var currentNewsItemKey = 0;

function refreshPage() {
    /** global: Image */
    var newImage = new Image();
    var time = $.now();
    newImage.src = '/screensaver/images/' + time + '.jpg';
    newImage.onload = function () {
        $('.notActive').css('background-image', 'url("/screensaver/images/' + time + '.jpg")').fadeIn(3000);
        $('.active').fadeOut(3000);
        $('.notActive, .active').toggleClass('active notActive');
    };
}

function updateTime() {
    var now = new Date();
    var hours = now.getHours();
    var minutes = now.getMinutes();

    if (hours < 10) {
        hours = '0' + hours;
    }
    if (minutes < 10) {
        minutes = '0' + minutes;
    }

    $('.screensaver--time').html(hours + ':' + minutes);
}

function updateWeather() {
    $('.screensaver .weather').load('/weather/icon/');
}

function showItems() {
    $(
        '.screensaver--newsticker,'
        + '.screensaver--time,'
        + '.screensaver--newsticker-source,'
        + '.screensaver--newsticker-title'
    ).fadeToggle('slow');
}

function nextNewsItem() {
    $('.screensaver--newsticker-source, .screensaver--newsticker-title').fadeToggle('slow');
    setTimeout(showNextNewsItem, 1000);
}

function showNextNewsItem() {
    currentNewsItemKey++;
    if (currentNewsItemKey > 29) {
        currentNewsItemKey = 0;
    }

    /** global: newsItems */
    $('.screensaver--newsticker-source').html(newsItems[currentNewsItemKey][0])
        .attr('class', 'screensaver--newsticker-source')
        .fadeToggle('slow')
        .css('backgroundColor', newsItems[currentNewsItemKey][3]);

    $('.screensaver--newsticker-title').html(newsItems[currentNewsItemKey][1] + '<span>' + newsItems[currentNewsItemKey][2] + '</span>').fadeToggle('slow');
}
