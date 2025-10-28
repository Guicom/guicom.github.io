(function() {
    var pymChild = new pym.Child();
    window.addEventListener('load', function() {
        setTimeout(function() {
            pymChild.sendHeight();
        }, 300);
    });
})();