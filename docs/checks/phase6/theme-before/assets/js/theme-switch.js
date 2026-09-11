(function () {
    'use strict';
    if (!window.NextcoreTheme) { return; }
    var buttons = document.querySelectorAll('.nextcore-theme-switch');
    function sync() {
        buttons.forEach(function (button) {
            button.hidden = false;
            button.setAttribute('aria-pressed', String(window.NextcoreTheme.get() === 'dark'));
        });
    }
    buttons.forEach(function (button) {
        button.addEventListener('click', function () {
            window.NextcoreTheme.set(window.NextcoreTheme.get() === 'dark' ? 'light' : 'dark');
        });
    });
    window.addEventListener('nextcore:themechange', sync);
    sync();
}());

