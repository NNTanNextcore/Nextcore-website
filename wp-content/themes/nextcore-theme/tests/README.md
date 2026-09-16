# Homepage section snap regression checks

The controller is `assets/js/home-section-snap.js`. Layout, ACF fields and enqueue scope are unchanged.

## Run the browser regression suite

Start a dedicated Chrome instance with remote debugging on port 9223 and a separate temporary profile. Do not use a personal browsing profile. With the local WordPress site running, execute from the theme directory:

```powershell
node --check assets/js/home-section-snap.js
node --experimental-websocket tests/home-section-snap.cjs
```

Node 22+ can omit `--experimental-websocket`. `NC_CDP_URL` overrides the debugging endpoint; `NC_SITE_URL` overrides `http://localhost/nextcore-website/`. The suite opens and closes its own tab. It tests the current homepage content at 1440 × 900 with the current 82px desktop header.

Results and per-frame traces are written under the OS temporary directory in `nextcore-snap-qa/`. Failed assertions set a nonzero exit status. The suite requires no npm dependencies.

## Behavior covered

- Wheel intent accumulates to 45 normalized pixels without an initial native jump.
- One-screen transitions take approximately 800ms. An 80px move takes approximately 250ms; a repeated anchor creates no animation.
- Frame positions advance monotonically, without a long stationary tail.
- Reversal at the beginning, middle and end of an animation starts from the actual current position. Small opposite deltas and deltas outside the 160ms confirmation window do not reverse it.
- Content crossing a stationary cursor does not cancel an active transition.
- Native gestures remain native until a new gesture starts at the tall-section boundary.
- Slightly oversized full-page scenes advance in one gesture instead of stopping on a small responsive overflow.
- Continuous upward input remains animated across slightly oversized scenes.
- Vertical wheel input over Technology controls, the testimonial carousel and the About background video still snaps the page.
- Statistics is an independent animated scene after About.
- Continuous input is released after the bounded 180ms tail filter.
- Every logical scene is traversed in both directions, including the short CTA/footer scene clamped by the document bottom.

The optional `data-nc-snap-debug` attribute on the document element enables `nc:snap-debug` events containing timestamps, state, reason, position and target. Frame history is collected by the test only; normal browsing does not store it.

## Additional acceptance checks

Check both languages and themes at 1920×1080, 1440×900, 1366×768, 1024×1366, 768×1024, 430×932, 390×844, 375×812 and 844×390. Exercise actual wheel/touch events, navbar anchors, direct hashes, Back/Forward, reduced motion, menu/dialog states, nested scrolling and Technology dragging.

Browser automation validates logic and movement geometry. Final subjective smoothness still needs a physical mouse; Safari/iOS and Android device momentum cannot be certified by desktop Chrome emulation.
