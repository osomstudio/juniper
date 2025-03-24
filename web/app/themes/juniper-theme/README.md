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
You can see an exmaple in the `juniper-theme` in the `patterns` directory in `cta.php` file.

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

## ACF Blocks
ACF Blocks are good for creating template parts, such us Header, Navigation, Footer and other reusable blocks.
Example of adding ACF block:
`<!-- wp:acf/cta {"name":"acf/cta","data":{},"align":"full","mode":"preview"} /-->`

## Development workflow
1. Start from configuration in `theme.json` file.
2. Register block styles and pattern categories.
3. Create Design System template in the `templates` directory.
4. Add blocks to the Design System template.
5. Create template parts in the `parts` directory.
6. Start creating the rest blocks with use of Gutenberg and ACF.
7. Register created blocks in the `patterns` directory.

Source: https://fullsiteediting.com/