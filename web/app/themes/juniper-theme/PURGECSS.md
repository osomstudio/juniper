# PurgeCSS Integration Guide

## Overview
PurgeCSS has been integrated into the Juniper theme build process to remove unused CSS and reduce file sizes. It's only active during production builds to avoid interference during development.

## How It Works

### Development (No PurgeCSS)
```bash
npm run dev
```
- Watches files for changes
- No CSS purging
- Faster builds
- All CSS classes available

### Production (With PurgeCSS)
```bash
npm run build
```
- Sets `NODE_ENV=production`
- Runs PurgeCSS via PostCSS
- Removes unused CSS classes
- Optimized for deployment

### Production Without PurgeCSS (Debugging)
```bash
npm run build:nopurge
```
- Production build without PurgeCSS
- Useful for troubleshooting missing styles

## Configuration Files

### `.postcssrc.js`
Main PostCSS configuration that runs PurgeCSS as a plugin.

**Key settings:**
- Scans `.php`, `.twig`, `.html`, `.js` files
- Extensive safelist for WordPress, Gutenberg, Bootstrap
- Preserves CSS variables, keyframes, font-face

### `purgecss.config.js`
Detailed PurgeCSS configuration with custom extractors.

**Features:**
- Custom extractors for Twig, PHP, and JS files
- Content path definitions
- Safelist patterns (standard, deep, greedy)
- Keeps keyframes, variables, font-face

### `purgecss-safelist.txt`
Reference document listing all protected classes.

## Safelist Categories

### WordPress Core
- `wp-*` - All WordPress classes
- `admin-*` - Admin bar
- `post-*`, `page-*`, `term-*` - Content classes
- `menu-*`, `current-*` - Navigation classes

### Gutenberg/Block Editor
- `wp-block-*` - Block classes
- `acf-block-*` - ACF blocks
- `has-*`, `is-*` - State classes
- `align*` - Alignment classes
- `editor-*` - Editor styles

### Bootstrap Components
- `show`, `active`, `open` - State classes
- `collapse`, `collapsing` - Collapse plugin
- `fade`, `modal-backdrop` - Modal/transition classes
- `dropdown-*` - Dropdown menu

### Third-Party Libraries
- `swiper-*` - Swiper slider
- `barba-*` - Barba.js page transitions
- `gsap-*` - GSAP animations
- `litepicker*` - Date picker

### Dynamic Classes
Classes added via JavaScript that PurgeCSS can't detect:
- `is-visible`, `is-active`, `is-loading`
- `has-error`, `has-success`
- `loading`, `loaded`, `error`, `success`

## Content Scanning

PurgeCSS scans these files for CSS class usage:
- `**/*.php` - All PHP template files
- `**/*.twig` - All Twig templates
- `**/*.html` - Gutenberg templates
- `**/*.js` - JavaScript files including blocks

## Custom Extractors

### Twig Extractor
Handles Twig-specific syntax:
```twig
<div class="my-class">
{{ 'dynamic-class' }}
{% set classes = 'another-class' %}
```

### PHP Extractor
Extracts classes from WordPress functions:
```php
get_body_class()
get_post_class()
```

### JavaScript Extractor
Handles various JS patterns:
```javascript
classList.add('my-class')
className = 'my-class'
element.classList.toggle('active')
```

## Adding Custom Classes to Safelist

If you find classes being removed that shouldn't be:

### Option 1: Edit `.postcssrc.js`
Add patterns to the safelist:
```javascript
safelist: {
  standard: [
    /^my-custom-class$/,  // Exact match
  ],
  deep: [
    /^my-prefix-/,        // Match children
  ],
}
```

### Option 2: Use `!important` Comment
In your SCSS/CSS files:
```scss
/* purgecss ignore */
.my-important-class {
  /* This class will be preserved */
}

/* purgecss start ignore */
.class-one { }
.class-two { }
/* purgecss end ignore */
```

## Testing PurgeCSS

### 1. Compare File Sizes
```bash
# Without PurgeCSS
npm run build:nopurge
ls -lh dist/src/css/_app.css

# With PurgeCSS
npm run build
ls -lh dist/src/css/_app.css
```

### 2. Check for Missing Styles
1. Build with PurgeCSS: `npm run build`
2. Test all pages and interactions
3. Check browser console for errors
4. Verify dynamic states (modals, dropdowns, etc.)

### 3. Debug Missing Classes
If styles are missing:
1. Build without purge: `npm run build:nopurge`
2. Compare the outputs
3. Add missing class patterns to `.postcssrc.js` safelist
4. Rebuild with: `npm run build`

## Expected File Size Reduction

Typical results (varies by project):
- **Bootstrap**: ~200KB → ~30-50KB (75-85% reduction)
- **Custom CSS**: Varies based on usage
- **Overall**: 50-80% reduction common

## Troubleshooting

### Problem: Dynamic classes missing
**Solution:** Add patterns to safelist in `.postcssrc.js`

### Problem: Third-party library styles broken
**Solution:** Add library prefix to `deep` safelist

### Problem: Gutenberg editor styles missing
**Solution:** Check that `editor-*` and `wp-block-*` patterns are safelisted

### Problem: Too aggressive purging
**Solution:** Use `npm run build:nopurge` and compare

### Problem: Not enough purging
**Solution:** Review content paths in `purgecss.config.js`

## Best Practices

1. **Always test after enabling** - Check all pages and components
2. **Use safelist conservatively** - Only add what's needed
3. **Keep safelist documented** - Update `purgecss-safelist.txt`
4. **Monitor file sizes** - Track reduction metrics
5. **Version control check** - Commit after verifying functionality

## Performance Impact

### Build Time
- Development: No impact (`npm run dev`)
- Production: +2-5 seconds for PurgeCSS processing

### Runtime Performance
- ✅ Smaller CSS files
- ✅ Faster page loads
- ✅ Better Core Web Vitals
- ✅ Reduced bandwidth usage

## Maintenance

### When to Update Safelist
- Adding new JavaScript interactions
- Integrating new plugins/libraries
- Adding custom dynamic classes
- After WordPress/theme updates

### Regular Checks
- Monthly: Review file size trends
- Per release: Test all functionality
- After major updates: Verify library classes

## Support

For issues or questions:
1. Check this documentation
2. Review `purgecss-safelist.txt`
3. Test with `npm run build:nopurge`
4. Check PurgeCSS docs: https://purgecss.com/
