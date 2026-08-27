# theme.json
Start by configuring the design system by modifying the `theme.json` file, where you can define the colour palette, grid width, typography, font sizes, and spacing.
### Example
```json
{
  "color": {
    "defaultDuotone": false,
    "defaultPalette": false,
    "defaultGradients": false,
    "palette": [
      {
        "color": "#164194",
        "name": "Primary",
        "slug": "primary"
      },
      {
        "color": "#e6ebf5",
        "name": "Blueish",
        "slug": "blueish"
      }
    ]
  },
  "layout": {
    "contentSize": "1140px",
    "wideSize": "1280px"
  },
  "typography": {
    "fontSizes": [
      {
        "name": "Small",
        "size": "18px",
        "slug": "small"
      },
      {
        "name": "Medium",
        "size": "20px",
        "slug": "medium"
      },
      {
        "name": "Large",
        "size": "24px",
        "slug": "large"
      }
    ],
    "fontFamilies": [
      {
        "name": "Default",
        "slug": "default",
        "fallbacks": [
          "system-ui",
          "sans-serif"
        ]
      }
    ],
    "lineHeights": [
      {
        "name": "Default",
        "size": 1.5,
        "slug": "default"
      }
    ]
  },
  "spacing": {
    "spacingScale": {
      "steps": 0
    },
    "spacingSizes": [
      {
        "name": "1",
        "size": "1rem",
        "slug": "10"
      },
      {
        "name": "2",
        "size": "min(1.5rem, 2vw)",
        "slug": "20"
      },
      {
        "name": "3",
        "size": "min(2.5rem, 3vw)",
        "slug": "30"
      },
      {
        "name": "4",
        "size": "min(4rem, 5vw)",
        "slug": "40"
      },
      {
        "name": "5",
        "size": "min(6.5rem, 8vw)",
        "slug": "50"
      },
      {
        "name": "6",
        "size": "min(10.5rem, 13vw)",
        "slug": "60"
      }
    ],
    "units": ["%", "px", "em", "rem", "vh", "vw"]
  }
}
```

# Template files
Creating templates in Gutenberg is based on a file system within the templates directory, where filenames follow a structure similar to PHP, such as single.html for single posts or page-home.html for a specific page. Just like in classic WordPress themes, the inheritance and priority mechanism determines which template is used, based on the template hierarchy.

## Template parts
Template parts are reusable blocks of content that can be included in multiple templates. They are stored in the parts directory and can be included in templates using the `<!-- wp:template-part -->` block.
Template parts in Gutenberg can be registered in the theme.json file, allowing you to define reusable sections such as headers, footers, or sidebars. This helps structure the theme and makes it easier to manage global components.
```json
{
  "templateParts": [
    {
      "area": "footer",
      "name": "footer",
      "title": "Footer"
    },
    {
      "area": "header",
      "name": "header",
      "title": "Header"
    },
    {
      "area": "header",
      "name": "top-bar",
      "title": "Top bar"
    }
  ]
}
```

## Block patterns
Block patterns are predefined layouts of blocks that can be inserted into the editor with a single click. They are stored in the patterns directory.
You can see an example in the `juniper-theme` in the `patterns` directory in `cta.php` file.

### Pattern categories
Example:
```php
register_block_pattern_category(
	'cta',
	array( 'label' => __( 'CTA', 'juniper-theme' ) )
);
```

## Block styles
Block styles are predefined styles for blocks that can be applied with a single click. An example of block style registration:
```php
add_action( 'init', 'juniper_register_blocks_styles' );
function juniper_register_blocks_styles() : void {
	register_block_style(
		'core/button',
		array(
			'name'  => 'arrowed',
			'label' => __( 'Arrowed', 'juniper' ),
		)
	);
}
```

## Creating a Block
Blocks are good for creating template parts, such us Header, Navigation, Footer and other reusable blocks.
They are registered natively (`block.json` + `edit.js` + `render.php`, via `register_block_type()`), with no
field-plugin dependency. `blocks/cta/` is a complete reference implementation - copy it as a starting point.

