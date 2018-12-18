/* eslint-disable no-undef,no-param-reassign */
/** global: chrome */

const HEADER_BLACKLIST = [
  'content-security-policy',
  'x-frame-options',
];

const pushLink = (link) => {
  const xhr = new XMLHttpRequest();
  xhr.open('POST', 'https://www.feednews.me/chrome/import', true);
  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

  xhr.onreadystatechange = () => {
    if (xhr.readyState !== XMLHttpRequest.DONE) {
      return;
    }
    const response = JSON.parse(xhr.responseText);
    const title = response.status === 'success' ? response.title : 'Error occurred';
    const body = response.status === 'success' ? response.description : response.message;

    (() => new Notification(title, { icon: 'feednews.png', body }))();
  };

  xhr.send(`url=${encodeURIComponent(link)}`);
};

chrome.browserAction.onClicked.addListener(() => {
  chrome.tabs.query({ active: true, lastFocusedWindow: true }, (tabs) => {
    pushLink(tabs[0].url);
  });
});

chrome.webRequest.onHeadersReceived.addListener(
  (details) => {
    const filteredHeaders = details.responseHeaders.filter((header) => {
      const sanitizedHeader = header.name.toLowerCase();
      return HEADER_BLACKLIST.indexOf(sanitizedHeader) < 0;
    });

    return { responseHeaders: filteredHeaders };
  },
  { urls: ['<all_urls>'] },
  ['blocking', 'responseHeaders'],
);

chrome.contextMenus.create({
  title: 'Add to Feednews',
  contexts: ['link'],
  onclick: (info) => {
    pushLink(info.linkUrl);
  },
});

