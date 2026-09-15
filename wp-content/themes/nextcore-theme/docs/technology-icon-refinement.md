# Technology icon refinement

## Follow-up: viewport height and border connectors (2026-09-14)

This follow-up supersedes the connector counts and fixed desktop height described below.

- Technology now has a minimum height of `100svh` minus the measured site header and WordPress admin bar. A ResizeObserver updates the offset when those elements resize. Removed the later `45svh` / mobile `auto` height overrides from `layout.css` for Technology only; Stats retains its existing styles.
- Desktop constellation grows with viewport height, accounting for section padding/borders. Short or narrow screens retain enough content height for readable icons and labels, so the section can exceed one viewport rather than clip content. Mobile retains the existing stacked group cards.
- Removed all 24 internal node connectors. There are now exactly six Nextcore-to-group curves and six red dots.
- Each endpoint is calculated on the visible ellipse border using the measured group dimensions. The red dot uses exactly that endpoint, rather than an interpolated point along the curve. Language uses a slightly adjusted border angle to leave the PHP icon/label clear. Resize recalculates geometry; node drag does not move the group attachment.
- Retained hover highlighting, node activation, drag and Escape. Cancelled pending drag animation frames on release to prevent a stale callback from accessing cleared drag state.

QA: 1920×1080, 1440×900, 1200×900, 1024×768, 768×1024, 480×900, 375×812, and 1920×1440, each in light and dark (16 combinations). All have 24 nodes, no internal connectors, no intersecting node/label bounds, and no horizontal overflow. Desktop dot-to-endpoint error is below 0.01px and the normalized ellipse error below 0.001. At 1920×1080 the section is 998px with an 82px header; at 1920×1440 it is 1358px. The minimum-height assertion passes for every case. Mobile connectors remain hidden. Hover highlights one group curve, drag clamps to 32px and releases, activation/Escape pass, and final browser errors/failed requests are empty. JavaScript syntax check passes. Screenshots/results use the existing QA location and script below.

## Icons and renderer

Added 21 local SVGs in `assets/images/technology/`: Drupal, Salesforce, Python, C#, Java, PHP, JavaScript, MySQL, PostgreSQL, SQLite, Flask, .NET, Laravel, React, Vue, AWS, Azure, Google Cloud, Windows, Ubuntu, and CentOS.

Source: [Devicon v2.17.0](https://github.com/devicons/devicon/tree/v2.17.0/icons). The asset directory includes the MIT license and a per-file source manifest. All images are served from the theme; no runtime CDN or emoji is used. Brand colors and SVG aspect ratios are preserved. Flask uses the local SVG as a CSS mask with the theme text color; AWS has a small white backing to keep its dark wordmark readable in both themes.

`inc/technology-icons.php` registers all 24 marks. The template checks local file existence and renders an image inside `.tech-node-icon`, separate from `.tech-node-label`. Sitecore, Apex, and DB2 intentionally use full styled wordmarks because no verified asset was selected for them. Any missing registered file falls back to its full configured label, escaped through WordPress. Decorative images have empty alt text; buttons retain technology/group accessible names. AWS label capitalization is normalized.

## Sizes and layout

- Technology labels: 13px desktop, 12px at 1023px and below; weight 600, line-height 1.2, theme text color. Previously 10px/9px.
- Cluster labels: 15px, weight 600, theme text color; previously 12px muted text.
- Icons: 40px; the wider AWS wordmark uses a 46px container with 3px padding.
- Node circles: 64px desktop, 60px at 1023px and below; previously 58px/50px.
- Constellation height: 720px desktop, 690px at 1023px and below. Node row gap increases from 22px to 30px to accommodate labels.
- Six cluster positions, core, orbital concept, hover, drag, and connector geometry remain intact. Desktop copy retains its 355px column; gap is 28px. At 1200px and below, the copy sits above the constellation to provide room for the larger nodes. Existing mobile cluster cards and hidden connectors remain at 768px and below.

Only Technology code was changed. QA also exposed an existing Escape handler error: clearing the active node before calling `.blur()` dereferenced null. The handler now retains the node reference before clearing it.

## QA results

Local Edge headless, 2026-09-14. Tested 1440, 1200, 1024, 768, 480, and 375px in both light and dark themes (12 combinations).

- All cases render 24 nodes: 21 loaded SVGs and the three intentional wordmarks.
- No intersecting node/label bounding boxes or horizontal document overflow in any case.
- Label sizes are 13px at 1440/1200/1024 and 12px at 768/480/375.
- Screenshots captured for all 12 cases; visual inspection included 1440 light/dark, 768 dark, and 375 light. Icons retain colors, remain sharp and readable, and the Sitecore wordmark stays on one line.
- Desktop retains 30 connector paths. Mobile hides connectors as before.
- Desktop hover highlights two connector paths. A 100px pointer movement clamps to 32px; the connector endpoint exactly matches the dragged node center. Release clears drag displacement and dragging state.
- Activation and Escape clearing pass. No browser exceptions or failed HTTP requests in the final run.
- PHP syntax passes for the template and registry; JavaScript syntax passes for `home.js`. PHP CLI emits the existing duplicate OpenSSL module warning.

Reproducible browser check: `node --experimental-websocket docs/checks/phase6/technology-icons-qa.mjs` from the WordPress root, with a browser debugging endpoint on port 9223. Screenshots and measured results are in `docs/checks/phase6/technology-icons/` under the WordPress root. Layout capture uses reduced motion; the interaction checks use real browser mouse/key events.
