(function(){
    var pymChild = new pym.Child();

    // Initial height send.
    updateHeight()

    // Function to send height with a small delay.
    function updateHeight(timeout = 200) {
        setTimeout(function() {
            pymChild.sendHeight();
        }, timeout);
    }

    // 1. Observe DOM changes.
    var observer = new MutationObserver(function(mutations) {
        updateHeight();
    });

    // Observe entire body to detect changes.
    observer.observe(document.body, {
        childList: true,
        subtree: true,
        attributes: true,
        attributeFilter: ['class', 'style']
    });

    // 2. Listen for form submission..
    document.addEventListener('submit', function(e) {
        // Wait for error messages to display.
        updateHeight(1000);
    }, true);


    // 3. Periodic fallback update (every 500ms for 5 seconds after a change)
    var updateCounter = 0;
    var maxUpdates = 10;
    document.addEventListener('change', function() {
        updateCounter = 0;
        var interval = setInterval(function() {
            pymChild.sendHeight();
            updateCounter++;
            if (updateCounter >= maxUpdates) {
                clearInterval(interval);
            }
        }, 500);
    });
})();