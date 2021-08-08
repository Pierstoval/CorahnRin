(function (d) {
    var links = d.querySelectorAll('.tabs a');

    for (var i = 0, l = links.length; i < l; i++) {
        links[i].addEventListener('click', function (e) {
            window.location.hash = e.target.getAttribute('href');
        });
    }
})(window.document);
