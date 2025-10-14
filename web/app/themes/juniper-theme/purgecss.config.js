module.exports = {
	// Content files to scan for used CSS classes
	content: [
		// PHP template files
		'**/*.php',

		// Twig template files
		'views/**/*.twig',
		'templates/**/*.twig',

		// Gutenberg HTML templates
		'templates/**/*.html',
		'parts/**/*.html',
		'patterns/**/*.php',

		// JavaScript files (including blocks)
		'src/js/**/*.js',
		'blocks/**/*.js',

		// ACF block templates
		'blocks/**/functions.php',
	],

	// Safelist - CSS classes that should never be purged
	safelist: {
		// Standard safelist - exact class names
		standard: [
			// WordPress core classes
			/^wp-/,
			/^admin-/,
			/^body/,
			/^post-/,
			/^page-/,
			/^term-/,
			/^logged-in/,
			/^menu-/,
			/^nav-/,
			/^current-/,

			// Bootstrap dynamic classes
			/^active$/,
			/^show$/,
			/^open$/,
			/^collapse$/,
			/^collapsing$/,
			/^fade$/,
			/^modal-backdrop$/,

			// Common JavaScript-added classes
			/^is-/,
			/^has-/,
			/^loading/,
			/^loaded/,
			/^error/,
			/^success/,
		],

		// Deep safelist - includes children (e.g., .modal .modal-dialog)
		deep: [
			// WordPress Gutenberg/Block Editor
			/^acf-/,
			/^block-/,
			/^blocks-/,
			/^gutenberg/,
			/^editor-/,
			/^wp-block/,
			/^align/,

			// Bootstrap components with children
			/^modal/,
			/^dropdown/,
			/^navbar/,
			/^carousel/,
			/^tooltip/,
			/^popover/,

			// Third-party libraries
			/^swiper/,
			/^barba/,
			/^gsap/,
			/^litepicker/,
		],

		// Greedy safelist - matches attributes (e.g., [data-*], [aria-*])
		greedy: [
			/data-/,
			/aria-/,
		],
	},

	// Default extractor for HTML-like files
	defaultExtractor: content => content.match(/[\w-/:]+(?<!:)/g) || [],

	// Custom extractors for specific file types
	extractors: [
		{
			extractor: content => {
				// Extract classes from Twig templates
				// Matches: class="...", class='...', {{ 'class-name' }}
				const classes = [];

				// Standard class attributes
				const classMatches = content.matchAll(/class=["']([^"']+)["']/g);
				for (const match of classMatches) {
					classes.push(...match[1].split(/\s+/));
				}

				// Twig variables and functions
				const twigMatches = content.matchAll(/['"]([\w-]+)['"]/g);
				for (const match of twigMatches) {
					classes.push(match[1]);
				}

				return classes;
			},
			extensions: ['twig'],
		},
		{
			extractor: content => {
				// Extract classes from PHP files
				// Handles WordPress functions like get_body_class(), etc.
				return content.match(/[\w-/:]+(?<!:)/g) || [];
			},
			extensions: ['php'],
		},
		{
			extractor: content => {
				// Extract classes from JavaScript files
				// Handles classList.add(), className = '', etc.
				const classes = [];

				// classList.add/remove/toggle
				const classListMatches = content.matchAll(/classList\.(add|remove|toggle)\(['"]([^'"]+)['"]\)/g);
				for (const match of classListMatches) {
					classes.push(match[2]);
				}

				// className assignments
				const classNameMatches = content.matchAll(/className\s*[=:]\s*['"]([^'"]+)['"]/g);
				for (const match of classNameMatches) {
					classes.push(...match[1].split(/\s+/));
				}

				// All other potential class references
				const stringMatches = content.matchAll(/['"]([a-zA-Z][\w-]*)['"]/g);
				for (const match of stringMatches) {
					if (match[1].includes('-') || match[1].match(/^[a-z]/)) {
						classes.push(match[1]);
					}
				}

				return classes;
			},
			extensions: ['js'],
		},
	],

	// Keep @keyframes, @font-face, CSS variables
	keyframes: true,
	fontFace: true,
	variables: true,

	// Rejected selectors (optional - useful for debugging)
	rejected: false,
	rejectedCss: false,
};
