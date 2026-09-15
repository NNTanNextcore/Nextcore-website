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
    // Keep server-rendered totals as the fallback; animate each visible metric once.
    if ('IntersectionObserver' in window && !reduced.matches) {
        var counters = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) { return; }
                counters.unobserve(entry.target);
                var element = entry.target;
                var total = Number(element.dataset.statValue);
                var suffix = element.dataset.statSuffix || '';
                var started = performance.now();
                element.setAttribute('aria-label', element.textContent);
                element.textContent = '0' + suffix;
                function tick(now) {
                    var progress = reduced.matches ? 1 : Math.min(1, (now - started) / 1600);
                    var eased = 1 - Math.pow(1 - progress, 3);
                    element.textContent = Math.floor(total * eased) + suffix;
                    if (progress < 1) { requestAnimationFrame(tick); }
                }
                requestAnimationFrame(tick);
            });
        }, {threshold: 0.5});
        home.querySelectorAll('[data-stat-value]').forEach(function (element) {
            counters.observe(element);
        });
    }
    var constellation = home.querySelector('[data-constellation]');
    if (!constellation) { return; }
    var core = constellation.querySelector('.constellation-core');
    var layer = constellation.querySelector('[data-connector-layer]');
    var status = constellation.querySelector('[data-constellation-status]');
    var nodes = Array.prototype.slice.call(constellation.querySelectorAll('[data-tech-node]'));
    var clusters = Array.prototype.slice.call(constellation.querySelectorAll('[data-tech-cluster]'));
    var clusterLines = new WeakMap();
    var svgNamespace = 'http://www.w3.org/2000/svg';
    var activeNode = null;
    var drag = null;
    var suppressClick = new WeakSet();
    var dragDisabled = matchMedia('(max-width: 768px), (pointer: coarse)');

    function createConnector(className, index) {
        var path = document.createElementNS(svgNamespace, 'path');
        path.setAttribute('class', className);
        layer.appendChild(path);
        var signal = document.createElementNS(svgNamespace, 'circle');
        signal.setAttribute('class', 'constellation-signal');
        signal.setAttribute('r', '4');
        signal.style.animationDelay = (-index * .53) + 's';
        layer.appendChild(signal);
        return {path: path, signal: signal};
    }

    clusters.forEach(function (cluster, index) {
        clusterLines.set(cluster, createConnector('connector connector--cluster', index));
    });

    function centerWithin(element, parentRect) {
        var rect = element.getBoundingClientRect();
        return {x: rect.left - parentRect.left + rect.width / 2, y: rect.top - parentRect.top + rect.height / 2};
    }

    function setConnector(connector, start, end, curve) {
        var dx = end.x - start.x;
        var dy = end.y - start.y;
        var length = Math.max(1, Math.hypot(dx, dy));
        var bend = Math.min(curve, length * .16);
        var control = {x: (start.x + end.x) / 2 - dy / length * bend, y: (start.y + end.y) / 2 + dx / length * bend};
        connector.path.setAttribute('d', 'M ' + start.x + ' ' + start.y + ' Q ' + control.x + ' ' + control.y + ' ' + end.x + ' ' + end.y);
        if (connector.signal) {
            connector.signal.setAttribute('cx', end.x);
            connector.signal.setAttribute('cy', end.y);
        }
    }

    // Intersect the ray toward the core with the actual elliptical border.
    function borderToward(element, target, bounds) {
        var rect = element.getBoundingClientRect();
        var center = centerWithin(element, bounds);
        var dx = target.x - center.x;
        var dy = target.y - center.y;
        var radiusX = Math.max(1, (rect.width - 1) / 2);
        var radiusY = Math.max(1, (rect.height - 1) / 2);
        var angle = Math.atan2(dy / radiusY, dx / radiusX);
        return {x: center.x + Math.cos(angle) * radiusX, y: center.y + Math.sin(angle) * radiusY};
    }

    function syncConnectors() {
        if (!core || !layer || innerWidth <= 768) { return; }
        var bounds = constellation.getBoundingClientRect();
        var corePoint = centerWithin(core, bounds);
        layer.ownerSVGElement.setAttribute('viewBox', '0 0 ' + bounds.width + ' ' + bounds.height);
        clusters.forEach(function (cluster) {
            // Begin under the opaque core; its breathing animation cannot expose a gap.
            setConnector(clusterLines.get(cluster), corePoint, borderToward(cluster, corePoint, bounds), 0);
        });
    }

    function highlight(target) {
        layer.querySelectorAll('.is-active').forEach(function (line) { line.classList.remove('is-active'); });
        if (!target) { return; }
        var cluster = target.matches('[data-tech-cluster]') ? target : target.closest('[data-tech-cluster]');
        if (clusterLines.has(cluster)) {
            clusterLines.get(cluster).path.classList.add('is-active');
            clusterLines.get(cluster).signal.classList.add('is-active');
        }
    }

    function activate(node) {
        if (activeNode) { activeNode.classList.remove('is-active'); }
        activeNode = node;
        if (activeNode) {
            activeNode.classList.add('is-active');
            status.textContent = activeNode.dataset.techLabel + ' — ' + activeNode.dataset.techGroup;
        } else {
            status.textContent = '';
        }
        highlight(activeNode);
    }

    constellation.addEventListener('pointerover', function (event) {
        var target = event.target.closest('[data-tech-node], [data-tech-cluster]');
        if (target) { highlight(target); }
    });
    constellation.addEventListener('pointerout', function (event) {
        if (!event.relatedTarget || !constellation.contains(event.relatedTarget)) { highlight(activeNode); return; }
        var target = event.relatedTarget.closest('[data-tech-node], [data-tech-cluster]');
        highlight(target || activeNode);
    });
    constellation.addEventListener('click', function (event) {
        var node = event.target.closest('[data-tech-node]');
        if (node && suppressClick.has(node)) { suppressClick.delete(node); return; }
        activate(node || null);
    });
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && activeNode) {
            var nodeToBlur = activeNode;
            activate(null);
            nodeToBlur.blur();
        }
    });

    nodes.forEach(function (node) {
        node.addEventListener('pointerdown', function (event) {
            if (dragDisabled.matches || event.button !== 0) { return; }
            drag = {node: node, pointerId: event.pointerId, startX: event.clientX, startY: event.clientY, x: 0, y: 0, moved: false};
            node.classList.add('is-dragging');
            node.setPointerCapture(event.pointerId);
            event.preventDefault();
        });
        node.addEventListener('pointermove', function (event) {
            if (!drag || drag.node !== node || drag.pointerId !== event.pointerId) { return; }
            var dx = event.clientX - drag.startX;
            var dy = event.clientY - drag.startY;
            var distance = Math.hypot(dx, dy);
            var scale = distance > 32 ? 32 / distance : 1;
            drag.x = dx * scale;
            drag.y = dy * scale;
            drag.moved = drag.moved || distance > 4;
            cancelAnimationFrame(drag.frame);
            drag.frame = requestAnimationFrame(function () {
                node.style.setProperty('--drag-x', drag.x + 'px');
                node.style.setProperty('--drag-y', drag.y + 'px');
                syncConnectors();
            });
        });
        function release(event) {
            if (!drag || drag.node !== node || drag.pointerId !== event.pointerId) { return; }
            var released = drag;
            cancelAnimationFrame(released.frame);
            drag = null;
            node.classList.remove('is-dragging');
            if (released.moved) { suppressClick.add(node); }
            var start = performance.now();
            var reducedMotion = reduced.matches;
            function returnToAnchor(now) {
                var progress = reducedMotion ? 1 : Math.min(1, (now - start) / 450);
                var eased = 1 - Math.pow(1 - progress, 3);
                node.style.setProperty('--drag-x', released.x * (1 - eased) + 'px');
                node.style.setProperty('--drag-y', released.y * (1 - eased) + 'px');
                syncConnectors();
                if (progress < 1) { requestAnimationFrame(returnToAnchor); }
                else {
                    node.style.removeProperty('--drag-x');
                    node.style.removeProperty('--drag-y');
                }
            }
            requestAnimationFrame(returnToAnchor);
        }
        node.addEventListener('pointerup', release);
        node.addEventListener('pointercancel', release);
    });

    var technology = constellation.closest('.technology');
    var siteHeader = document.querySelector('.nextcore-site-header');
    var adminBar = document.getElementById('wpadminbar');
    function syncViewportHeight() {
        var occupied = [siteHeader, adminBar].reduce(function (height, element) {
            return height + (element ? element.getBoundingClientRect().height : 0);
        }, 0);
        technology.style.setProperty('--technology-chrome-height', occupied + 'px');
    }
    syncViewportHeight();
    if ('ResizeObserver' in window) {
        var geometryObserver = new ResizeObserver(syncConnectors);
        geometryObserver.observe(constellation);
        clusters.forEach(function (cluster) { geometryObserver.observe(cluster); });
        var chromeObserver = new ResizeObserver(syncViewportHeight);
        [siteHeader, adminBar].forEach(function (element) { if (element) { chromeObserver.observe(element); } });
    }
    addEventListener('resize', syncViewportHeight, {passive: true});
    addEventListener('resize', syncConnectors, {passive: true});
    requestAnimationFrame(syncConnectors);
}());
