(function () {
    'use strict';
    var home = document.querySelector('body.nextcore-homepage .nextcore-home');
    if (!home) { return; }
    var root = document.documentElement;
    var header = document.querySelector('.nextcore-site-header');
    var admin = document.getElementById('wpadminbar');
    var nodes = Array.prototype.filter.call(home.children, function (node) { return node.matches('section'); });
    var footer = document.querySelector('.site-footer');
    if (footer) { nodes.push(footer); }
    if (nodes.length < 2) { return; }
    var desktop = matchMedia('(min-width: 1200px) and (hover: hover) and (pointer: fine)');
    var reduced = matchMedia('(prefers-reduced-motion: reduce)');
    var threshold = 45, tolerance = 20, quietPeriod = 200;
    var scenes = [], offset = 0, viewport = innerHeight, available = innerHeight, snapOverflow = 0, maxY = 0;
    var frame = 0, layoutFrame = 0, layoutDirty = false, animation = null;
    var state = 'idle', gesture = null, lastWheel = -Infinity, tailUntil = 0, tailDirection = 0;
    var reverseDelta = 0, reverseStarted = 0;
    var touch = null, touchIntent = false, settleTimer = 0, userInteracted = false;
    var excluded = 'input, textarea, select, iframe, [contenteditable]:not([contenteditable="false"]), [role="slider"], [role="dialog"], [role="listbox"], dialog, .modal, .lightbox, .dropdown, .sub-menu, .nextcore-mobile-panel, .map, .map-container, .leaflet-container, .mapboxgl-map, [data-nc-snap-ignore]';

    nodes.forEach(function (node) { node.setAttribute('data-nc-snap-section', ''); });
    root.classList.add('nc-home-snap');

    // Opt-in QA events only; no frame history or logging in normal operation.
    function trace(reason, extra) {
        if (!root.hasAttribute('data-nc-snap-debug')) { return; }
        document.dispatchEvent(new CustomEvent('nc:snap-debug', {detail: Object.assign({
            time: performance.now(), y: scrollY, state: state, reason: reason,
            target: animation ? animation.destination : null
        }, extra || {})}));
    }
    function blocked() {
        return (header && header.classList.contains('menu-open')) || document.body.classList.contains('modal-open') ||
            !!document.querySelector('dialog[open], [aria-modal="true"]:not([hidden]), .is-dragging');
    }
    function ignore(target, direction) {
        if (!(target instanceof Element) || target.closest(excluded)) { return true; }
        for (var node = target; node && node !== document.body; node = node.parentElement) {
            var style = getComputedStyle(node);
            if (/(auto|scroll)/.test(style.overflowY) && node.scrollHeight > node.clientHeight + 1 &&
                (direction < 0 ? node.scrollTop > 0 : node.scrollTop + node.clientHeight < node.scrollHeight - 1)) { return true; }
        }
        return false;
    }
    function measure() {
        layoutFrame = 0;
        if (animation) { layoutDirty = true; return; }
        layoutDirty = false;
        viewport = innerHeight;
        admin = document.getElementById('wpadminbar');
        offset = [header, admin].reduce(function (occupied, node) {
            if (!node) { return occupied; }
            return /fixed|sticky/.test(getComputedStyle(node).position) ? Math.max(occupied, node.getBoundingClientRect().bottom) : occupied;
        }, 0);
        root.style.setProperty('--nc-snap-offset', offset + 'px');
        maxY = Math.max(0, root.scrollHeight - viewport);
        available = viewport - offset;
        // Small overflow commonly comes from responsive padding/aspect ratios. Treat
        // it as one visual scene so a full-page section does not require a second
        // wheel gesture merely to clear its last few pixels.
        snapOverflow = Math.min(160, Math.max(0, available * .2));
        scenes = [];
        nodes.forEach(function (node) {
            var rect = node.getBoundingClientRect();
            node.removeAttribute('data-nc-snap-group');
            if (!rect.height) { return; }
            var top = rect.top + scrollY, bottom = rect.bottom + scrollY;
            var previous = scenes[scenes.length - 1];
            var shortFooter = node === footer && previous && previous.node.id === 'contact' &&
                rect.height < available * .5 && bottom - previous.top < available * 1.25;
            if (shortFooter) {
                previous.bottom = bottom;
                node.setAttribute('data-nc-snap-group', previous.node.id);
            } else { scenes.push({node: node, top: top, bottom: bottom}); }
        });
    }
    function scheduleMeasure() {
        if (!layoutFrame) { layoutFrame = requestAnimationFrame(measure); }
    }
    function cancel() {
        cancelAnimationFrame(frame);
        frame = 0;
        animation = null;
        state = 'idle';
        tailUntil = 0;
        gesture = null;
        lastWheel = -Infinity;
        reverseDelta = 0;
        touchIntent = false;
        clearTimeout(settleTimer);
        if (layoutDirty) { scheduleMeasure(); }
        trace('cancel');
    }
    // Cubic-bezier(0.25, 0.1, 0.25, 1): solve x(t), then evaluate y(t).
    function ease(progress) {
        var low = 0, high = 1, t = progress;
        for (var i = 0; i < 14; i++) {
            var x = 3 * (1 - t) * t * .25 + t * t * t;
            if (x < progress) { low = t; } else { high = t; }
            t = (low + high) / 2;
        }
        return 3 * (1 - t) * (1 - t) * t * .1 + 3 * (1 - t) * t * t + t * t * t;
    }
    function clampY(y) { return Math.max(0, Math.min(maxY, y)); }
    function smoothScrollToTarget(destination, milliseconds, complete) {
        cancelAnimationFrame(frame);
        frame = 0;
        animation = null;
        reverseDelta = 0;
        tailUntil = 0;
        var start = scrollY, started = performance.now();
        destination = clampY(destination);
        var distance = Math.abs(destination - start);
        if (distance < 2) { state = 'idle'; gesture = null; trace('no-op'); if (complete) { complete(); } return; }
        if (milliseconds == null) { milliseconds = Math.max(230, Math.min(900, 800 * Math.sqrt(distance / Math.max(1, viewport - offset)))); }
        if (reduced.matches) {
            window.scrollTo({top: destination, behavior: 'instant'});
            state = 'idle'; gesture = null;
            if (complete) { complete(); }
            return;
        }
        animation = {destination: destination, direction: Math.sign(destination - start)};
        state = 'animating';
        trace('start', {duration: milliseconds, from: start});
        function tick(now) {
            if (blocked()) { cancel(); return; }
            var progress = Math.min(1, (now - started) / milliseconds);
            window.scrollTo({top: progress === 1 ? destination : start + (destination - start) * ease(progress), behavior: 'instant'});
            trace('frame');
            if (progress < 1) { frame = requestAnimationFrame(tick); }
            else {
                tailDirection = animation.direction;
                animation = null; frame = 0; gesture = null;
                state = 'tail'; tailUntil = now + 180;
                trace('complete', {destination: destination, tailUntil: tailUntil});
                if (layoutDirty) { measure(); }
                if (complete) { complete(); }
            }
        }
        frame = requestAnimationFrame(tick);
    }
    function directionalTarget(values, direction) {
        var targets = values.map(clampY).filter(function (y, i, all) {
            return (y - scrollY) * direction >= 2 && all.indexOf(y) === i;
        });
        return targets.length ? (direction > 0 ? Math.min.apply(null, targets) : Math.max.apply(null, targets)) : null;
    }
    function targetFor(direction, reversing) {
        // A reversal may return to the boundary behind the current animation position.
        if (reversing) {
            var boundaries = [0, maxY];
            scenes.forEach(function (scene) {
                boundaries.push(scene.top - offset);
                if (direction < 0) { boundaries.push(Math.max(scene.top - offset, scene.bottom - viewport)); }
            });
            return directionalTarget(boundaries, direction);
        }
        var visibleTop = scrollY + offset, index = 0;
        // The final short scene may hit the document limit before its top reaches
        // the header. Use its reachable landing position in both directions.
        scenes.forEach(function (scene, i) { if (clampY(scene.top - offset) <= scrollY + tolerance) { index = i; } });
        var scene = scenes[index];
        if (!scene) { return null; }
        if (direction > 0) {
            var remaining = scene.bottom - scrollY - viewport;
            var atSceneStart = Math.abs(scrollY - clampY(scene.top - offset)) <= tolerance;
            if ((remaining > tolerance && !(atSceneStart && remaining <= snapOverflow)) || index === scenes.length - 1) { return null; }
            return directionalTarget(scenes.slice(index + 1).map(function (next) { return next.top - offset; }), direction);
        }
        var passedTop = visibleTop - scene.top;
        var atSceneEnd = Math.abs(scrollY - clampY(scene.bottom - viewport)) <= tolerance;
        // Mirror the small-overflow allowance used by downward snaps. When an
        // almost-full-screen scene was entered from below, its reachable landing
        // point is slightly past its top; that must not turn the next upward wheel
        // gesture into native scrolling.
        if (passedTop > tolerance && !(atSceneEnd && passedTop <= snapOverflow)) { return null; }
        var previous = scenes.slice(0, index).map(function (item) { return Math.max(item.top - offset, item.bottom - viewport); });
        previous.push(0);
        return directionalTarget(previous, direction);
    }
    window.addEventListener('wheel', function (event) {
        userInteracted = true;
        if (!desktop.matches || reduced.matches || event.ctrlKey || event.metaKey || Math.abs(event.deltaX) > Math.abs(event.deltaY) || !event.deltaY) { return; }
        if (blocked()) { cancel(); return; }
        var direction = Math.sign(event.deltaY), now = performance.now();
        var fresh = now - lastWheel > quietPeriod;
        var delta = Math.abs(event.deltaY) * (event.deltaMode === 1 ? 16 : event.deltaMode === 2 ? viewport : 1);
        lastWheel = now;
        // An actual move into a control starts a new interaction. Content passing under
        // a stationary pointer during the captured gesture must not cancel the animation.
        var moved = gesture && Math.hypot(event.clientX - gesture.x, event.clientY - gesture.y) > 8;
        if (animation) {
            if (fresh && (!gesture || moved) && ignore(event.target, direction)) { cancel(); return; }
            if (!event.cancelable) { cancel(); return; }
            event.preventDefault();
            if (direction === animation.direction) { reverseDelta = 0; return; }
            if (!reverseDelta || now - reverseStarted > 160) { reverseDelta = 0; reverseStarted = now; }
            reverseDelta += delta;
            if (reverseDelta >= threshold) {
                var destination = targetFor(direction, true);
                trace('reverse', {destination: destination});
                if (destination === null) { cancel(); return; }
                gesture = {direction: direction, x: event.clientX, y: event.clientY};
                smoothScrollToTarget(destination);
            }
            return;
        }
        if (state === 'tail') {
            if (!fresh && direction === tailDirection && now < tailUntil) {
                if (event.cancelable) { event.preventDefault(); }
                return;
            }
            state = 'idle'; gesture = null; trace('unlock');
        }
        if (fresh || !gesture || gesture.direction !== direction) {
            if (layoutDirty) { measure(); }
            gesture = {direction: direction, delta: 0, x: event.clientX, y: event.clientY,
                destination: ignore(event.target, direction) ? null : targetFor(direction)};
            state = gesture.destination === null ? 'native' : 'collecting';
            trace(state === 'native' ? 'native-gesture' : 'boundary-intent');
        }
        // Never switch a native gesture into a snap in the middle of its momentum.
        if (state === 'native') { return; }
        if (!event.cancelable) { cancel(); return; }
        event.preventDefault();
        gesture.delta += delta;
        if (gesture.delta >= threshold) { smoothScrollToTarget(gesture.destination); }
    }, {passive: false});

    function stopForInput() { userInteracted = true; cancel(); }
    window.addEventListener('pointerdown', stopForInput, {passive: true});
    window.addEventListener('keydown', function (event) {
        if (['ArrowDown', 'ArrowUp', 'PageDown', 'PageUp', 'Home', 'End', ' ', 'Escape', 'Tab'].indexOf(event.key) !== -1) { stopForInput(); }
    });

    // Native momentum first. Only settle after a real swipe ends within 24px of a scene boundary.
    window.addEventListener('touchstart', function (event) {
        stopForInput();
        touch = event.touches.length === 1 && !blocked() && !ignore(event.target, 1) && !ignore(event.target, -1) ?
            {y: event.touches[0].clientY, scroll: scrollY} : null;
    }, {passive: true});
    function settle() {
        clearTimeout(settleTimer);
        settleTimer = setTimeout(function () {
            if (!touchIntent || touch || desktop.matches || reduced.matches || blocked() || frame) { return; }
            touchIntent = false;
            var nearest = null, distance = 25;
            scenes.forEach(function (scene) {
                var y = Math.max(0, Math.min(maxY, scene.top - offset));
                var delta = Math.abs(y - scrollY);
                if (delta < distance) { distance = delta; nearest = y; }
            });
            if (nearest !== null && distance > 1) { smoothScrollToTarget(nearest, 180); }
        }, 180);
    }
    window.addEventListener('touchend', function (event) {
        touchIntent = !!touch && event.changedTouches.length === 1 &&
            Math.abs(touch.y - event.changedTouches[0].clientY) >= 60 && Math.abs(scrollY - touch.scroll) > 4;
        touch = null;
        if (touchIntent) { settle(); }
    }, {passive: true});
    window.addEventListener('touchcancel', function () { touch = null; cancel(); }, {passive: true});
    window.addEventListener('scroll', function () { if (touchIntent) { settle(); } }, {passive: true});

    function hashTarget(hash) {
        try { return hash && document.getElementById(decodeURIComponent(hash.slice(1))); }
        catch (error) { return null; }
    }
    document.addEventListener('click', function (event) {
        var link = event.target.closest('a[href]');
        if (!link || event.defaultPrevented || event.button !== 0 || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey ||
            link.hasAttribute('download') || (link.target && link.target !== '_self')) { return; }
        var url = new URL(link.href, location.href);
        if (url.origin !== location.origin || url.pathname !== location.pathname || url.search !== location.search) { return; }
        var target = hashTarget(url.hash);
        if (!target || !(home.contains(target) || (footer && footer.contains(target))) || blocked()) { return; }
        event.preventDefault();
        userInteracted = true;
        cancel();
        measure();
        if (location.hash !== url.hash) { history.pushState(null, '', url.hash); }
        smoothScrollToTarget(target.getBoundingClientRect().top + scrollY - offset, null, function () {
            var temporary = !target.hasAttribute('tabindex');
            if (temporary) { target.setAttribute('tabindex', '-1'); }
            target.focus({preventScroll: true});
            if (temporary) { target.addEventListener('blur', function () { target.removeAttribute('tabindex'); }, {once: true}); }
        });
    });
    function landHash() {
        var target = hashTarget(location.hash);
        if (!target || !(home.contains(target) || (footer && footer.contains(target)))) { return; }
        measure();
        window.scrollTo({top: Math.max(0, target.getBoundingClientRect().top + scrollY - offset), behavior: 'instant'});
    }
    window.addEventListener('popstate', cancel);
    window.addEventListener('hashchange', function () { cancel(); requestAnimationFrame(landHash); });
    window.addEventListener('pageshow', scheduleMeasure);
    window.addEventListener('resize', function () { cancel(); scheduleMeasure(); }, {passive: true});
    window.addEventListener('orientationchange', function () { cancel(); scheduleMeasure(); }, {passive: true});
    reduced.addEventListener('change', cancel);
    desktop.addEventListener('change', cancel);
    if ('ResizeObserver' in window) {
        var observer = new ResizeObserver(scheduleMeasure);
        nodes.concat([header, admin]).forEach(function (node) { if (node) { observer.observe(node); } });
    }
    if (header) {
        new MutationObserver(function () { if (blocked()) { cancel(); } scheduleMeasure(); }).observe(header, {attributes: true, attributeFilter: ['class']});
    }
    measure();
    function ready() {
        scheduleMeasure();
        // Let the browser finish its initial fragment scroll before correcting the header offset.
        if (!userInteracted && performance.getEntriesByType('navigation')[0] && performance.getEntriesByType('navigation')[0].type === 'navigate') {
            requestAnimationFrame(function () {
                requestAnimationFrame(function () { if (!userInteracted) { landHash(); } });
            });
        }
    }
    if (document.readyState === 'complete') { ready(); } else { window.addEventListener('load', ready, {once: true}); }
    if (document.fonts) { document.fonts.ready.then(function () { scheduleMeasure(); if (!userInteracted) { ready(); } }); }
}());
