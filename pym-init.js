(function(){
    console.log('[Pym] Script starting...');

    // Wait for pym to be available
    if (typeof pym === 'undefined') {
        console.error('[Pym] pym.js is not loaded!');
        return;
    }

    var pymChild = new pym.Child();
    console.log('[Pym] Child initialized');

    // Function to send height with a small delay
    function updateHeight(timeout, reason) {
        timeout = timeout || 50; // Reduced default timeout
        setTimeout(function() {
            var currentHeight = document.documentElement.scrollHeight;
            pymChild.sendHeight();
            console.log('[Pym] Height sent:', currentHeight + 'px - Reason:', reason || 'unknown', '- Delay:', timeout + 'ms');
        }, timeout);
    }

    // CRITICAL: Send height immediately and multiple times on page load
    updateHeight(0, 'immediate-load');
    updateHeight(50, 'quick-load');
    updateHeight(100, 'fast-load');
    updateHeight(200, 'normal-load');
    updateHeight(500, 'delayed-load');

    // 1. Observe DOM changes with debouncing
    var mutationTimeout;
    var observer = new MutationObserver(function(mutations) {
        clearTimeout(mutationTimeout);
        mutationTimeout = setTimeout(function() {
            console.log('[Pym] DOM mutation detected');
            updateHeight(50, 'mutation');
        }, 50);
    });

    // Observe entire body to detect changes
    observer.observe(document.body, {
        childList: true,
        subtree: true,
        attributes: true,
        attributeFilter: ['class', 'style']
    });
    console.log('[Pym] MutationObserver attached');

    // 2. Listen for form submission - CAPTURE PHASE
    document.addEventListener('submit', function(e) {
        console.log('[Pym] Form submitted');
        // Send height before page reload
        updateHeight(0, 'submit-immediate');
        updateHeight(100, 'submit-quick');
        updateHeight(300, 'submit-delayed');
    }, true);

    // 3. Listen for clicks on submit button
    document.addEventListener('click', function(e) {
        var target = e.target;
        if (target.type === 'submit' ||
            target.tagName === 'BUTTON' ||
            target.closest('button') ||
            target.closest('input[type="submit"]')) {
            console.log('[Pym] Submit button clicked');
            updateHeight(50, 'click');
        }
    }, true);

    // 4. Periodic fallback update after form changes
    var updateCounter = 0;
    var maxUpdates = 8; // Reduced to 4 seconds
    var periodicInterval;

    document.addEventListener('change', function(e) {
        console.log('[Pym] Change event detected on:', e.target.name || e.target.id);
        updateCounter = 0;
        clearInterval(periodicInterval);

        periodicInterval = setInterval(function() {
            pymChild.sendHeight();
            console.log('[Pym] Periodic update', updateCounter + 1, '/', maxUpdates);
            updateCounter++;
            if (updateCounter >= maxUpdates) {
                clearInterval(periodicInterval);
            }
        }, 500);
    });

    // 5. Aggressive height monitoring on page load
    var lastHeight = 0;
    var checkCount = 0;
    var maxChecks = 20; // Check for 10 seconds

    var heightCheckInterval = setInterval(function() {
        var currentHeight = document.documentElement.scrollHeight;
        checkCount++;

        if (currentHeight !== lastHeight) {
            console.log('[Pym] Height changed from', lastHeight, 'to', currentHeight);
            pymChild.sendHeight();
            lastHeight = currentHeight;
        }

        // Stop checking after 10 seconds
        if (checkCount >= maxChecks) {
            clearInterval(heightCheckInterval);
            console.log('[Pym] Height monitoring stopped');
        }
    }, 500); // Check every 500ms

    console.log('[Pym] All listeners attached successfully');
})();