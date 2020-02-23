
require('./legacy/components/main');
require('./legacy/components/clipboard');
require('./legacy/components/checklist');
require('./legacy/components/droplist');
require('./legacy/components/newsfeedlist');
require('./legacy/components/screensaver');
require('./legacy/components/searchbox');
require('./legacy/components/settings');
require('./legacy/components/welcome');
require('./legacy/components/register');
require('./legacy/components/tabs');

window.showDialog = function(title, description) {
    $('.dialog .title').html(title);
    $('.dialog .description').html(description);
    $('.dialog').modal({fadeDuration: 100});
}
