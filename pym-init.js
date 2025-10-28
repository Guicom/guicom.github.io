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
        timeout = timeout || 200;
        setTimeout(function() {
            var currentHeight = document.documentElement.scrollHeight;
            pymChild.sendHeight();
            console.log('[Pym] Height sent:', currentHeight + 'px - Reason:', reason || 'unknown', '- Delay:', timeout + 'ms');
        }, timeout);
    }

    // Initial height send
    updateHeight(200, 'initial');

    // 1. Observe DOM changes with debouncing to avoid too many updates
    var mutationTimeout;
    var observer = new MutationObserver(function(mutations) {
        clearTimeout(mutationTimeout);
        mutationTimeout = setTimeout(function() {
            console.log('[Pym] DOM mutation detected');
            updateHeight(100, 'mutation');
        }, 50); // Debounce mutations
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
        // Multiple updates to catch errors at different timings
        updateHeight(100, 'submit-immediate');
        updateHeight(500, 'submit-delayed');
        updateHeight(1000, 'submit-final');
    }, true); // Capture phase = true

    // 3. Listen for clicks on submit button
    document.addEventListener('click', function(e) {
        var target = e.target;
        if (target.type === 'submit' ||
            target.tagName === 'BUTTON' ||
            target.closest('button') ||
            target.closest('input[type="submit"]')) {
            console.log('[Pym] Submit button clicked');
            setTimeout(function() {
                updateHeight(100, 'click');
            }, 100);
        }
    }, true);

    // 4. Periodic fallback update after form changes
    var updateCounter = 0;
    var maxUpdates = 10;
    var periodicInterval;

    document.addEventListener('change', function(e) {
        console.log('[Pym] Change event detected on:', e.target.name || e.target.id);
        updateCounter = 0;
        clearInterval(periodicInterval); // Clear previous interval

        periodicInterval = setInterval(function() {
            pymChild.sendHeight();
            console.log('[Pym] Periodic update', updateCounter + 1, '/', maxUpdates);
            updateCounter++;
            if (updateCounter >= maxUpdates) {
                clearInterval(periodicInterval);
            }
        }, 500);
    });

    // 5. Check for height changes every second
    var lastHeight = 0;
    setInterval(function() {
        var currentHeight = document.documentElement.scrollHeight;
        if (currentHeight !== lastHeight) {
            console.log('[Pym] Height changed from', lastHeight, 'to', currentHeight);
            pymChild.sendHeight();
            lastHeight = currentHeight;
        }
    }, 1000);

    console.log('[Pym] All listeners attached successfully');
})();