# Serif


*   **Market Positioning:** A speed-optimized, lightweight block theme emphasizing editorial layouts, clean hierarchy, and WCAG 2.2 accessibility standards right out of the box.
*   **Custom Plugin Included:** *Serif ReadTime & Font Control*
    *   **Features:** A plugin registering an accessible floating font-adjustment widget (allowing front-end resizing and contrast switching using CSS custom properties) and an automated "minutes to read" calculation block.
*   **Existing Plugin Integrations:** Yoast SEO or RankMath. Integrates theme-specific schema and custom block styles for native breadcrumbs.
*   **Technical Testing Focus:**
    *   **PHPUnit:** Unit test the dynamic reading-time logic (ensuring it handles plain text, shortcodes, and complex Gutenberg blocks correctly, and ignores media attachment metadata).
    *   **Playwright E2E:** Verify the floating font widget functions correctly when toggled, checking that font size updates are dynamically pushed to the local storage, and run automated accessibility scans (`playwright-axe`) to assert contrast ratios under different theme presets.