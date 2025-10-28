(function() {
    // Initialise le Child pym
    var pymChild = new pym.Child();

    // Fonction pour envoyer la hauteur au parent
    function sendHeight() {
        const h = document.body.scrollHeight;
        pymChild.sendHeight();
        console.log('[PYM][child] Height sent:', h);
    }

    // Envoi répété jusqu'à stabilisation pour couvrir le POST reload
    function sendHeightRepeatedly(maxAttempts = 20, intervalMs = 250) {
        let attempts = 0;
        const interval = setInterval(() => {
            attempts++;
            sendHeight();
            if (attempts >= maxAttempts) clearInterval(interval);
        }, intervalMs);
    }

    // Observer les changements DOM pour détecter messages d’erreur
    const observer = new MutationObserver(() => {
        setTimeout(sendHeight, 100);
    });
    observer.observe(document.body, { childList: true, subtree: true, attributes: true });

    // Lancer l’envoi après load
    window.addEventListener('load', () => {
        sendHeightRepeatedly();
    });

    // Détecter la soumission du formulaire
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', () => {
            console.log('[PYM][child] Form submitted, notifying parent');
            window.parent.postMessage({ type: 'form-submitted' }, '*');
        });
    }
})();