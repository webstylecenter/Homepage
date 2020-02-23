<header class="header--bar js-toggle-fullscreen">
    <div class="header--bar-site  wow slideInLeft">
        <div class="title">
            <a href="/" class="title--back noselect js-return"><span class="fa fa-chevron-circle-left"></span></a>
            <a data-balloon="Click to refresh feedlist" data-balloon-pos="down" href="/" class="js-reload-page"><b>FEEDNEWS</b><em>.me</em></a>
        </div>
    </div>
    <div class="header--bar-actions wow slideInRight">

        @if($device['is_mobile'] === false)
        <div data-balloon="Click to see weather forecast" data-balloon-pos="down" class="js-update-weather-icon header--bar-weather-icon hide-if-tablet hide-if-mobile">{% include 'weather/icon.html.twig' %}</div>
        <div class="js-show-weather-radar header--bar-weather-radar"><img class="js-weather-radar" src="https://api.buienradar.nl/image/1.0/RadarMapNL?w=500&h=512" />
            <div class="header--bar-weather-radar-overview">
                @include('weather/detail')
            </div>
        </div>

        @if(isset($googleCalendar))
        <div data-balloon="Click to see your calendar" data-balloon-pos="down" class="js-show-calendar header--bar-calendar-icon hide-if-tablet hide-if-mobile"><span class="fa fa-calendar"></span></div>
        <div class="header--bar-calendar-view">
            <iframe src="{{ settings.google_calendar }}" style="border-width:0" width="800" height="600" frameborder="0" scrolling="no"></iframe>
        </div>
        @endif

        @endif

        <span data-balloon="Send current page to popup" data-balloon-pos="down" class="js-send-to-pip hide-if-mobile show-if-desktop"><span class="fa fa-window-restore"></span></span>
        <span data-balloon="Open new window" data-balloon-pos="down" class="js-open-new-window hide-if-mobile"><span class="fa fa-external-link-alt fa-x4"></span></span>
        <span data-balloon="Copy link to clipboard" data-balloon-pos="down" class="js-copy-to-clipboard hide-if-mobile" data-clipboard-action="copy" data-clipboard-text="#"><span class="fa fa-share-square fa-x4"></span></span>

        <span data-balloon="Open your todo list" data-balloon-pos="down" data-url="/checklist/" class="js-open-url hide-if-mobile"><span class="fa fa-check fa-x4"></span></span>
        <span data-balloon="Add new item to your feed" data-balloon-pos="down" data-modal-target=".js-form-feed" class="js-modal-trigger hide-if-mobile"><span class="fa fa-file fa-x4"></span></span>
        <span data-balloon="Open settings" data-balloon-pos="down" data-url="/settings/" class="js-open-url hide-if-mobile"><span class="fa fa-cog fa-x4"></span></span>

        <span data-balloon="Refresh for latest items" data-balloon-pos="down" class="js-refresh-feed-items show-if-mobile hide-if-desktop hide-if-tablet"><span class="fa fa-sync fa-x4"></span></span>

        <div data-balloon="Click to open menu" data-balloon-pos="down" class="action-user js-open-profile-menu fluent-dark">
            {{ $username ?? '' }}
        </div>
    </div>
</header>
