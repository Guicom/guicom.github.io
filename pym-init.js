(function(){
    var pymChild = new pym.Child();
    setTimeout(function () {
        pymChild.sendHeight();
    }, 200)

    document.addEventListener('submit', function(e) {
        setTimeout(function() {
            pymChild.sendHeight();
        }, 100);
    });
})();
