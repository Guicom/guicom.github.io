(function(){
    console.log('[Pym] Script starting...');

    var pymChild = new pym.Child();
    console.log('[Pym] Child initialized');

    // Function to send height with a small delay
    function updateHeight(timeout, reason) {
        timeout = timeout || 200;
        setTimeout(function() {
            pymChild.sendHeight();
            console.log('[Pym] Height sent - Reason:', reason || 'unknown', '- Delay:', timeout + 'ms');
        }, timeout);
    }

    // Initial height send
    updateHeight(200, 'initial');

    // 1. Observe DOM changes
    var observer = new MutationObserver(function(mutations) {
        console.log('[Pym] DOM mutation detected');
        updateHeight(100, 'mutation');
    });

    // Observe entire body to detect changes
    observer.observe(document.body, {
        childList: true,
        subtree: true,
        attributes: true,
        attributeFilter: ['class', 'style']
    });
    console.log('[Pym] MutationObserver attached');

    // 2. Listen for form submission
    document.addEventListener('submit', function(e) {
        console.log('[Pym] Form submitted');
        updateHeight(1000, 'submit');
    }, true);

    // 3. Periodic fallback update
    var updateCounter = 0;
    var maxUpdates = 10;
    document.addEventListener('change', function() {
        console.log('[Pym] Change event detected');
        updateCounter = 0;
        var interval = setInterval(function() {
            pymChild.sendHeight();
            console.log('[Pym] Periodic update', updateCounter + 1, '/', maxUpdates);
            updateCounter++;
            if (updateCounter >= maxUpdates) {
                clearInterval(interval);
            }
        }, 500);
    });

    console.log('[Pym] All listeners attached successfully');
})();