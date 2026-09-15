# Technology constellation UI refinement

## Files changed

- `template-parts/home/technology.php`
- `assets/css/base.css`
- `assets/css/light.css`
- `assets/js/home.js`
- `docs/technology-ui-refinement-report.md`

The ACF registry and fallback data remain unchanged in this refinement because they already match the approved 6 groups and 24 technologies.

## Main visual fixes

- Rebalanced the left column and rendered configured heading lines as deliberate blocks to avoid an orphaned final word.
- Added restrained red ambient gradients, a lower-left planetary arc, sparse depth dots, and a soft glow behind the core.
- Increased node size and upgraded the flat surfaces with inner depth, group-specific color, and more legible labels.
- Preserved the deterministic cluster layout and interaction model without changing other homepage sections.

## Icon and logo approach

No external requests or vendor assets were added. Recognizable marks use safe geometric glyphs where suitable (React, Laravel, Windows), while the remaining technologies use readable full or compact wordmarks such as `salesforce`, `sitecore`, `Python`, `MySQL`, `Postgre`, `Azure`, and `G Cloud`. This replaces ambiguous one-letter placeholders while keeping the nodes compact.

## Connector improvements

All 30 straight SVG lines were replaced by quadratic Bézier paths. The six core-to-cluster paths include staggered red signal points with a restrained pulse. Hover, focus, active state, resize, drag, and return-to-anchor all update the curved paths and signals through the existing animation-frame pipeline.

## Center core improvements

The core now uses a constructed three-stroke Nextcore `N`, an inner energy ring, a dashed outer orbit, layered red radial illumination, and a small `NEXTCORE` wordmark. Breathing remains limited to scale 1–1.015.

## Interaction and responsive behavior

- Node hover/focus keeps the 1.07 scale, stronger local glow, related connector emphasis, clearer label, and subtle sibling dimming.
- Cluster hover brightens its ring and center connector while spreading alternating children by 3px.
- Fine-pointer desktop drag remains clamped to a 32px radius and returns in 450ms; mobile drag remains disabled.
- At 768px and below the section uses readable two-column cluster blocks; at 480px and below it uses one column with no horizontal overflow.
- Reduced-motion keeps hover, focus, and active affordances while disabling idle and signal animation.

## Light and dark behavior

Dark mode uses the approved deep background with restrained red haze. Light mode applies the same depth treatment at lower opacity on the existing light surface. Core, text, borders, and node surfaces continue to derive from theme tokens.

## QA results

- PHP syntax, JavaScript syntax, ACF JSON parsing, and `git diff --check` passed.
- Dark-mode layout passed at 1440, 1200, 1024, 768, 480, and 375px; light mode passed at 1440, 768, and 375px.
- All viewports rendered 24 nodes, 6 clusters, 30 curved paths, and 6 red signal points with zero horizontal overflow. Desktop cluster-overlap checks returned zero.
- The heading rendered as exactly `Nền tảng` / `tạo nên khác biệt` without line overflow at every tested width.
- Hover activated two related paths and one signal, scaled the target to approximately 1.07, and dimmed siblings to 0.7.
- A simulated 100px drag was clamped to exactly 32px; the curved path endpoint stayed aligned with the node and returned to `0px, 0px` without a stuck state.
- Reduced-motion removed both node and signal animations at every tested width.
- Click activation and blank-area clearing passed. No browser exceptions, console errors, or failed requests were observed.
- Desktop and mobile screenshots were visually inspected; the final top clusters clear the header and the red planetary accent remains below the main copy.

No database write or production deployment was performed.
