# Phase 6 handoff

Updated: 2026-09-14 (Asia/Saigon)

## Current status

Phase 6 is **in progress**. Do not declare PASS yet and do not start Phase 7.

The work from the interrupted session is present in `C:\xampp\htdocs\nextcore-website` at commit `eabf35f4` (`develop`, also local `main`). The repository contained the Phase 6 code and artifacts before this continuation; the current working tree now also contains the handoff and base-URL-aware QA harness edits. The reliable Phase 6 code baseline is `docs/checks/phase6/theme-before/`; comparison against that snapshot shows 28 added/modified theme files.

The local URL changed after the repository move:

- Current working site: `http://localhost/nextcore-website/` (HTTP 200, Nextcore markup present).
- `http://localhost/` now renders an Apache directory listing.
- Core browser QA scripts now use `PHASE6_BASE_URL`, defaulting to `http://localhost/nextcore-website/`.
- Chrome CDP ports 9223 and 9224 are currently unavailable.

Saved QA artifacts from 2026-09-11 document the previous runtime, but the final browser suite still must be rerun against the current base URL before Phase 6 can pass.

### Critical database reset discovered on 2026-09-14

`docs/checks/phase6/current-state-after.json` proves that the database currently connected by `wp-config.php` (`DB_NAME=nextcorevn`, prefix `nxt_`) no longer contains the Phase 5/6 local writes recorded by the 2026-09-11 journals. This is a runtime-state reset outside the repository, not a code revert performed in this continuation.

- ACF options: 62 in the Phase 6 baseline, currently 0.
- Front-page `nc_*` meta rows: 292 in the Phase 6 baseline, currently 0.
- Menus 84/85 and Phase 6 footer menus 86/87 are absent; all menu locations currently point to menu 69 or zero.
- Phase 5 object ID 1813 is absent and 11 existing content records differ from the Phase 6 baseline.
- TranslatePress EN dictionary counts are back at the older state: regular EN 1471 and gettext EN 3549, so the reviewed Phase 6 translations are not present in the current database.

`docs/checks/phase6/data-comparison.json` records these mismatches. Final data/browser QA is blocked until the approved local fixture state is restored or an equivalent database snapshot is supplied.

## Completed work

Evidence-backed implementation already present:

- Standalone native hero, breadcrumbs, sidebar, cards, archive/category/search layouts, post/service layouts and styled 404.
- Service taxonomy uses the main query and supports mixed `post` plus `dich-vu` content.
- `/dich-vu/` remains a Page with a native listing; CPT archive behavior was not changed.
- Three service render paths remain distinct: native, Elementor, and Lark.
- About legacy UX shortcode vocabulary is converted during render without changing stored Elementor data.
- Lark uses scoped curated CSS instead of the old global 133k stylesheet.
- Legacy Elementor bodies retain authored colors while native shell elements support light/dark.
- The homepage EN date corruption was fixed by stripping TranslatePress gettext tracking tags before passing the translated date format to `wp_date()`.
- The approved Blog label translation now matches the HTML-encoded dictionary source.
- Mobile submenu Escape handling was fixed so Escape can bubble to the panel and return focus to the mobile menu button.
- Duplicate native/sidebar category widgets are suppressed because the same taxonomies are already rendered by the native sidebar.
- Service CTA was added to the native box-sizing scope to prevent small-screen overflow.
- Timeline and cached Elementor markup compatibility filters remove duplicate decorative IDs without changing builder data.
- Empty Lark carousel IDs and stale `aria-hidden` attributes are removed at render time.

## Files changed

Compared with `docs/checks/phase6/theme-before/`:

- Modified: `404.php`, `archive.php`, `category.php`, `functions.php`, `home.php`, `page-slide_left_custom.php`, `search.php`, `sidebar.php`, `single.php`, `single-dich-vu.php`, `taxonomy-danh-muc-dich-vu.php`.
- Added: `assets/css/inner.css`, `assets/css/lark.css`, `inc/inner.php`, `template-parts/content/listing.php`.
- Modified: `assets/js/navigation.js`, `inc/compatibility.php`.
- Modified content parts: `template-parts/content/card.php`, `gallery.php`, `page.php`, `post.php`.
- Modified global parts: `template-parts/global/breadcrumbs.php`, `page-title.php`.
- Modified homepage parts: `template-parts/home/about-video.php`, `hero.php`, `partner.php`, `technology.php`.
- Modified service part: `template-parts/service/lark-content.php`.

Phase 6 harnesses and artifacts were added under `docs/checks/phase6/`, including the files explicitly listed in the continuation prompt. `docs/phase6-blog-en-supplement-review.md` records the four user-approved supplemental Blog translations.

## Database writes

Phase 5 local seed (recorded as completed on 2026-09-11, but missing from the current database):

