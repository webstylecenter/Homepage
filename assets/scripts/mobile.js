/**
 * Created by petervandam on 07/11/2016.
 */
$( document ).ready(function() {
    $('.listItem').click(function() {
        $('.list').hide();
        $('.title').hide();
        $('.contentContainer').show();

        $('.backButton').css('display', 'inline-block');
        $('.pageLinkToUrl').show();
    });

    $('.backButton').click(function() {
        $('.list').show();
        $('.title').show();
        $('.contentContainer').hide();

        $('.backButton').hide();
        $('.pageLinkToUrl').hide();
    });

    $('.mobilePin').click(function() {
        var pin = $(document).find('.selected').find('.pin');
        $.ajax("/pin/" +$(pin).data('pin-id'))
            .done(function(response) {
                if (response == '1') {
                    $(pin).parent().toggleClass('pinned');
                    alert('Item (un-)pinned!');
                }
            });
    });

    function startTrigger(e,data) {
        var $elem = $(this);
        $elem.data('mouseheld_timeout', setTimeout(function() {
            $elem.trigger('mouseheld');
        }, e.data));
    }

    function stopTrigger() {
        var $elem = $(this);
        clearTimeout($elem.data('mouseheld_timeout'));
    }


    var mouseheld = $.event.special.mouseheld = {
        setup: function(data) {
            var $this = $(this);
            $this.bind('mousedown', +data || mouseheld.time, startTrigger);
            $this.bind('mouseleave mouseup', stopTrigger);
        },
        teardown: function() {
            var $this = $(this);
            $this.unbind('mousedown', startTrigger);
            $this.unbind('mouseleave mouseup', stopTrigger);
        },
        time: 750 // default to 750ms
    };

    $(".listItem").bind('mouseheld', function(e) {
        console.log('Held', e);
        var pin = $(this).find('.pin');
        $.ajax("/pin/" +$(pin).data('pin-id'))
            .done(function(response) {
                if (response == '1') {
                    $(pin).parent().toggleClass('pinned');
                }
            });
    })
});
