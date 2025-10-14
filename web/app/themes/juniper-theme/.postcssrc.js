const purgecss = require('@fullhuman/postcss-purgecss');

module.exports = {
	plugins: [
		purgecss({
			content: [
				'./**/*.php',
				'./**/*.twig',
				'./**/*.html',
				'./**/*.js',
			],
			safelist: {
				standard: [
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
					/^active/,
					/^show/,
					/^open/,
					/^collapse/,
					/^collapsing/,
					/^fade/,
					/^modal/,
					/^dropdown/,
					/^navbar/,
					/^carousel/,
					/^swiper/,
					/^barba/,
					/^gsap/,
				],
				deep: [
					/^acf-/,
					/^block-/,
					/^blocks-/,
					/^gutenberg/,
					/^editor-/,
					/^has-/,
					/^is-/,
					/^wp-block/,
					/^alignfull/,
					/^alignwide/,
					/^alignleft/,
					/^alignright/,
					/^aligncenter/,
				],
				greedy: [
					/data-/,
					/aria-/,
				],
			},
			// Only run PurgeCSS in production builds
			rejected: false,
			variables: true,
		}),
	],
};