- Journal: `docs/checks/phase5/local-write-20260911-023252.json`.
- Net records documented in `docs/phase5-local-write-report.md`: 63 options, 40 posts (12 attachments and 28 menu items), 568 postmeta rows, two `nav_menu` terms, two term-taxonomy rows and 28 term relationships.
- Phase 5 rerun was idempotent and the temporary ACF attachment-parent side effect was restored.

Phase 6 menu writes (recorded as completed on 2026-09-11, but missing from the current database):

- Journal: `docs/checks/phase6/menu-write.json`.
- `primary` changed from menu 69 to 84; `primary_mobile` changed from 69 to 85.
- `home_primary=84`, `home_mobile=85` retained.
- Footer menus 86 and 87 were created with menu items 1857–1862.
- Original menu 69 was retained.

Phase 6 Admin controlled write (already restored; do not repeat merely for coverage):

- `options_nc_header_contact_label`: `Liên hệ ngay` → `Liên hệ ngay · QA` → restored to `Liên hệ ngay`.
- Frontend change and restoration both passed in `admin-qa-results.json`.

No production database was written. No provider integration was enabled.

## Translation writes

All recorded writes were local TranslatePress dictionary writes. They are absent from the database currently connected by `wp-config.php`. No English text was written into ACF source fields.

1. Main approved import:
   - Journal: `translation-apply-20260911-074224.json`.
   - 156 journaled writes: 110 regular, four block, 42 theme gettext.
   - 13 skips (hero fragment EN-003 plus unchanged proper names/English sources).
   - Zero conflicts.
   - EN dictionary count 1594 → 1627; EN gettext count 3579 → 3593.
   - Hero H1 was translated as one block and preserved the red Nextcore span.

2. Encoded Blog label correction:
   - Journal: `translation-blog-apply-20260911-075423.json`.
   - Row 1561: `Góc nhìn &amp; kiến thức` → `Insights &amp; knowledge`.
   - Count unchanged; verified value recorded.

3. Four supplemental Blog card translations explicitly approved by the user:
   - Review: `docs/phase6-blog-en-supplement-review.md`.
   - Journal: `translation-blog-supplement-20260911-081502.json`.
   - Rows 1065, 1562, 1563 and 1564 updated; all four journal entries have `verified=true`.
   - Zero conflicts; dictionary count unchanged at 1639.

The apply scripts are not general migration tools and must not be rerun casually: `apply-translations.php`, `fix-blog-translation.php`, and `apply-blog-supplement.php`.

## QA already executed

Saved evidence from 2026-09-11:

- `admin-qa-results.json`: authenticated Admin, G01–G10/G14 and G11–G13/G15 present, required validation PASS, file/video control present, video picker opened, repeater/relationships present, Portal relationship empty, controlled save and restore PASS, no JS errors. The first image-picker assertion was a false negative.
- `admin-followup-results.json`: image picker, post-object dropdown and taxonomy dropdown all opened; no errors. This resolves the false negative above.
- `interaction-results.json`: desktop submenu hover/click/ArrowDown/Escape/pointer transfer, search Escape, theme switch, mobile menu/accordion/Escape focus and testimonial controls passed on VI/EN home and representative taxonomy pages. Elementor carousel moved on Đặt sân, Aff ATV and About; Đặt sân lightbox opened; Aff ATV toggle changed `false` → `true`; no recorded JS errors.
- `responsive-results.json`: 212 light/dark cases across 27 routes, including six widths for 13 representative routes; zero overflow, bad-width, broken-image, JS-exception or TranslatePress-marker cases. It reported 12 Aff ATV duplicate-ID cases before the final compatibility fix.
- `responsive-retest-results.json`: 36 cases for `/`, `/en/`, and Aff ATV after the final duplicate-ID fix and supplemental Blog translations; zero overflow, duplicate IDs, broken images, exceptions or marker leaks.
- `final-browser-results.json`: 27 routes, no runtime exceptions, no missing non-404 assets, no integration nodes/requests. This file predates the Aff ATV duplicate-ID fix, so its single Aff ATV duplicate-ID result is superseded only by the focused responsive retest, not by a full final rerun. Its only console 404 is the intentional missing-page request.
- `code-quality-results.json`: 60 PHP files and seven JS files passed; no forbidden Flatsome/runtime localhost/hardcoded EN pattern; four representative templates rendered without optional plugins. This predates the last compatibility edits and must be rerun.
- Visual screenshots exist under `docs/checks/phase6/visual/`. Representative homepage, taxonomy, services, About, Contact, Outsource and Lark screenshots were manually inspected in the interrupted session. A complete documented visual review table is not yet written.

Verified EN homepage evidence in the focused retest includes:

