{!! $darkTheme = '' !!}
@if($device['is_mobile'] && (date('H') > 21 || date('H') < 8))
{!! $darkTheme = 'darkTheme' !!}
@endif

<div class="tabs {{ darkTheme }}">
    <div class="tab tab--recent">
        <aside data-is-mobile="{{ $device['is_mobile'] }}" data-hideXframe="{{ $user['hideXframeNotice'] ?? false }}" class="feed-list feed-list--type-sidebar {{ $darkTheme }}">
            @include()
            {% include 'home/components/newsfeed.html.twig' with {'userFeedItems': userFeedItems} %}
        </aside>
    </div>
    <div class="tab tab--history"></div>
    <div class="tab tab--search">
        <input type="text" name="query" class="search-query js-search-feed" placeholder="Search feed items" />
        <div class="js-search-list"></div>
    </div>
</div>
<div class="tabBar {{ $darkTheme }}">
    <button class="active" data-open-tab="recent"><span class="fa fa-clock fa-x4"></span> Recent items</button>
    <button data-open-tab="history"><span class="fa fa-history fa-x4"></span> Last opened</button>
    <button data-open-tab="search"><span class="fa fa-search fa-x4"></span> Search</button>
</div>

<div class="content iFramesContainer hide-if-mobile" style="overflow:auto; -webkit-overflow-scrolling: touch;">
    <iframe
        id="welcomeFrame"
        class="content-frame"
        src="{% if not userFeedItems %}/settings/{% else %}/welcome{% endif %}"
        sandbox="allow-scripts allow-same-origin allow-forms allow-popups allow-pointer-lock allow-modals"
        allowfullscreen="allowfullscreen"
        mozallowfullscreen="mozallowfullscreen"
        msallowfullscreen="msallowfullscreen"
        oallowfullscreen="oallowfullscreen"
        webkitallowfullscreen="webkitallowfullscreen"
        style="width: 100%; height:100%;"
    ></iframe>
</div>

<div class="urlbar hide">
    <a href="/welcome/" target="_blank">/welcome/</a>
</div>

<div class="content-close-pip js-close-pip fa fa-window-close"></div>
<div class="content-maximize-pip js-send-from-pip fa fa-window-restore"></div>
