/* Loaded synchronously in head: select images as markup arrives, before paint. */
(function () {
    'use strict';
    var images = new Set();
    var video = null, section = null, inView = false, generation = 0;
    var reduced = window.matchMedia('(prefers-reduced-motion: reduce)');
    function mode() { return document.documentElement.getAttribute('data-theme') || (matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'); }
    function selectImage(img) {
        var url = img.dataset[mode() + 'Src'];
        if (!url || img.getAttribute('src') === url) { return; }
        img.style.visibility = 'hidden';
        img.onload = function () {
            if (img.getAttribute('src') === img.dataset[mode() + 'Src']) { img.style.visibility = ''; }
        };
        img.src = url;
        if (img.complete && img.naturalWidth) { img.style.visibility = ''; }
    }
    function hideFrame() { if (section) { section.classList.remove('is-playing'); } }
    function syncVideo() {
        if (!video) { return; }
        if (reduced.matches || !inView || document.hidden) {
            video.pause();
            if (reduced.matches) {
                hideFrame();
                if (video.hasAttribute('src')) { video.removeAttribute('src'); video.load(); }
            }
            return;
        }
        var url = video.dataset[mode() + 'Src'];
        if (video.getAttribute('src') !== url) {
            hideFrame();
            video.src = url;
            video.load();
        }
        var token = generation;
        var promise = video.play();
        if (promise) { promise.catch(function () { if (token === generation) { hideFrame(); } }); }
    }
    function changeMode() {
        generation++;
        hideFrame();
        if (video) { video.pause(); video.removeAttribute('src'); video.load(); }
        images.forEach(selectImage);
        if (video && section) {
            var poster = section.querySelector('[data-mode-image]');
            if (poster) { video.poster = poster.dataset[mode() + 'Src']; }
        }
        syncVideo();
    }
    function bindVideo(element) {
        if (video) { return; }
        video = element;
        section = video.closest('.company-video');
        video.muted = true;
        video.addEventListener('playing', function () {
            var token = generation;
            var expected = new URL(video.dataset[mode() + 'Src'], document.baseURI).href;
            function show() {
                if (token === generation && video.currentSrc === expected && !video.paused && !reduced.matches && inView && !document.hidden) {
                    section.classList.add('is-playing');
                }
            }
            if (video.requestVideoFrameCallback) { video.requestVideoFrameCallback(show); } else { show(); }
        });
        video.addEventListener('error', hideFrame);
        // Keep the decoded frame on waiting/loop boundaries; never reveal a different-mode background.
        if ('IntersectionObserver' in window) {
            new IntersectionObserver(function (entries) { inView = entries[0].isIntersecting; syncVideo(); }, { threshold: 0 }).observe(section);
        } else { inView = true; }
        changeMode();
    }
    function scan(root) {
        if (root.nodeType !== 1 && root.nodeType !== 9) { return; }
        var matches = [];
        if (root.matches && root.matches('[data-mode-image]')) { matches.push(root); }
        matches = matches.concat(Array.from(root.querySelectorAll('[data-mode-image]')));
        matches.forEach(function (img) { if (!images.has(img)) { images.add(img); selectImage(img); } });
        var candidate = root.matches && root.matches('.company-video-media') ? root : root.querySelector('.company-video-media');
        if (candidate) { bindVideo(candidate); }
    }
    var observer = new MutationObserver(function (changes) { changes.forEach(function (change) { change.addedNodes.forEach(scan); }); });
    observer.observe(document.documentElement, { childList: true, subtree: true });
    scan(document);
    document.addEventListener('DOMContentLoaded', function () { scan(document); observer.disconnect(); });
    window.addEventListener('nextcore:themechange', changeMode);
    document.addEventListener('visibilitychange', syncVideo);
    reduced.addEventListener('change', syncVideo);
}());

