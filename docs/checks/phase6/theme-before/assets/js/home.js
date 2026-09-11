(function () {
    'use strict';
    var home = document.querySelector('.nextcore-home');
    if (!home) { return; }
    var reduced = matchMedia('(prefers-reduced-motion: reduce)');
    if ('IntersectionObserver' in window && !reduced.matches) {
        var reveal = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) { entry.target.classList.add('is-visible'); reveal.unobserve(entry.target); }
            });
        }, {threshold: 0.08});
        home.querySelectorAll('[data-reveal]').forEach(function (element) {
            element.classList.add('reveal-ready'); reveal.observe(element);
        });
        reduced.addEventListener('change', function () {
            if (reduced.matches) { home.querySelectorAll('[data-reveal]').forEach(function (element) { element.classList.add('is-visible'); }); }
        });
    }
    var marquee = home.querySelector('.marquee');
    var track = home.querySelector('.marquee-track');
    var button = home.querySelector('.marquee-control');
    if (!marquee || !track || !track.firstElementChild || !button) { return; }
    var duplicate = track.firstElementChild.cloneNode(true);
    duplicate.setAttribute('aria-hidden', 'true');
    duplicate.setAttribute('inert', '');
    track.appendChild(duplicate);
    button.addEventListener('click', function () {
        var paused = marquee.classList.toggle('is-paused');
        button.setAttribute('aria-pressed', String(paused));
        button.querySelector('[aria-hidden]').textContent = paused ? '▷' : 'Ⅱ';
    });
}());

