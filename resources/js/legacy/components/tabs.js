$(function () {
  $('.tabBar button').on('click', function() {
    switchToTab(this);
  })
});

function switchToTab(el) {
  let name = $(el).data('open-tab');
  $('.tabBar button').removeClass('active');
  $(el).addClass('active');
  $('.tabs .tab').hide();
  $('.tab--' + name).show();

  if (name == 'history') {
      loadHistory();
  }
}

function loadHistory() {
  let source = $('#js-feed-item-template').html();
  /** global: Handlebars */
  let template = Handlebars.compile(source);

  $.getJSON('/feed/opened/', function (data) {
    if (data.status !== 'success') {
      return;
    }

    $('.tab--history').html(template({
      feedItems: data.items
    }));
  });
}
