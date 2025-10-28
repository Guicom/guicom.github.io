(function() {
    var pymChild = new pym.Child();
    window.addEventListener('load', function() {
        console.log("[PYM] Loaded")
        setTimeout(function() {
            pymChild.sendHeight();
            console.log("[PYM] Height sent")
        }, 300);
    });
})();