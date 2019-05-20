// Taken from : https://gist.github.com/CrocoDillon/7989214
// vanilla JavaScript
var links = document.links;

for (var i = 0, linksLength = links.length; i < linksLength; i++) {
  if (links[i].hostname != window.location.hostname) {
    links[i].target = '_blank';
  }
}
