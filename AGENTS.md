# Repository Guidelines

## Project Structure & Module Organization
This is a WordPress installation served from XAMPP's `htdocs` directory. Site customizations live in `wp-content/themes/flatsome-child/`: `functions.php` registers hooks, shortcodes, assets, and AJAX handlers; PHP templates render pages, posts, and services. Custom templates include `single-dich-vu.php` and `taxonomy-danh-muc-dich-vu.php`. CSS, JavaScript, fonts, and images live under the child theme's `assets/` directory.

`wp-content/plugins/` contains bundled plugins, including Advanced Custom Fields Pro. `wp-content/uploads/` stores media. Treat `wp-admin/`, `wp-includes/`, the Flatsome parent theme, and third-party plugin libraries as upstream code; prefer child-theme changes.

## Build, Test, and Development Commands
- Start Apache and MySQL through the XAMPP Control Panel, then open `http://localhost/` with a configured local WordPress database. Existing database site URLs may require local adjustment.
- Run `& C:\xampp\php\php.exe -l wp-content/themes/flatsome-child/functions.php` in PowerShell to check PHP syntax. Repeat for each changed PHP file.
- Run `Get-ChildItem wp-content/themes/flatsome-child -Recurse -Filter *.php | ForEach-Object { & C:\xampp\php\php.exe -l $_.FullName }` to lint all child-theme PHP files.

No root-level build pipeline or project test runner is configured. Assets are served directly; no npm build is required for existing custom CSS or JavaScript.

## Coding Style & Naming Conventions
Match surrounding formatting; use four spaces in new child-theme PHP blocks. Prefer descriptive, prefixed snake_case PHP functions to avoid global collisions. Preserve WordPress template filenames and existing hook identifiers. Enqueue assets through WordPress APIs in `functions.php`; edit custom source files rather than bundled minified libraries. No project-wide formatter or lint configuration was found.

## Testing Guidelines
No dedicated custom-theme test suite or coverage threshold is configured. After syntax checks, manually exercise affected pages, service archives, shortcodes, and galleries at desktop and mobile widths. Check browser console errors and AJAX responses. Verify ACF-dependent pages with representative local content. Record checks and results in the pull request.

## Commit & Pull Request Guidelines
This checkout has no Git metadata, so historical commit conventions cannot be verified. Use concise imperative subjects, such as `Fix service gallery initialization`. Keep changes focused. Pull requests should explain the behavior change, link relevant issues, list validation, and include screenshots for visible changes.

## Security & Configuration
Keep database credentials and authentication salts in local configuration; never include secrets from `wp-config.php` or backup archives in commits. Sanitize input, escape output, and apply nonce and capability checks to state-changing handlers.
