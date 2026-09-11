(function () {
    'use strict';
    var header = document.querySelector('.nextcore-site-header');
    if (!header) { return; }
    var desktop = window.matchMedia('(min-width: 1200px)');
    var entries = [];
    var panel = header.querySelector('.nextcore-mobile-panel');
    var menuToggle = header.querySelector('.nextcore-mobile-toggle');
    function set(entry, open) {
        if (!open && entry.submenu.contains(document.activeElement)) { entry.button.focus(); }
        entry.button.setAttribute('aria-expanded', String(open));
        entry.submenu.hidden = !open;
    }
    header.querySelectorAll('.nextcore-menu .menu-item-has-children').forEach(function (item) {
        var submenu = Array.from(item.children).find(function (child) { return child.classList.contains('sub-menu'); });
        var button = Array.from(item.children).find(function (child) { return child.classList.contains('nextcore-submenu-toggle'); });
        if (!submenu || !button) { return; }
        submenu.id = button.getAttribute('aria-controls');
        var entry = { item: item, submenu: submenu, button: button, timer: null };
        entries.push(entry);
        button.hidden = false;
        set(entry, false);
        function open() {
            clearTimeout(entry.timer);
            entries.forEach(function (other) {
                if (other !== entry && other.item.parentElement === item.parentElement) { set(other, false); }
            });
            set(entry, true);
        }
        button.addEventListener('click', function () {
            if (button.getAttribute('aria-expanded') === 'true') { set(entry, false); }
            else { open(); }
        });
        item.addEventListener('pointerenter', function (event) {
            if (desktop.matches && event.pointerType !== 'touch') { open(); }
        });
        item.addEventListener('pointerleave', function () {
            if (!desktop.matches) { return; }
            entry.timer = setTimeout(function () {
                if (!item.contains(document.activeElement)) { set(entry, false); }
            }, 180);
        });
        item.addEventListener('focusout', function () {
            setTimeout(function () { if (!item.contains(document.activeElement)) { set(entry, false); } }, 0);
        });
        item.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                if (button.getAttribute('aria-expanded') === 'true') {
                    event.stopPropagation();
                    set(entry, false);
                    button.focus();
                }
            }
            if (event.key === 'ArrowDown' && event.target === button) {
                event.preventDefault();
                open();
                var link = submenu.querySelector('a');
                if (link) { link.focus(); }
            }
        });
    });
    function closePanel(focus) {
        if (!panel || !menuToggle) { return; }
        if (focus) { menuToggle.focus(); }
        panel.hidden = true;
        header.classList.remove('menu-open');
        menuToggle.setAttribute('aria-expanded', 'false');
        entries.forEach(function (entry) { set(entry, false); });
    }
    if (panel && menuToggle) {
        menuToggle.hidden = false;
        closePanel(false);
        menuToggle.addEventListener('click', function () {
            var open = menuToggle.getAttribute('aria-expanded') !== 'true';
            panel.hidden = !open;
            header.classList.toggle('menu-open', open);
            menuToggle.setAttribute('aria-expanded', String(open));
        });
        panel.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') { closePanel(true); }
        });
        panel.addEventListener('click', function (event) {
            if (event.target.closest('a')) { closePanel(true); }
        });
    }
    document.addEventListener('click', function (event) {
        if (!header.contains(event.target)) { closePanel(false); }
    });
    function reset() {
        if (panel && panel.contains(document.activeElement)) { menuToggle.focus(); }
        closePanel(false);
    }
    if (desktop.addEventListener) { desktop.addEventListener('change', reset); }
    else { desktop.addListener(reset); }
    function syncHeader() { header.classList.toggle('is-scrolled', window.scrollY > 24); }
    window.addEventListener('scroll', syncHeader, { passive: true });
    syncHeader();
    var search = document.querySelector('.nextcore-search-dialog');
    var searchButton = header.querySelector('[data-nextcore-search]');
    if (search && searchButton) {
        searchButton.addEventListener('click', function () { search.showModal(); });
        search.querySelector('.dialog-close').addEventListener('click', function () { search.close(); });
        search.addEventListener('click', function (event) {
            if (event.target !== search) { return; }
            var rect = search.getBoundingClientRect();
            if (event.clientX < rect.left || event.clientX > rect.right || event.clientY < rect.top || event.clientY > rect.bottom) { search.close(); }
        });
    }
}());