- H1 `Nextcore Software / Joint Stock Company`.
- Date `Founded on June 15, 2022` with no TranslatePress marker leak.
- `Insights & knowledge` Blog label.
- Red Nextcore span retained.
- No overflow or browser exceptions at the tested widths/modes.

## Known failures and limitations

- The Phase 5/6 local fixture and reviewed translations are absent from the current database, although their journals remain in the repository.
- CDP Chrome instances are not running, so browser suites cannot run until new local QA browsers are started.
- `current-state-after.json` and `data-comparison.json` now exist and show material data loss versus both Phase 6 baseline snapshots.
- `final-browser-results.json` is stale with respect to the last duplicate-ID fixes and Blog supplement.
- `code-quality-results.json` is stale with respect to the last changes in `inc/compatibility.php`, `inc/inner.php`, `inner.css`, `navigation.js`, and `single-dich-vu.php`.
- The seven required final Phase 6 reports do not exist yet.
- CONTACT-01 remains intentionally unresolved: the legacy Contact body still shows `384 2/9`; global contact data remains `63 Phan Đăng Lưu`.
- Legacy article titles/bodies outside the approved homepage scope may remain Vietnamese on EN pages. Do not translate them without approval.
- The old attendance rule was clarified by the user: local post ID 1530 is published and may remain usable at `/dich-vu/he-thong-cham-cong-bang-guong-mat/`; production has a private product and must use a different suitable article during production work. Do not force local ID 1530 to 404.
- Production URL comparison was partial in the prior session; do not claim complete production parity.

## Pending work

- Restore the journaled Phase 5 fixture, Phase 6 menus and reviewed translations only after explicit approval for rerunning the guarded local apply routines, or load a supplied matching database snapshot.
- Start isolated Chrome QA instances with CDP, using profiles that do not expose credentials in artifacts. Admin QA does not need another write if the existing authenticated evidence remains sufficient; a read-only current-runtime verification is enough.
- Generate `current-state-after.json` using the read-only baseline harness and run `data-compare.mjs`.
- Rerun PHP lint, JS syntax checks, forbidden-pattern checks and optional-plugin render after the last edits.
- Rerun the full responsive and final-browser suites against `http://localhost/nextcore-website/`.
- Recheck the final EN Blog card title/excerpts, date, H1 DOM, modes and six widths.
- Recheck Aff ATV duplicate SVG IDs, Outsource timeline IDs, Lark empty IDs, sidebar duplicate blocks and service CTA overflow in the full final suite.
- Document visual inspection results, including responsive screenshots for the required route groups.
- Verify pagination behavior where the dataset produces pagination; otherwise record that it could not be exercised with the current content count.
- Create the seven required reports and update this handoff to final status.

## Exact next actions

1. Get explicit approval to restore the missing journaled local data, or receive a matching local database snapshot.
2. Restore in guarded order: Phase 5 setup, verify its idempotent rerun, Phase 6 menu assignments, main reviewed translations, encoded Blog correction, and the four approved Blog supplements. Create new journals for every write.
3. Regenerate `current-state-after.json`, run `data-compare.mjs`, and investigate every remaining non-translation difference.
4. Start frontend CDP Chrome and run `responsive-qa.mjs`, `interaction-qa.mjs`, and `final-browser-qa.mjs` against the current base URL.
5. `code-quality.mjs` was rerun on 2026-09-14: 60 PHP and seven JS files pass, forbidden patterns are empty, and all four optional-plugin representative renders have `no_fatal=true`.
6. Inspect summarized anomalies and representative screenshots; fix only evidence-backed regressions, then rerun affected checks.
7. Write:
   - `docs/phase6-implementation-report.md`
   - `docs/phase6-admin-qa.md`
   - `docs/phase6-translation-write-report.md`
   - `docs/phase6-legacy-page-qa.md`
   - `docs/phase6-regression-fixes.md`
   - `docs/phase6-visual-qa.md`
   - `docs/phase6-open-questions.md`
8. Update this file with final PASS/FAIL evidence and stop for user review before Phase 7.

## Things that must NOT be repeated

- Do not rerun Phase 5 setup/seed or either menu seed script.
- Do not rerun translation apply scripts unless a specific journaled mismatch is first proven.
- Do not activate/switch the theme again; Nextcore was already active in the saved runtime and must only be verified.
- Do not repeat the Admin save/restore test solely to recreate evidence; it already passed and restored the original value.
- Do not overwrite Elementor/builder data, ACF source content, menu 69, slugs, post statuses or business content.
- Do not force local attendance post ID 1530 to 404.
- Do not change CONTACT-01 business content without a separate decision.
- Do not enable Messenger, Zalo, floating call or Umami.
- Do not create Portal content or link the draft object.
- Do not deploy, create a ZIP, or modify production.
- Do not treat old `http://localhost/` artifacts as proof of the current `/nextcore-website/` runtime without rerunning.
