# K&P Search Integration with Raffle AI

A WordPress Gutenberg plugin that integrates [Raffle](https://raffle.ai) search into any post or page through Gutenberg blocks and shortcodes. Features include top questions, autocomplete, AI-generated summaries, and full search results. Note that this requires an active subscription with [Raffle](https://raffle.ai) and a published Search Tool.

## Requirements

-   WordPress 6.1+
-   PHP 8.1+
-   Node.js (for development)

## Installation

1. Install the plugin from the [WordPress.org plugin directory](https://wordpress.org/plugins/kp-search-with-raffle) (**Plugins > Add New**, search for "K&P Search"), or upload the `kp-search-with-raffle` folder to `/wp-content/plugins/`.
2. Activate the plugin in **Plugins > Installed Plugins**.
3. Go to **Settings > Raffle Search** and enter your:
    - **Base URL** (default: `https://api.raffle.ai/v2`)
    - **Search UID** (provided by Raffle AI)

## Usage

### Gutenberg blocks

Insert the **KP Raffle Search** or **KP Raffle Search Widget** blocks from the Gutenberg block inserter (category: Widgets). The search block supports wide and full alignment.

### Shortcodes

You can also embed the blocks via shortcodes — useful in classic editor pages, widgets, or PHP templates.

**KP Raffle Search** — renders the full search experience:

```
[raffle_search]
[raffle_search uid="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx"]
```

| Attribute | Default   | Description                                      |
| --------- | --------- | ------------------------------------------------ |
| `uid`     | _(empty)_ | Override the global Search UID for this instance |

**KP Raffle Search Widget** — renders a magnifier icon button:

```
[raffle_search_widget]
[raffle_search_widget mode="link" url="https://example.com/search"]
```

| Attribute | Default   | Description                                                      |
| --------- | --------- | ---------------------------------------------------------------- |
| `mode`    | `overlay` | `overlay` opens the search in a modal; `link` navigates to a URL |
| `url`     | _(empty)_ | Target URL when `mode="link"`                                    |

## Settings

The settings page (**Settings > Raffle Search**) is organised into tabs:

### General

| Setting        | Default                    | Description                                  |
| -------------- | -------------------------- | -------------------------------------------- |
| **Base URL**   | `https://api.raffle.ai/v2` | Raffle API base URL                          |
| **Search UID** | _(empty)_                  | The UID of your published Raffle Search Tool |

### Metadata

Controls which `<meta>` tags the plugin outputs in the `<head>` of posts and pages.

| Setting                   | Default | Description                                                                 |
| ------------------------- | ------- | --------------------------------------------------------------------------- |
| **Add article:tag meta**  | Off     | Output `<meta property="article:tag">` tags from post/page taxonomy terms   |
| **Add raffle:type meta**  | Off     | Output `<meta property="raffle:type">` with the post type (page, post, CPT) |
| **Enable tags for pages** | Off     | Register the `post_tag` taxonomy for pages so tags can be assigned to them  |

### Settings

Controls search result display and filtering.

| Setting                                 | Default   | Description                                                                                                |
| --------------------------------------- | --------- | ---------------------------------------------------------------------------------------------------------- |
| **Show References**                     | On        | Show reference links below AI summaries                                                                    |
| **Hide summary button**                 | Off       | Remove the "AI Summary" button from the search UI                                                          |
| **Excerpt trim length**                 | _(none)_  | Maximum character length for result excerpts except `instant_answer` (leave empty for full length)         |
| **Instant Answers excerpt trim length** | _(none)_  | Maximum character length for excerpts where result type is `instant_answer` (leave empty for full length)  |
| **Hide excerpts for types**             | `pdf`     | Comma-separated list of type slugs whose excerpts should be hidden                                         |
| **Hide tags**                           | _(empty)_ | Exclude or include specific tags from the tag filter bar and result badges (comma-separated list + mode)   |
| **Filter types**                        | _(empty)_ | Exclude or include specific types from the type filter bar and result badges (comma-separated list + mode) |

### Design

Customise the visual appearance of search results and the widget.

| Setting                  | Default   | Description                                                            |
| ------------------------ | --------- | ---------------------------------------------------------------------- |
| **Default result image** | _(empty)_ | Fallback image URL when a result has no thumbnail                      |
| **Result image width**   | `250`     | Width in pixels for result thumbnails                                  |
| **Type badge colors**    | _(theme)_ | Background and text colour for the type badge                          |
| **Tag badge colors**     | _(theme)_ | Background and text colour for the tag badge                           |
| **Widget icon color**    | `#333`    | Icon colour for the search widget (desktop and mobile, set separately) |

## Development

Install dependencies:

```bash
npm install
```

### Available scripts

| Command           | Description                                            |
| ----------------- | ------------------------------------------------------ |
| `npm run build`   | Compile and bundle assets for production into `build/` |
| `npm start`       | Start the development watcher with hot reloading       |
| `npm run format`  | Auto-format source files                               |
| `npm run lint:js` | Lint JavaScript source files                           |

### Project structure

```
kp-search-with-raffle/
├── src/                      # Source files
│   ├── index.js              # Block registration (editor entry point)
│   ├── edit.js               # Block editor component
│   ├── view.js               # Frontend entry point
│   ├── block.json            # Block metadata
│   ├── style.css             # Frontend styles
│   ├── editor.css            # Editor-only styles
│   ├── api/                  # Raffle AI API helpers
│   ├── components/
│   │   ├── RaffleSearch.jsx      # Main search component
│   │   ├── RaffleResultCard.jsx  # Individual result card
│   │   ├── RaffleFiltersCard.jsx # Type & tag filter bar
│   │   ├── Spinner.jsx           # Loading spinner
│   │   └── icons/                # SVG icon components
│   ├── hooks/                # Custom React hooks (e.g. useDebounce)
│   ├── types/                # Result type definitions
│   ├── utils/                # Utility helpers (date, html, getResultType)
│   └── widget/               # KP Raffle Search Widget block
│       ├── block.json
│       ├── index.js
│       ├── edit.js
│       ├── view.js
│       ├── style.css
│       └── editor.css
├── .github/workflows/
│   └── deploy.yml            # Deploys releases to WordPress.org SVN
├── .wordpress-org/           # Plugin directory banners & icons (SVN assets/)
├── build/                    # Compiled output (generated by `npm run build`, not committed)
├── includes/
│   ├── assets/               # Admin CSS/JS and logo.svg
│   ├── admin.php             # Plugin settings page
│   ├── advanced-settings.php # Metadata output helpers
│   └── helpers.php           # Shared PHP helpers
├── .distignore               # Files excluded from the WordPress.org release
├── readme.txt                # WordPress.org readme (Stable tag etc.)
└── kp-search-with-raffle.php # Plugin entry point
```

### Tech stack

-   [@wordpress/scripts](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/) — build tooling (webpack + Babel)
-   [@tanstack/react-query](https://tanstack.com/query) — data fetching & caching
-   [@uidotdev/usehooks](https://usehooks.com) — utility hooks

### External services

This plugin connects to an API provided by Raffle to conduct search queries.
This service is provided by "Raffle": [Security](https://raffle.ai/security), [Privacy policy](https://raffle.ai/privacy-policy).

## Releasing

The plugin is published to the WordPress.org plugin directory:

-   **Public page:** https://wordpress.org/plugins/kp-search-with-raffle
-   **SVN repository:** https://plugins.svn.wordpress.org/kp-search-with-raffle

Deployment is automated by the GitHub Action in [`.github/workflows/deploy.yml`](.github/workflows/deploy.yml), which runs whenever a GitHub release is **published**.

### Release checklist

1. Bump the version number in all four places:
    - `Version:` in the plugin header of `kp-search-with-raffle.php`
    - `KP_SEARCH_WITH_RAFFLE_VERSION` in `kp-search-with-raffle.php`
    - `Stable tag:` in `readme.txt`
    - `version` in `package.json`
2. Commit and push to `main`.
3. Create a GitHub release with a tag matching the version, e.g. `1.1.0` (a leading `v`, as in `v1.1.0`, is also accepted).
4. Follow the run under the repository's **Actions** tab. The new version is usually live on WordPress.org within a few minutes.

### What the workflow does

1. **Verifies the version** — fails if the release tag doesn't match both the plugin header `Version` and the readme `Stable tag`.
2. **Builds the assets** — runs `npm ci` and `npm run build` on Node 22 (`build/` is not committed).
3. **Deploys to SVN** — using [10up/action-wordpress-plugin-deploy](https://github.com/10up/action-wordpress-plugin-deploy), it syncs the plugin to `trunk/`, creates `tags/<version>`, and syncs `.wordpress-org/` to `assets/`. Everything listed in [`.distignore`](.distignore) (source files, `node_modules`, dev config, etc.) is excluded.
4. **Attaches the zip** — uploads the generated plugin zip to the GitHub release.

### Required secrets

Set these under **Settings > Secrets and variables > Actions** in the GitHub repository:

| Secret         | Description                                                                                     |
| -------------- | ----------------------------------------------------------------------------------------------- |
| `SVN_USERNAME` | WordPress.org username with commit access to the plugin (case-sensitive)                        |
| `SVN_PASSWORD` | SVN password generated under **Profile > Account & Security** on WordPress.org (not the login password) |

### Plugin directory assets

Banners and icons shown on WordPress.org live in `.wordpress-org/` and are deployed with each release:

| File                    | Size     |
| ----------------------- | -------- |
| `icon.svg`              | Vector   |
| `icon-128x128.png`      | 128×128  |
| `icon-256x256.png`      | 256×256  |
| `banner-772x250.png`    | 772×250  |
| `banner-1544x500.png`   | 1544×500 |

They are generated from `includes/assets/logo.svg`. Screenshots can be added as `screenshot-1.png`, `screenshot-2.png`, … and referenced in `readme.txt` under `== Screenshots ==`.

## License

GPL-3.0-or-later — see [https://www.gnu.org/licenses/gpl-3.0.html](https://www.gnu.org/licenses/gpl-3.0.html).
