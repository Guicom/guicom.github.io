(function() {
    var pymChild = new pym.Child();
    window.addEventListener('load', function() {
        console.log("[PYM] Loaded")
        setTimeout(function() {
            var height = document.getElementsByTagName('body')[0].offsetHeight.toString();
            console.log("[PYM] Height sent ("+ height +"px)")
            pymChild.sendHeight();
        }, 300);
    });
})();