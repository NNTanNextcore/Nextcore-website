(function () {
    'use strict';
    var key = 'nextcore-theme-mode';
    var root = document.documentElement;
    var media = window.matchMedia ? window.matchMedia('(prefers-color-scheme: dark)') : null;
    var override = null;
    function valid(value) { return value === 'light' || value === 'dark'; }
    try { var saved = window.localStorage.getItem(key); override = valid(saved) ? saved : null; } catch (error) {}
    function apply() {
        var mode = override || (media && media.matches ? 'dark' : 'light');
        root.setAttribute('data-theme', mode);
        var meta = document.querySelector('meta[name="theme-color"]');
        if (meta) { meta.setAttribute('content', mode === 'dark' ? '#070b0d' : '#ffffff'); }
        window.dispatchEvent(new CustomEvent('nextcore:themechange', { detail: { mode: mode } }));
        return mode;
    }
    window.NextcoreTheme = {
        get: function () { return root.getAttribute('data-theme'); },
        set: function (mode) {
            if (!valid(mode)) { return; }
            override = mode;
            try { window.localStorage.setItem(key, mode); } catch (error) {}
            apply();
        }
    };
    if (media) {
        if (media.addEventListener) { media.addEventListener('change', apply); }
        else if (media.addListener) { media.addListener(apply); }
    }
    window.addEventListener('storage', function (event) {
        if (event.key !== key && event.key !== null) { return; }
        override = valid(event.newValue) ? event.newValue : null;
        apply();
    });
    apply();
}());