### File structure
Each block lives in `blocks/<slug>/`:
```
blocks/<slug>/
  block.json      Block name, attributes, supports, and the render.php pointer
  edit.js         Editor UI - registerBlockType(name, { edit, save: () => null })
  render.php      Server-side markup, reads $attributes
  functions.php   Registers the block + editor script, enqueues frontend style.css/script.js
  style.scss      Frontend + editor styles (compiled by Parcel to dist/blocks/<slug>/style.css)
  script.js       Frontend behaviour (compiled to dist/blocks/<slug>/script.js)
  ajax.js         Optional, see Juniper\Ajax
```

### 1. block.json
Declare the block name (`juniper-theme/<slug>`), its attributes (the data your `edit.js`/`render.php` will
read and write) and which core supports it needs:
```json
{
    "apiVersion": 3,
    "name": "juniper-theme/cta",
    "title": "CTA",
    "category": "formatting",
    "attributes": {
        "heading": { "type": "string", "default": "" },
        "text": { "type": "string", "default": "" }
    },
    "supports": { "align": ["left", "right", "full"] },
    "render": "file:./render.php"
}
```

### 2. edit.js
The editor is built with the block editor packages WordPress core exposes globally as `wp.*` (there is no
`@wordpress/scripts`/npm build step for these, so import nothing from `@wordpress/*` - just add
`/* global wp */` at the top of the file):
```js
/* global wp */

const { registerBlockType } = wp.blocks;
const { RichText, useBlockProps } = wp.blockEditor;
const { createElement: el } = wp.element;

function Edit({ attributes, setAttributes }) {
  const blockProps = useBlockProps({ className: 'cta' });

  return el('div', blockProps, el(RichText, {
    tagName: 'h2',
    value: attributes.heading,
    onChange: (heading) => setAttributes({ heading }),
  }));
}

registerBlockType('juniper-theme/cta', { edit: Edit, save: () => null });
```
For fields more complex than a plain `RichText`/`TextControl` (repeatable rows, image pickers), use the
theme's reusable editor controls from `src/js/block-editor/controls/`:
- `RepeaterControl` - schema-driven repeater (`fields: [{ name, type, label }]`, supports `text`/`textarea`/`image`)
- `ImagePickerControl` - wraps the native media library, stores `{ id, url, alt }`

### 3. render.php
Read the attributes directly (no `get_field()`), and wrap the markup with `get_block_wrapper_attributes()`
so block supports like `align` keep applying the right classes on the front end:
```php
<?php
$heading = $attributes['heading'] ?? '';
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'cta' ) ); ?>>
	<?php if ( $heading ) : ?>
		<h2 class="cta__heading"><?php echo wp_kses_post( $heading ); ?></h2>
	<?php endif; ?>
</div>
```
For repeater/image attributes, read them safely with `Juniper\Fields\BlockFields::rows()`,
`BlockFields::image()` and `BlockFields::row_value()` instead of accessing the raw array.

### 4. functions.php
Register the editor script (with its `wp-*` dependencies) and the block itself on `init`; the frontend
style/script enqueue on `wp_enqueue_scripts` guarded by `has_block()` stays exactly as before:
```php
add_action( 'init', function () {
	$editor_handle = 'juniper-cta-editor';

	wp_register_script(
		$editor_handle,
		get_template_directory_uri() . '/dist/blocks/cta/edit.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n' ),
		wp_get_theme()->get( 'Version' ),
		true
	);

	register_block_type( __DIR__, array( 'editor_script' => $editor_handle ) );
} );
```

### Adding a block to content
`<!-- wp:juniper-theme/cta {"align":"full"} /-->`

### Scaffolding
`wp add block --name="Reviews"` (see root README) generates the folder above from the `dev/block*.txt`
templates - `block.json`, `edit.js`, `render.php`, `functions.php`, `style.scss`, `script.js`, `ajax.js` -
following this exact pattern, so you only need to fill in the block's own markup/attributes/editor UI.

## Development workflow
1. Start from configuration in `theme.json` file.
2. Register block styles and pattern categories.
3. Create Design System template in the `templates` directory.
4. Add blocks to the Design System template.
5. Create template parts in the `parts` directory.
6. Start creating the rest of the blocks using native Gutenberg block registration, reaching for the theme's
   `RepeaterControl`/`ImagePickerControl` editor controls when a block needs repeatable rows or image fields.
7. Register created blocks in the `patterns` directory.

Source: https://fullsiteediting.com/