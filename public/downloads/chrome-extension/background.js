/** global: chrome */
chrome.browserAction.onClicked.addListener(function(){

    chrome.tabs.query({'active': true, 'lastFocusedWindow': true}, function (tabs) {
        var url = tabs[0].url;

        console.log(url);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'https://feednews.me/chrome/import/', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');



        xhr.onreadystatechange = function (data) { console.log(data);
            if (xhr.readyState !== XMLHttpRequest.DONE) {
                return;
            }

            console.log(this.responseText);

            var response = JSON.parse(this.responseText);
            var title = response.status === 'success' ? response.data.title : 'Error occurred';
            var body = response.status === 'success' ? response.data.description : response.message;

            /** global: Notification */
            new Notification(title, {icon: 'feednews.png', body: body});

        };
        xhr.send('url=' + encodeURIComponent(url));
    });
});

var HEADERS_TO_STRIP_LOWERCASE = [
    'content-security-policy',
    'x-frame-options',
];

chrome.webRequest.onHeadersReceived.addListener(
    function(details) {
        return {
            responseHeaders: details.responseHeaders.filter(function(header) {
                return HEADERS_TO_STRIP_LOWERCASE.indexOf(header.name.toLowerCase()) < 0;
            })
        };
    }, {
        urls: ["<all_urls>"]
    }, ["blocking", "responseHeaders"]);
