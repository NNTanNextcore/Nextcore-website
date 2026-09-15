# Technology constellation update

## Files changed

- `template-parts/home/technology.php`: grouped constellation markup, center core, accessible technology buttons, and fixed registry mapping.
- `assets/css/base.css`: desktop constellation, orbital clusters, node states, responsive cards, light/dark tokens, and reduced-motion behavior.
- `assets/js/home.js`: connector geometry, hover/active states, constrained drag, return animation, keyboard clearing, and resize synchronization.
- `assets/css/native.css` and `assets/css/light.css`: removed obsolete marquee overrides.
- `inc/home-data.php`: source-of-truth fallback list for all 24 technologies.
- `acf-json/group_nc_technology.json`: matching choices using the existing field keys and flat repeater schema.

## Groups and technologies

- Platform: Drupal, Salesforce, Sitecore.
- Language: Python, C#, Java, PHP, JavaScript, Apex.
- Database: MySQL, PostgreSQL, SQLite, DB2.
- Framework: Flask, .NET, Laravel, React, Vue.
- Cloud: AWS, Azure, Google Cloud.
- OS: Windows, Ubuntu, CentOS.

No unapproved technology is rendered. Existing configured labels override matching fallback labels. Group membership is mapped in code, so no ACF schema change or database migration is needed.

## Constellation layout and behavior

Desktop uses fixed normalized cluster positions around a centered Nextcore core. A lightweight SVG layer connects the core to each cluster and each cluster to its nodes. Layout positions are deterministic and recalculate after resize.

Nodes float by 2px at staggered 4.6–6.5 second intervals, while the core breathes from scale 1 to 1.015 over 4.8 seconds. Hover and focus increase node scale and glow, clarify labels, soften sibling nodes, and highlight the relevant connectors and orbital ring.

Each desktop fine-pointer node can be dragged up to 32px from its anchor. Movement is clamped to a circular radius, rendered through transforms, and connector endpoints follow via `requestAnimationFrame`. Release returns to the exact anchor over 450ms without persistence or database writes.

Click, Enter, or Space pins one active node. Activating another node replaces it; clicking blank constellation space or pressing Escape clears it.

## Responsive and accessibility

At 768px and below, drag and SVG connectors are disabled and the layout becomes a centered core followed by two-column cluster blocks. At 480px and below, clusters use one column. Tap activation remains available with no horizontal page overflow.

Every node is a native button with an accessible label in the form `Technology — Group`. Focus styles are visible, active changes are announced through a polite live region, and drag is never the only interaction.

Light mode uses theme surfaces and text tokens while preserving restrained technology colors. Reduced-motion disables floating, breathing, and animated return while retaining hover, focus, and active states.

## QA results

- PHP syntax passed for the template and fallback data; JavaScript syntax and ACF JSON parsing passed.
- Browser layout passed at 1440, 1200, 1024, 768, 480, and 375px with 6 clusters, 24 semantic buttons, 30 connector lines, no cluster overlap on desktop, and zero horizontal overflow.
- Dark/light checks passed at 1440, 768, and 375px. Node surfaces and the red core switch correctly with theme tokens.
- Hover produced the expected scale, two highlighted connector segments, and 0.7 sibling opacity.
- A simulated 100px pointer move was clamped to exactly 32px; the connector endpoint matched the dragged node and the node returned to `0px, 0px` after release.
- Click activation, blank-area clearing, and Escape clearing passed. Enter/Space activation is provided by the native button elements.
- Reduced-motion disabled node animation at all six widths. Mobile disabled connectors and drag while retaining button activation.
- No browser exceptions, console errors, failed requests, or stuck dragging state were observed.

No production deployment or database update was performed.
