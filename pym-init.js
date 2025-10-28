(function(){
    var pymChild = new pym.Child();
    setTimeout(function () {
        pymChild.sendHeight();
    }, 200)

    document.addEventListener('submit', function(e) {
        console.log('form submit')
        setTimeout(function() {
            pymChild.sendHeight();
        }, 100);
    });
})();
