{% set hadNewItems = false %}
{% set showedEarlierTodayMessage = false %}
{% set hadYesterdayBefore = false %}
{% set hadHiddenPinnedItem = false %}

{% if not userFeedItems and (searchQueryString is not defined or not searchQueryString) %}
<div class="noFeedItems">
    {% include 'home/welcome-text.html.twig' %}
</div>
{% endif %}

{% for item in userFeedItems %}
{% set hidePinnedItem = false %}
{% if 'now'|date_modify('- 14 days')|date('Y-m-d') > item.updatedAt|date('Y-m-d') or loop.index > 5 %}
{% set hidePinnedItem = true %}

{% if hadHiddenPinnedItem == false %}
<div class="hidden-feed-items js-show-hidden-pinned-items">
    Show old pinned items
</div>
{% endif %}

{% set hadHiddenPinnedItem = true %}
{% endif %}

{% if item.userFeed.icon is not defined %}
{% set feedIcon = '' %}
{% else %}
{% set feedIcon = item.userFeed.icon %}
{% endif %}

{% if item.feedItem.feed.name is not defined %}
{% set feedName = '' %}
{% else %}
{% set feedName = item.feedItem.feed.name %}
{% endif %}

{% if item.userFeed.color is not defined %}
{% set feedColor = '#f0d714' %}
{% else %}
{% set feedColor = item.userFeed.color %}
{% endif %}

{% if not item.viewed %}
{% set hadNewItems = true %}
{% endif %}

{% if item.viewed and hadNewItems and not showedEarlierTodayMessage and loop.index > 1 %}
<div class="feed-list--separator">
    Earlier today
</div>
{% set showedEarlierTodayMessage = true %}
{% elseif item.viewed and not hadYesterdayBefore and item.createdAt|date('Y-m-d') == 'yesterday'|date('Y-m-d') and loop.index > 1 %}
<div class="feed-list--separator">
    Yesterday
</div>
{% set hadYesterdayBefore = true %}
{% endif %}

<div class="feed-list-item js-action-feed-list-click js-action-feed-list-swipe fluent {% if not item.viewed %}feed-list-item--state-new{% endif %} {% if item.pinned %}feed-list-item--state-pinned{% endif %} {% if feedIcon %}hasIcon{% endif %} {% if item.pinned and hidePinnedItem %}hidden-pinned-item{% endif %}"
     data-url="{{ item.feedItem.url }}" data-share-id="{{ feedName|slugify }}/{{ item.id }}/" data-id="{{ item.id }}" style="border-left-color:{{ feedColor }};">
    <div data-balloon="Pin item" data-balloon-pos="left" class="pin" data-pin-id="{{ item.id }}"><span class="fa fa-thumbtack"></span></div>
    <div data-balloon="Open in popup" data-balloon-pos="left" class="pip hide-if-mobile"><span class="fa fa-window-restore"></span></div>
    {% if feedIcon %}
    <div class="feed-icon" style="background-color:{{ feedColor }}">
        <span class="fa fa-{{ feedIcon }}"></span>
    </div>
    {% endif %}
    <p class="title ">{{ item.feedItem.title|striptags }}</p>
    <p class="description">
        {% if item.feedItem.description %}
        {{ item.feedItem.description|truncate(120, true) }}
        {% else %}
        {% if item.userFeed is not null %}
        {{ item.userFeed.feed.name }}
        {% endif %}
        {% endif %}
    </p>
</div>
{% endfor %}

{% if userFeedItems and nextPageNumber is defined %}
<a href="/feed/page/{{ nextPageNumber }}" class="feed-list-item jscroll-next">Next page</a>
{% endif %}
