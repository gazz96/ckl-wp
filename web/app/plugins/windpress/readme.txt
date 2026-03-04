=== WindPress - Tailwind CSS integration for WordPress ===
Contributors: suabahasa, rosua
Donate link: https://ko-fi.com/Q5Q75XSF7
Tags: tailwind, tailwindcss, tailwind css, block
Requires at least: 6.0
Tested up to: 6.9
Stable tag: 3.2.73
Requires PHP: 7.4
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Integrate Tailwind CSS 3 or 4 into WordPress easily, in seconds. Works well with the block editor, page builders, plugins, themes, and custom code.

== Description ==

### WindPress: the only Tailwind CSS v3 and v4 integration plugin for WordPress.

WindPress is a platform agnostic [Tailwind CSS](https://tailwindcss.com/) integration plugin for WordPress that allows you to use the full power of Tailwind CSS within the WordPress ecosystem.

**Tailwind CSS version**:
- 3.4.17
- 4.1.18

### Features

WindPress is packed full of features designed to streamline your workflow. Some of our favorites are:

* **Dual Tailwind CSS version**: Tailwind CSS `3.x` and `4.x` ready.
* **Plug and play**: Start using Tailwind CSS in WordPress in seconds — no setup is required.
* **Customizable Configuration**: The plugin comes with a default Tailwind CSS configuration, but you can easily customize it to fit your needs.
* **Easy to use**: Simplified and intuitive settings to get you up and running quickly.
* **Lightweight**: The plugin dashboard built on top of WordPress REST API, and a modern JavaScript framework for an instant, responsive user experience. Yet it has a small footprint and won't slow down your site.
* **Blazingly fast**: Cache makes your WordPress site blazing fast. Generate the final optimized CSS file in the browser without server-side tools. None of your data is transferred over the network.

And some specific integrations also include the following features:

* **Autocompletion**: As you type, Tailwind CSS class names will be suggested automatically.
* **Variable Picker**: Easily select Tailwind CSS themes' colors, fonts, and other variables from a panel.
* **HTML to native elements**: Convert Tailwind CSS HTML to native elements in the editor.
* **Sort the classes**: Sort the Tailwind CSS classes on the input field.
* **Hover Preview the classes**: Hover over the classes to see the complete outputted CSS and the preview of the design canvas.
* **Ubiquitous Panel**: A floating panel that allows you to quickly access the WindPress settings from anywhere on the page.

Visit [our website](https://wind.press) for more information.

### Seamless Integration

It's easy to build design with Tailwind CSS thanks to the seamless integration with the most popular visual/page builders:

* [Gutenberg](https://wordpress.org/gutenberg) / Block Editor
* [GreenShift](https://shop.greenshiftwp.com/?from=3679)
* [Kadence WP](https://kadencewp.com)
* [LiveCanvas](https://livecanvas.com/?ref=4008)
* [Timber](https://upstatement.com/timber/)
* [Blockstudio](https://blockstudio.dev/?ref=7) — **Pro**
* [Breakdance](https://breakdance.com/ref/165/) — **Pro**
* [Bricks](https://bricksbuilder.io/) — **Pro**
* [Builderius](https://builderius.io/?referral=afdfca82c8) — **Pro**
* [Etch](https://etchwp.com/) — **Pro**
* [Meta Box Views](https://metabox.sjv.io/OeOeZr) — **Pro**
* [Oxygen 6 / Classic](https://oxygenbuilder.com/ref/12/) — **Pro**
* [WPCodeBox 2](https://wpcodebox.com/?ref=185) — **Pro**

Planned / In Progress

* [Elementor](https://be.elementor.com/visit/?bta=209150&brand=elementor)
* [Divi](https://www.elegantthemes.com/affiliates/idevaffiliate.php?id=47622)
* [Pinegrow](https://pinegrow.com/wordpress)
* [Zion Builder](https://zionbuilder.io/)

Note: The core feature will remain available on all versions, but some integrations may be added or removed from the free version in the future.

### Bring Your Own Integration

WindPress is designed to be easily extensible, so you can build your integrations with Tailwind CSS. The plugin provides a simple API for adding integrations.
Check out our detailed [guide](https://wind.press/docs/integrations/custom-theme) to get started.


= Love WindPress? =
- Give a [5-star review](https://wordpress.org/support/plugin/windpress/reviews/?filter=5/#new-post)
- Purchase the [Pro version](https://wind.press)
- Join our [Facebook Group](https://www.facebook.com/groups/1142662969627943)
- Sponsor us on [GitHub](https://github.com/sponsors/suasgn) or [Ko-fi](https://ko-fi.com/Q5Q75XSF7)

= Credits =
- Image by [Pixel perfect](https://www.flaticon.com/free-icon/wind_727964) on Flaticon

Affiliate Disclosure: This readme.txt may contain affiliate links. If you decide to make a purchase through these links, we may earn a commission at no extra cost to you.

== Screenshots ==

1. The WindPress settings page to choose the Tailwind CSS version.
2. The `tailwind.config.js` file editor, which let adding the Tailwind CSS plugins.
3. The `main.css` file editor, which let adding the custom CSS.
4. The Tailwind CSS class name suggestions feature on the Gutenberg editor.
5. Sort the Tailwind CSS classes on the input field.
6. Hover over the Tailwind CSS class name to see the complete outputted CSS and the preview of the design canvas.
7. The front-end page with the Tailwind CSS classes applied, as was added from the Gutenberg editor.
8. The WindPress settings page to generate the cached CSS file.
9. The WindPress log page to see the compiler logs.

== Frequently Asked Questions ==

= Which version of Tailwind CSS is supported by WindPress? =

WindPress supports both Tailwind CSS versions 3 and 4 and will receive regular updates to support the latest version.

= Is WindPress support the Tailwind CSS plugins? =

Yes, WindPress supports Tailwind CSS plugins. You can add the plugins in the `tailwind.config.js` file editor. The Tailwind CSS plugins will be loaded from the `esm.sh` CDN.

= Do I need an internet connection to use WindPress? =

No, by default, you do not need an internet connection to use WindPress. However, an internet connection is required to load the Tailwind CSS plugins from the CDN.

= What themes is WindPress compatible with? =

WindPress is compatible with any WordPress theme. A small adjustment may be needed for the compiler scanner to detect the used classes in the theme.

== Changelog ==

Note: The Pro version has a version number with one higher minor version than the Free version.

For instance:
Free version 1.**0**.4
Pro version 1.**1**.4

= 3.2.73 - 2025-12-22 =
* **New**: Upgraded to Tailwind CSS v4 (4.1.18 latest)
* **Fix**: [Elementor, Bricks, Oxygen, OxygenClassic, Breakdance, Builderius, Timber, Blockstudio] Scanner now properly supports batch system to handle large datasets
* **Fix**: [Internal] JSON string detection in cache fetch_contents method to properly set content type

= 3.2.72 - 2025-12-08 =
* **Improve**: Test compatibility with WordPress 6.9

= 3.2.71 - 2025-12-08 =
* **Fix**: [Internal] Plugin's bundle (Zip) file are not generated correctly

= 3.2.70 - 2025-12-08 =
* **Fix**: [Internal] Plugin's bundle (Zip) file are not generated correctly

= 3.2.69 - 2025-12-08 =
* **New**: Initial WordPress Abilities API support (experimental)
* **Fix**: [Gutenberg] Generate Cache on Save feature's assets are not loaded correctly
* **Fix**: [Internal] path handling issue on the Windows OS
* **Improve**: [Internal] Replace the old scanner's dependency with `@windpress/oxide-parser` package

= 3.2.68 - 2025-11-20 =
* **New**: [Gutenberg] Added Dark Mode toggle to switch between Light, Dark, and System themes
* **New**: [Gutenberg] Generate Cache on Save feature
* **Improve**: Fix color picker on the Wizard page when the initial color is empty
* **Improve**: Redefined how styles are loaded on the front page based on the selected Performance Mode: Hybrid, Cached, or Compiler.
* **Remove**: Reverted file version history feature introduced in v3.2.65 due to complexity and performance concerns

= 3.2.67 - 2025-11-04 =
* **Improve**: [Gutenberg] Improved the Common Block's HTML to Blocks conversion process for better handling of complex HTML structures (experimental)

= 3.2.66 - 2025-11-04 =
* **New**: [Gutenberg] Introduced "Common Block" — a generic and flexible block that lets you build any HTML element, with import/export support (experimental)

= 3.2.65 - 2025-10-24 =
* **New**: Upgraded to Tailwind CSS v4 (4.1.16 latest)
* **New**: Added file version history feature to the Simple File System (experimental)

= 3.2.64 - 2025-10-18 =
* **New**: Upgraded to Tailwind CSS v4 (4.1.14 latest)
* **New**: [TW4] Source Map support for easier debugging in the browser DevTools (experimental)
* **Fix**: Wizard tree doesn't show the children nodes correctly [#66](https://github.com/wind-press/windpress/issues/66)
* **Fix**: [Bricks] Paste HTML feature keyboard shortcut not working correctly [#64](https://github.com/wind-press/windpress/issues/64) [#67](https://github.com/wind-press/windpress/issues/67) [#68](https://github.com/wind-press/windpress/issues/68)

= 3.2.63 - 2025-09-27 =
* **Fix**: [Breakdance] Integration doesn't load on the recent version of Breakdance (v2.4.1)
* **Fix**: [TW4] IntelliSense was failing to correctly clean up `@apply` directives

= 3.2.62 - 2025-09-13 =
* **Improve**: Improve the File editor performance by register the IntelliSense only once after the editor is initialized

= 3.2.61 - 2025-09-10 =
* **Fix**: [Bricks] Plain Classes: Doesn't work on some Bricks templates types like Archive, Search, Section, etc

= 3.2.60 - 2025-09-09 =
* **New**: Upgraded to Tailwind CSS v4 (4.1.13 latest)
* **New**: [LiveCanvas] Added compatibility with LiveCanvas 4.7
* **New**: Added integrations settings page to manage the integrations features

= 3.2.59 - 2025-09-01 =
* **Fix**: [TW3] Missing `/css/preflight.css` file where the Preflight styles are enabled

= 3.2.58 - 2025-08-19 =
* **New**: Upgraded to Tailwind CSS v4 (4.1.12 latest)
* **Improve**: Restricted the IntelliSense on the Files tab feature to work exclusively with Tailwind CSS v4
* **Fix**: [TW3] Tailwind hover tooltips no longer show px equivalents [#58](https://github.com/wind-press/windpress/issues/58)

= 3.2.56 - 2025-08-13 =
* **Fix**: [TW4] IntelliSense performance issue on the Files tab for the CSS file

= 3.2.55 - 2025-08-13 =
* **New**: [TW4] IntelliSense on the Files tab for the CSS file
* **New**: WP-Rocket exclusion for the WindPress CSS and JavaScript files [#54](https://github.com/wind-press/windpress/issues/54)
* **Improve**: [TW4] Autocompletion feature searches now is faster
* **Fix**: [Bricks] Plain Classes: Sort Classes glitch on the style

= 3.2.54 - 2025-07-24 =
* **Improve**: [Gutenberg] Compiler: The raw block data is now appended on the scanner data to improve the class name detection [#53](https://github.com/wind-press/windpress/issues/53)
* **Improve**: [Wizard] The Wizard's data is now can saved directly without the need to switch the Files tab first
* **Fix**: [Bricks] Plain Classes feature doesn't work correctly with Bricks' templates
* **Fix**: [Bricks] Variables value not registered correctly to the Bricks Global Variables system

= 3.2.53 - 2025-07-16 =
* **New**: [Bricks] Added compatibility with Bricks 2.0
* **Fix**: [Bricks] Plain Classes feature not works correctly with the Components feature

= 3.2.51 - 2025-07-10 =
* **Fix**: The `wizard.css` file is not updated correctly on the syncing process

= 3.2.44 - 2025-07-09 =
* **New**: Upgraded to Tailwind CSS v4 (4.1.11 latest)
* **New**: [TW4] The Wizard feature is now available on the Dashboard page

= 3.2.43 - 2025-06-12 =
* **New**: Upgraded to Tailwind CSS v4 (4.1.10 latest)

= 3.2.42 - 2025-06-06 =
* **Improve**: [Gutenberg] Run the Play Observer / Compiler even if the visual editor is not iframed

= 3.2.41 - 2025-06-04 =
* **Fix**: [Bricks] Variables contain `--` in the value being registered to the Bricks Global Variables system
* **Fix**: Plugin's bundle (Zip) file are not generated correctly

= 3.2.40 - 2025-06-01 =
* **Improve**: [Bricks] The Plain Classes and Variables feature compatibility for version 2.0-beta
* **Improve**: Optimize the bundle (Zip) size of the plugin

= 3.2.39 - 2025-05-29 =
* **New**: Upgraded to Tailwind CSS v4 (4.1.8 latest)
* **Improve**: [TW4] The Play Observer / Compiler performance & stability
* **Improve**: [TW4] Robust `@import` handling

= 3.2.35 - 2025-05-28 =
* **Improve**: [Gutenberg] The Play Observer / Compiler stability
* **Fix**: [TW4] `@import` a CDN stylesheet is not working correctly

= 3.2.34 - 2025-05-24 =
* **New**: [Etch](https://etchwp.com/) integration **[Pro]** (experimental)

= 3.2.33 - 2025-05-20 =
* **New**: Upgraded to Tailwind CSS v4 (4.1.7 latest)
* **New**: Added context menu to the Simple File System explorer (right-click on the file explorer)

= 3.2.32 - 2025-05-11 =
* **New**: Upgraded to Tailwind CSS v4 (4.1.6 latest)
* **Fix**: Unable to add new files to the Simple File System

= 3.2.31 - 2025-05-09 =
* **New**: Upgraded to Tailwind CSS v4 (4.1.5 latest)
* **Improve**: [TW4] The compiler is now logging the candidates it has found to aid in debugging

= 3.2.30 - 2025-04-29 =
* **Improve**: [Gutenberg] Load the The Play Observer / Compiler to the Pattern preview [#40](https://github.com/wind-press/windpress/issues/40)
* **Improve**: [Bricks] The Plain Classes feature compatibility for version 2.0-alpha [#42](https://github.com/wind-press/windpress/issues/42)
* **Fix**: [Bricks] The Plain Classes field is not synchronized with the history (undo/redo) actions [#44](https://github.com/wind-press/windpress/issues/44)

= 3.2.29 - 2025-04-15 =
* **New**: Upgraded to Tailwind CSS v4 (4.1.4 latest)

= 3.2.28 - 2025-04-08 =
* **Improve**: Safari browser compatibility
* **Improve**: [Metabox Views] Scanner now use the rendered data instead of the raw data

= 3.2.27 - 2025-04-07 =
* **Fix**: [TW4] `@source` directive with `jsdelivr` CDN is not working correctly

= 3.2.26 - 2025-04-06 =
* **New**: Upgraded to Tailwind CSS v4 (4.1.3 latest)
* **Improve**: [TW4] Autocompletion feature now supports user-defined classes from the Simple File System data
* **Improve**: Exclude the WindPress files from being processed by the SiteGround Speed Optimizer plugin
* **Fix**: [Gutenberg] Misconfigured integration on the block editor

= 3.2.24 - 2025-04-03 =
* **Fix**: [TW4] Error on generate cache caused by the `@source` directive change in the Tailwind CSS v4 (4.1.1)

= 3.2.23 - 2025-04-02 =
* **New**: Upgraded to Tailwind CSS v4 (4.1.1 latest)
* **Improve**: Add keyboard shortcuts to Generate Cache on the WindPress dashboard page
* **Improve**: [TW3] The Play Observer / Compiler performance & stability

= 3.2.22 - 2025-03-28 =
* **Fix**: Storage issue on the Incremental Generate Cache feature [#34](https://github.com/wind-press/windpress/issues/34)

= 3.2.21 - 2025-03-28 =
* **Improve**: [Experimental] The plugin is now fully translatable. Help us to translate the plugin into your language on [WordPress.org](https://translate.wordpress.org/projects/wp-plugins/windpress)

= 3.2.12 - 2025-03-27 =
* **New**: Upgraded to Tailwind CSS v4 (4.0.17 latest)

= 3.2.11 - 2025-03-27 =
* **New**: Upgraded to Tailwind CSS v4 (4.0.16 latest)

= 3.2.7 - 2025-03-27 =
* **Fix**: Browser compatibility issue with the latest compiler update.
* **Fix**: File on the editor are marked as read-only on Windows OS

= 3.2.5 - 2025-03-27 =
* **New**: Upgraded to Tailwind CSS v4 (4.0.15 latest)
* **New**: [TW4] The compiler now can generating cache on the front-end page. This only available if the "Admin always uses Compiler" setting is enabled.
* **Fix**: [TW4][Breakdance, Bricks, Builderius, LiveCanvas, Oxygen Classic] The "Generate Cache on Save" feature are not available on the previous version

= 3.2.4 - 2025-03-27 =
* **Fix**: [TW4] The local stylesheet import is not resolved correctly

= 3.2.3 - 2025-03-27 =
* **New**: [Oxygen 6](https://oxygenbuilder.com/ref/12/) integration **[Pro]** (experimental)
* **Improve**: [TW4] The Play Observer / Compiler performance & stability
* **Fix**: [TW4][Breakdance] The style are now instantly applied on the editor

= 3.2.2 - 2025-03-27 =
* **New**: Upgraded to Tailwind CSS v4 (4.0.14 latest)
* **New**: Refreshed the WindPress dashboard page design and layout for better user experience. Built with the latest [Nuxt UI](https://ui.nuxt.com/pro?aff=GZ5Zd)
* **Fix**: [TW3] The `tailwind.config.js` file are not properly loaded

= 3.1.35 - 2024-12-19 =
* **New**: Upgraded to Tailwind CSS v4 (4.0.9 latest)
* **Improve**: [TW4] Importing additional CSS files with the `@import` directive are now with the following format: `@import "fetch:https://example.com/path/to/the/file.css";`

= 3.1.34 - 2024-12-19 =
* **Fix**: Generating cache process issue on module resolution in the `main.css` file

= 3.1.33 - 2024-12-19 =
* **Fix**: Generating cache process issue on `@import` directive in the `main.css` file

= 3.1.32 - 2024-12-19 =
* **New**: Upgraded to Tailwind CSS v4 (4.0.8 latest)
* **New**: [WPCodeBox 2](https://wpcodebox.com/) integration **[Pro]**
* **Improve**: Better performance on the generating cache process
* **Improve**: [TW4] Generating the CSS cache will remove unused CSS variables. To always keep it, add them within the `@theme static { }` block in the `main.css` file. Alternatively, you can replace the `@import 'tailwindcss/theme.css' layer(theme);` code to `@import "tailwindcss/theme.css" layer(theme) theme(static);` on your `main.css` file.

= 3.1.31 - 2024-12-19 =
* **Fix**: Simple File System imported data are not loaded correctly

= 3.1.30 - 2024-12-19 =
* **New**: Upgraded to Tailwind CSS v4 (4.0.7 latest)
* **Fix**: [TW4] The `@source` directive is causing error when loaded in the page builders' editor

= 3.1.29 - 2024-12-19 =
* **New**: Upgraded to Tailwind CSS v4 (4.0.6 latest)
* **New**: [TW4] The `@source` directive is now supported but differs from the official Tailwind CSS version. Please refer to [our documentation](https://wind.press/docs/configuration/file-main-css#scanning-additional-sources) for details.

= 3.1.28 - 2024-12-19 =
* **New**: Upgraded to Tailwind CSS v4 (4.0.5 latest)

= 3.1.27 - 2024-12-19 =
* **New**: Simple File System data are now exportable and importable from the WindPress dashboard page

= 3.1.26 - 2024-12-19 =
* **New**: Upgraded to Tailwind CSS v4 (4.0.4 latest)

= 3.1.25 - 2024-12-19 =
* **New**: Upgraded to Tailwind CSS v4 (4.0.3 latest)

= 3.1.24 - 2024-12-19 =
* **New**: Upgraded to Tailwind CSS v4 (4.0.1 latest)

= 3.1.23 - 2024-12-19 =
* **New**: Upgraded to Tailwind CSS v4 (4.0.0 latest)

= 3.1.22 - 2024-12-19 =
* **Fix**: [Gutenberg] The CSS class field autofocusing issue on the block editor
* **Fix**: [Gutenberg, Breakdance, Bricks, Builderius, LiveCanvas, Oxygen] The "Generate Cache on Save" feature doesn't use the selected Tailwind CSS version

= 3.1.21 - 2024-12-19 =
* **New**: Upgraded to Tailwind CSS v4 (4.0.0-beta.6 next)
* **Fix**: [Breakdance] Editor style is mixed with admin-bar style (margin-top)

= 3.1.20 - 2024-12-19 =
* **Improve**: Decouple the Gutenberg-based integrations' scanner
* **Fix**: [Gutenberg, Breakdance, Bricks, Oxygen] The hover preview feature is too late to disappear when the mouse is moved away from the class name

= 3.1.19 - 2024-12-19 =
* **New**: [Blockstudio](https://blockstudio.dev/?ref=7) integration **[Pro]**
* **New**: Upgraded to Tailwind CSS v3 (3.4.16)
* **New**: Upgraded to Tailwind CSS v4 (4.0.0-beta.5 next)

= 3.1.18 - 2024-12-19 =
* **New**: Upgraded to Tailwind CSS v4 (4.0.0-beta.4 next)
* **New**: [Meta Box Views](https://metabox.io/plugins/mb-views/) integration **[Pro]**

= 3.1.17 - 2024-12-19 =
* **New**: The new website and documentation is now live at [wind.press](https://wind.press)
* **New**: Upgraded to Tailwind CSS v4 (4.0.0-beta.2 next)
* **Fix**: Scanned classes names are not unescaped correctly ([#4](https://github.com/wind-press/windpress/issues/4))

= 3.1.16 - 2024-12-19 =
* **New**: Upgraded to Tailwind CSS v4 (4.0.0-beta.1 next)

= 3.1.15 - 2024-12-19 =
* **New**: Upgraded to Tailwind CSS v3 (3.4.15)
* **New**: Upgraded to Tailwind CSS v4 (4.0.0-alpha.34 next)
* **Improve**: Test compatibility with WordPress 6.7
* **Improve**: The [LiveCanvas](https://livecanvas.com/?ref=4008) integration is now available on the Free version
* **Improve**: Tailwind CSS v3 stubs/default content are updated for the upcoming Wizard feature
* **Fix**: [Gutenberg] Missing the WindPress data on the block editor

= 3.1.13 - 2024-12-19 =
* **Fix**: Settings page doesn't loaded

= 3.1.12 - 2024-12-19 =
* **New**: Upgraded to Tailwind CSS v4 (4.0.0-alpha.33 next)
* **Improve**: [Breakdance, Bricks, Builderius, Oxygen] Variable Picker feature is now updated to the latest Tailwind CSS v4 variable names
* **Improve**: Tailwind CSS v3 stubs are updated for the upcoming Wizard feature
* **Fix**: [Bricks] The Variable Picker panel is not showing correctly on the Bricks editor

= 3.1.10 - 2024-12-19 =
* **New**: Upgraded to Tailwind CSS v4 (4.0.0-alpha.32 next)

= 3.1.9 - 2024-12-19 =
* **New**: Upgraded to Tailwind CSS v4 (4.0.0-alpha.30 next)

= 3.1.8 - 2024-12-19 =
* **New**: Upgraded to Tailwind CSS v4 (4.0.0-alpha.29 next)

= 3.1.7 - 2024-12-19 =
* **New**: Upgraded to Tailwind CSS v4 (4.0.0-alpha.28 next)

= 3.1.6 - 2024-12-19 =
* **New**: Upgraded to Tailwind CSS v3 (3.4.14)
* **New**: Upgraded to Tailwind CSS v4 (4.0.0-alpha.27 next)
* **New**: [Bricks] added settings to enable or disable WindPress' features. Right-click the WindPress icon on the Editor's top bar to access the settings.

= 3.1.5 - 2024-12-19 =
* **Improve**: Reduce the number of Play modules loaded on the front-end page for non-admin users
* **Improve**: The Ubiquitous panel is now automatically hidden when outside of the panel is clicked

= 3.1.4 - 2024-12-19 =
* **Fix**: Breakdance integration doesn't work on the editor due to fail to load the required JavaScript files

= 3.1.3 - 2024-12-19 =
* **New**: Upgraded to Tailwind CSS v4 (4.0.0-alpha.26 next)
* **Improve**: Properly handling the local JavaScript modules
* **Improve**: Renamed some action and filter hooks
* **Improve**: Some integrations' features are conditionally loaded based on the supported Tailwind CSS version

= 3.1.1 - 2024-12-19 =
* **New**: Porting the Tailwind CSS v4 specific integration features to the Tailwind CSS v3: Autocompletion, Sort, and Class name to CSS
* **Improve**: The Play Observer regenerates the CSS only if new classes are added to the DOM

= 3.1.0 - 2024-12-19 =
* **New**: Tailwind CSS v3 support has been added
* **New**: Upgraded to Tailwind CSS v3 (3.4.13)
* **Improve**: Disable the preflight styles by default on the new installation
* **Improve**: The CSS and JavaScript files are now deletable by emptying the content
* **Improve**: The `main.css` and `tailwind.config.js` files are now resettable by emptying the content

= 3.0.17 - 2024-12-19 =
* **New**: Upgraded to Tailwind CSS v4 (4.0.0-alpha.25 next)

= 3.0.15 - 2024-12-19 =
* **Improve**: Added the bundled Tailwind CSS version number on the settings page
* **Improve**: Relative path support for the local CSS and JavaScript files

= 3.0.14 - 2024-12-19 =
* **New**: Upgraded to Tailwind CSS v4 (4.0.0-alpha.24 next)
* **New**: A simple local CSS and JavaScript file support to manage the Tailwind CSS customizations
* **Fix**: The Ubiquitous Panel feature issue on the Bricks editor

= 3.0.11 - 2024-12-19 =
* **Improve**: Temporary disable the Ubiquitous Panel feature on the Bricks editor due to causing issue with the integration.

= 3.0.10 - 2024-12-19 =
* **New**: Upgraded to Tailwind CSS v4 (4.0.0-alpha.23 next)
* **New**: Initial support on Tailwind CSS configs loaded from CDN with the `@config` directive
* **Improve**: Internationalization (i18n) support on the admin dashboard
* **Fix**: Some style issues on the admin dashboard

= 3.0.9 - 2024-12-19 =
* **New**: Upgraded to Tailwind CSS v4 (4.0.0-alpha.21 next)
* **New**: Initial support on Tailwind CSS plugins loaded from CDN with the `@plugin` directive
* **Improve**: Internationalization (i18n) support on the admin dashboard

= 3.0.8 - 2024-12-19 =
* **New**: [Gutenberg](https://wordpress.org/gutenberg) integration.
* **New**: [GreenShift](https://shop.greenshiftwp.com/?from=3679) integration.
* **New**: [Kadence WP](https://kadencewp.com) integration.

= 3.0.6 - 2024-12-19 =
* **Improve**: Internationalization (i18n) support

= 3.0.0 - 2024-12-19 =
* **New**: Upgraded to Tailwind CSS v4 (4.0.0-alpha.20 next)
* **New**: [Timber](https://upstatement.com/timber/) integration
* **New**: [Bricks](https://bricksbuilder.io/) integration **[Pro]**
* **New**: [Breakdance](https://breakdance.com/ref/165/) integration **[Pro]**
* **New**: [Builderius](https://builderius.io/?referral=afdfca82c8) integration **[Pro]**
* **New**: [LiveCanvas](https://livecanvas.com/?ref=4008) integration **[Pro]**
* **New**: [Oxygen](https://oxygenbuilder.com/ref/12/) integration **[Pro]**
* **Improve**: Test compatibility with WordPress 6.6

= 1.0.0 - 2024-12-19 =
* **New**: 🐣 Initial release.
* [unreleased]: https://github.com/wind-press/windpress/compare/v3.2.73...HEAD
* [3.2.73]: https://github.com/wind-press/windpress/compare/v3.2.72...v3.2.73
* [3.2.72]: https://github.com/wind-press/windpress/compare/v3.2.71...v3.2.72
* [3.2.71]: https://github.com/wind-press/windpress/compare/v3.2.70...v3.2.71
* [3.2.70]: https://github.com/wind-press/windpress/compare/v3.2.69...v3.2.70
* [3.2.69]: https://github.com/wind-press/windpress/compare/v3.2.68...v3.2.69
* [3.2.68]: https://github.com/wind-press/windpress/compare/v3.2.67...v3.2.68
* [3.2.67]: https://github.com/wind-press/windpress/compare/v3.2.66...v3.2.67
* [3.2.66]: https://github.com/wind-press/windpress/compare/v3.2.65...v3.2.66
* [3.2.65]: https://github.com/wind-press/windpress/compare/v3.2.64...v3.2.65
* [3.2.64]: https://github.com/wind-press/windpress/compare/v3.2.63...v3.2.64
* [3.2.63]: https://github.com/wind-press/windpress/compare/v3.2.62...v3.2.63
* [3.2.62]: https://github.com/wind-press/windpress/compare/v3.2.61...v3.2.62
* [3.2.61]: https://github.com/wind-press/windpress/compare/v3.2.60...v3.2.61
* [3.2.60]: https://github.com/wind-press/windpress/compare/v3.2.59...v3.2.60
* [3.2.59]: https://github.com/wind-press/windpress/compare/v3.2.58...v3.2.59
* [3.2.58]: https://github.com/wind-press/windpress/compare/v3.2.57...v3.2.58
* [3.2.57]: https://github.com/wind-press/windpress/compare/v3.2.56...v3.2.57
* [3.2.56]: https://github.com/wind-press/windpress/compare/v3.2.55...v3.2.56
* [3.2.55]: https://github.com/wind-press/windpress/compare/v3.2.54...v3.2.55
* [3.2.54]: https://github.com/wind-press/windpress/compare/v3.2.53...v3.2.54
* [3.2.53]: https://github.com/wind-press/windpress/compare/v3.2.51...v3.2.53
* [3.2.51]: https://github.com/wind-press/windpress/compare/3.3.44...v3.2.51
* [3.2.44]: https://github.com/wind-press/windpress/compare/3.3.43...3.3.44
* [3.2.43]: https://github.com/wind-press/windpress/compare/3.3.42...3.3.43
* [3.2.42]: https://github.com/wind-press/windpress/compare/3.3.41...3.3.42
* [3.2.41]: https://github.com/wind-press/windpress/compare/3.3.40...3.3.41
* [3.2.40]: https://github.com/wind-press/windpress/compare/3.3.39...3.3.40
* [3.2.39]: https://github.com/wind-press/windpress/compare/3.3.35...3.3.39
* [3.2.35]: https://github.com/wind-press/windpress/compare/3.3.34...3.3.35
* [3.2.34]: https://github.com/wind-press/windpress/compare/3.3.33...3.3.34
* [3.2.33]: https://github.com/wind-press/windpress/compare/3.3.32...3.3.33
* [3.2.32]: https://github.com/wind-press/windpress/compare/3.3.31...3.3.32
* [3.2.31]: https://github.com/wind-press/windpress/compare/3.3.30...3.3.31
* [3.2.30]: https://github.com/wind-press/windpress/compare/3.3.29...3.3.30
* [3.2.29]: https://github.com/wind-press/windpress/compare/3.3.28...3.3.29
* [3.2.28]: https://github.com/wind-press/windpress/compare/3.3.27...3.3.28
* [3.2.27]: https://github.com/wind-press/windpress/compare/3.3.26...3.3.27
* [3.2.26]: https://github.com/wind-press/windpress/compare/3.3.24...3.3.26
* [3.2.24]: https://github.com/wind-press/windpress/compare/3.3.23...3.3.24
* [3.2.23]: https://github.com/wind-press/windpress/compare/3.3.22...3.3.23
* [3.2.22]: https://github.com/wind-press/windpress/compare/3.3.21...3.3.22
* [3.2.21]: https://github.com/wind-press/windpress/compare/3.3.12...3.3.21
* [3.2.12]: https://github.com/wind-press/windpress/compare/3.3.11...3.3.12
* [3.2.11]: https://github.com/wind-press/windpress/compare/3.3.7...3.3.11
* [3.2.7]: https://github.com/wind-press/windpress/compare/3.3.5...3.3.7
* [3.2.5]: https://github.com/wind-press/windpress/compare/3.3.4...3.3.5
* [3.2.4]: https://github.com/wind-press/windpress/compare/3.3.3...3.3.4
* [3.2.3]: https://github.com/wind-press/windpress/compare/3.3.2...3.3.3
* [3.2.2]: https://github.com/wind-press/windpress/compare/3.2.35...3.3.2
* [3.1.35]: https://github.com/wind-press/windpress/compare/3.2.34...3.2.35
* [3.1.34]: https://github.com/wind-press/windpress/compare/3.2.33...3.2.34
* [3.1.33]: https://github.com/wind-press/windpress/compare/3.2.32...3.2.33
* [3.1.32]: https://github.com/wind-press/windpress/compare/3.2.31...3.2.32
* [3.1.31]: https://github.com/wind-press/windpress/compare/3.2.30...3.2.31
* [3.1.30]: https://github.com/wind-press/windpress/compare/3.2.29...3.2.30
* [3.1.29]: https://github.com/wind-press/windpress/compare/3.2.28...3.2.29
* [3.1.28]: https://github.com/wind-press/windpress/compare/3.2.27...3.2.28
* [3.1.27]: https://github.com/wind-press/windpress/compare/3.2.26...3.2.27
* [3.1.26]: https://github.com/wind-press/windpress/compare/3.2.25...3.2.26
* [3.1.25]: https://github.com/wind-press/windpress/compare/3.2.24...3.2.25
* [3.1.24]: https://github.com/wind-press/windpress/compare/3.2.23...3.2.24
* [3.1.23]: https://github.com/wind-press/windpress/compare/3.2.22...3.2.23
* [3.1.22]: https://github.com/wind-press/windpress/compare/3.2.21...3.2.22
* [3.1.21]: https://github.com/wind-press/windpress/compare/3.2.20...3.2.21
* [3.1.20]: https://github.com/wind-press/windpress/compare/3.2.19...3.2.20
* [3.1.19]: https://github.com/wind-press/windpress/compare/3.2.18...3.2.19
* [3.1.18]: https://github.com/wind-press/windpress/compare/3.2.17...3.2.18
* [3.1.17]: https://github.com/wind-press/windpress/compare/3.2.16...3.2.17
* [3.1.16]: https://github.com/wind-press/windpress/compare/3.2.15...3.2.16
* [3.1.15]: https://github.com/wind-press/windpress/compare/3.2.13...3.2.15
* [3.1.13]: https://github.com/wind-press/windpress/compare/3.2.12...3.2.13
* [3.1.12]: https://github.com/wind-press/windpress/compare/3.2.10...3.2.12
* [3.1.10]: https://github.com/wind-press/windpress/compare/3.2.9...3.2.10
* [3.1.9]: https://github.com/wind-press/windpress/compare/3.2.8...3.2.9
* [3.1.8]: https://github.com/wind-press/windpress/compare/3.2.7...3.2.8
* [3.1.7]: https://github.com/wind-press/windpress/compare/3.2.6...3.2.7
* [3.1.6]: https://github.com/wind-press/windpress/compare/3.2.5...3.2.6
* [3.1.5]: https://github.com/wind-press/windpress/compare/3.2.4...3.2.5
* [3.1.4]: https://github.com/wind-press/windpress/compare/3.2.3...3.2.4
* [3.1.3]: https://github.com/wind-press/windpress/compare/3.2.1...3.2.3
* [3.1.1]: https://github.com/wind-press/windpress/compare/3.2.0...3.2.1
* [3.1.0]: https://github.com/wind-press/windpress/compare/3.1.17...3.2.0
* [3.0.17]: https://github.com/wind-press/windpress/compare/3.1.15...3.1.17
* [3.0.15]: https://github.com/wind-press/windpress/compare/3.1.14...3.1.15
* [3.0.14]: https://github.com/wind-press/windpress/compare/3.1.11...3.1.14
* [3.0.11]: https://github.com/wind-press/windpress/compare/3.1.10...3.1.11
* [3.0.10]: https://github.com/wind-press/windpress/compare/3.1.9...3.1.10
* [3.0.9]: https://github.com/wind-press/windpress/compare/3.1.8...3.1.9
* [3.0.8]: https://github.com/wind-press/windpress/compare/3.1.6...3.1.8
* [3.0.6]: https://github.com/wind-press/windpress/compare/3.1.0...3.1.6
* [3.0.0]: https://github.com/wind-press/windpress/compare/1.1.0...3.1.0
* [1.0.0]: https://github.com/wind-press/windpress/releases/tag/v1.0.0