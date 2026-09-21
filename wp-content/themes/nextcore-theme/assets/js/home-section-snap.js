(function () {
    'use strict';
    var home = document.querySelector('body.nextcore-homepage .nextcore-home');
    if (!home) { return; }
    var root = document.documentElement;
    var header = document.querySelector('.nextcore-site-header');
    var admin = document.getElementById('wpadminbar');
    var nodes = Array.prototype.filter.call(home.children, function (node) { return node.matches('section'); });
    var footer = document.querySelector(
        '.elementor-location-footer, [data-elementor-type="footer"], .site-footer'
    );
    if (footer && !home.contains(footer)) { nodes.push(footer); }
    if (nodes.length < 2) { return; }
    var reduced = matchMedia('(prefers-reduced-motion: reduce)');
    var threshold = 18, tolerance = 20, quietPeriod = 300;
    var scenes = [], offset = 0, viewport = innerHeight, available = innerHeight, maxY = 0;
    var frame = 0, layoutFrame = 0, layoutDirty = false, animation = null;
    var state = 'idle', gesture = null, lastWheel = -Infinity, tailUntil = 0, tailDirection = 0;
    var lastWheelReason = 'waiting', lastWheelTarget = null;
    var reverseDelta = 0, reverseStarted = 0;
    var touch = null, touchIntent = false, settleTimer = 0, userInteracted = false;
    var excluded = 'input, textarea, select, iframe, [contenteditable]:not([contenteditable="false"]), [role="slider"], [role="dialog"], [role="listbox"], dialog, .modal, .lightbox, .dropdown, .sub-menu, .nextcore-mobile-panel, .is-dragging, .map, .map-container, .leaflet-container, .mapboxgl-map, [data-nc-snap-ignore]';

    nodes.forEach(function (node) { node.setAttribute('data-nc-snap-section', ''); });
    root.classList.add('nc-home-snap');
    window.nextcoreHomeSnapStatus = function () {
        return {
            sections: scenes.map(function (scene) { return scene.node.id || scene.node.className; }),
            scrollY: scrollY, viewport: viewport, offset: offset, maxY: maxY,
            state: state, blocked: blocked(), blockingReason: blockingReason(),
            lastWheel: lastWheelReason, target: lastWheelTarget
        };
    };

    // Opt-in QA events only; no frame history or logging in normal operation.
    function trace(reason, extra) {
        if (!root.hasAttribute('data-nc-snap-debug')) { return; }
        document.dispatchEvent(new CustomEvent('nc:snap-debug', {detail: Object.assign({
            time: performance.now(), y: scrollY, state: state, reason: reason,
            target: animation ? animation.destination : null
        }, extra || {})}));
    }
    function blockingReason() {
        if (header && header.classList.contains('menu-open')) { return 'menu-open'; }
        if (document.body.classList.contains('modal-open')) { return 'body.modal-open'; }
        var modals = document.querySelectorAll('dialog[open], [aria-modal="true"]');
        for (var i = 0; i < modals.length; i++) {
            var modal = modals[i];
            if (!modal.getClientRects().length) { continue; }
            for (var node = modal; node; node = node.parentElement) {
                var style = getComputedStyle(node);
                if (node.hidden || node.getAttribute('aria-hidden') === 'true' || node.inert ||
                    style.display === 'none' || style.visibility === 'hidden' || style.visibility === 'collapse' ||
                    Number(style.opacity) === 0) { break; }
            }
            if (!node) { return modal.tagName.toLowerCase(); }
        }
        return null;
    }
    function blocked() { return blockingReason() !== null; }
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
        root.style.setProperty('--nc-section-height', Math.max(1, viewport - offset) + 'px');
        maxY = Math.max(0, root.scrollHeight - viewport);
        available = viewport - offset;
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
    function listenMedia(query, handler) {
        if (query.addEventListener) { query.addEventListener('change', handler); }
        else if (query.addListener) { query.addListener(handler); }
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
        function sceneTarget(scene, index) {
            return index === scenes.length - 1 ? maxY : scene.top - offset;
        }
        // A reversal may return to the boundary behind the current animation position.
        if (reversing) {
            var boundaries = [0, maxY];
            scenes.forEach(function (scene, index) {
                boundaries.push(sceneTarget(scene, index));
            });
            return directionalTarget(boundaries, direction);
        }
        var index = 0;
        // The final short scene may hit the document limit before its top reaches
        // the header. Use its reachable landing position in both directions.
        scenes.forEach(function (scene, i) { if (clampY(scene.top - offset) <= scrollY + tolerance) { index = i; } });
        if (direction > 0) {
            return directionalTarget(scenes.slice(index + 1).map(function (next, relativeIndex) {
                return sceneTarget(next, index + 1 + relativeIndex);
            }), direction);
        }
        var sectionTops = scenes.map(sceneTarget);
        sectionTops.push(0);
        return directionalTarget(sectionTops, direction);
    }
    window.addEventListener('wheel', function (event) {
        userInteracted = true;
        lastWheelReason = 'received';
        lastWheelTarget = event.target instanceof Element ? event.target.tagName : null;
        if (event.ctrlKey || event.metaKey || Math.abs(event.deltaX) > Math.abs(event.deltaY) || !event.deltaY) { lastWheelReason = 'filtered-gesture'; return; }
        if (blocked()) { lastWheelReason = 'blocked'; cancel(); return; }
        var direction = Math.sign(event.deltaY), now = performance.now();
        var fresh = now - lastWheel > quietPeriod;
        var delta = Math.abs(event.deltaY) * (event.deltaMode === 1 ? 16 : event.deltaMode === 2 ? viewport : 1);
        lastWheel = now;
        // An actual move into a control starts a new interaction. Content passing under
        // a stationary pointer during the captured gesture must not cancel the animation.
        var moved = gesture && Math.hypot(event.clientX - gesture.x, event.clientY - gesture.y) > 8;
        if (animation) {
            if (fresh && (!gesture || moved) && ignore(event.target, direction)) { lastWheelReason = 'nested-scroll'; cancel(); return; }
            if (!event.cancelable) { lastWheelReason = 'not-cancelable'; cancel(); return; }
            event.preventDefault();
            lastWheelReason = 'animating';
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
                lastWheelReason = 'tail';
                return;
            }
            state = 'idle'; gesture = null; trace('unlock');
        }
        if (fresh || !gesture || gesture.direction !== direction) {
            if (layoutDirty) { measure(); }
            gesture = {direction: direction, x: event.clientX, y: event.clientY,
                destination: ignore(event.target, direction) ? null : targetFor(direction)};
            state = gesture.destination === null ? 'native' : 'idle';
            lastWheelReason = gesture.destination === null ? 'no-target-or-nested-scroll' : 'target-found';
            trace(state === 'native' ? 'native-gesture' : 'boundary-intent');
        }
        if (state === 'native') {
            if (ignore(event.target, direction)) { lastWheelReason = 'nested-scroll'; return; }
            gesture.destination = targetFor(direction);
            if (gesture.destination === null) { lastWheelReason = 'no-target'; return; }
            state = 'idle';
            trace('boundary-intent');
        }
        if (!event.cancelable) { lastWheelReason = 'not-cancelable'; cancel(); return; }
        event.preventDefault();
        lastWheelReason = 'started';
        smoothScrollToTarget(gesture.destination);
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
            if (!touchIntent || touch || reduced.matches || blocked() || frame) { return; }
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
    listenMedia(reduced, function () { cancel(); scheduleMeasure(); });
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
