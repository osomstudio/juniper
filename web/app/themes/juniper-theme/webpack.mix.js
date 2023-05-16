const mix = require('laravel-mix');

mix.postCss('src/css/theme.css', 'css', [
	require('tailwindcss'),
	require('postcss-nested')
])
.options({
	processCssUrls: false
});
