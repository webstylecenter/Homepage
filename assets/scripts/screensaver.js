/**
 * Created by petervandam on 18/11/2016.
 */
$(function() {
    setInterval(function() {
        setTimeout(function() {
            refreshPage();
        }, 5 * 1000);
    }, 3 * 60 * 1000);

    setInterval(function() {
        updateTime();
    }, 1000);

    setInterval(function() {
        updateWeather();
    }, 5 * 60 * 1000);

    setInterval(function() {
        nextNewsItem();
    }, 20 * 1000);

    refreshPage();
    updateWeather();

    setTimeout(function() {
        showItems();
    }, 3000);

});

/** global: currentNewsItem */
var currentNewsItem = 0;

function refreshPage() {
    /** global: Image */
    var tempImage = new Image();
    var time = $.now();
    tempImage.src = '/screensaver/images/' + time  + '.jpg';
    tempImage.onload = function() {
        $('.notActive').css('background-image', 'url("/screensaver/images/' + time + '.jpg")');
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

function showItems() {
    console.log('ShowItems');
    $('.activeNewsItem').slideToggle('slow');
    $('.newsSource').slideToggle('slow');
    $('.newsTitle').slideToggle('slow');
    $('.newsDescription').slideToggle('slow');
    $('.currentTime').slideToggle('slow');
}

function nextNewsItem() {
    hideNewsItem();
    setTimeout(function() {
        showNextNewsItem();
    }, 1000);
}

function hideNewsItem() {
    $('.newsSource').slideToggle('slow');
    $('.newsTitle').slideToggle('slow');
    $('.newsDescription').slideToggle('slow');
}

function showNextNewsItem() {
    /** global: currentNewsItem */
    currentNewsItem = currentNewsItem +1;
    if (currentNewsItem > 24) {
        currentNewsItem = 0;
    }

    /** global: newsItems */
    $('.newsTitle').html(newsItems[currentNewsItem][0]);
    $('.newsDescription').html(newsItems[currentNewsItem][1]);

    $('.newsSource').slideToggle('slow');
    $('.newsTitle').slideToggle('slow');
    $('.newsDescription').slideToggle('slow');

}
