(function() {
    // Initialise le Child pym
    var pymChild = new pym.Child({ id: 'pardot-form' });

    // Fonction pour envoyer la hauteur du document
    function sendHeight() {
        const h = document.body.scrollHeight;
        pymChild.sendHeight();
        console.log('[PYM][child] Height sent:', h);
    }

    // Envoi répété jusqu'à stabilisation (utile pour POST / reload)
    function sendHeightRepeatedly(maxAttempts = 20, intervalMs = 250) {
        let attempts = 0;
        const interval = setInterval(() => {
            attempts++;
            sendHeight();
            if (attempts >= maxAttempts) clearInterval(interval);
        }, intervalMs);
    }

    // Observer les changements DOM pour attraper les messages d'erreur
    const observer = new MutationObserver(() => {
        setTimeout(sendHeight, 100);
    });
    observer.observe(document.body, { childList: true, subtree: true, attributes: true });

    // Lancer après load
    window.addEventListener('load', () => {
        sendHeightRepeatedly();
    });
})();
