(function () {
    'use strict';
    if (!window.NextcoreTheme) { return; }
    function sync() {
        document.querySelectorAll('.nextcore-theme-switch').forEach(function (button) {
            button.hidden = false;
            button.setAttribute('aria-pressed', String(window.NextcoreTheme.get() === 'dark'));
        });
    }
    document.addEventListener('click', function (event) {
        if (!event.target.closest('.nextcore-theme-switch')) { return; }
        window.NextcoreTheme.set(window.NextcoreTheme.get() === 'dark' ? 'light' : 'dark');
    });
    window.addEventListener('nextcore:themechange', sync);
    new MutationObserver(sync).observe(document.body, { childList: true, subtree: true });
    sync();
}());

