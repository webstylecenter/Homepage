/** global: chrome */
chrome.browserAction.onClicked.addListener(function(){

	chrome.tabs.query({'active': true, 'lastFocusedWindow': true}, function (tabs) {
	    var url = tabs[0].url;

		var xhr = new XMLHttpRequest();
		xhr.open('POST', 'https://feednews.me/chrome/import/', true);
		xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

		xhr.onreadystatechange = function () {
		    if (xhr.readyState !== XMLHttpRequest.DONE) {
		    	return;
		    }

		    var response = JSON.parse(this.responseText);
			var title = response.status === 'success' ? response.data.title : 'Error occurred';
			var body = response.status === 'success' ? response.data.description : response.message;

            /** global: Notification */
			new Notification(title, {icon: 'feednews.png', body: body});

		};
		xhr.send('url=' + encodeURIComponent(url));
	});
});
