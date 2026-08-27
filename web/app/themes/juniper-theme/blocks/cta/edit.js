/* global wp */

const { registerBlockType } = wp.blocks;
const { RichText, useBlockProps } = wp.blockEditor;
const { createElement: el } = wp.element;
const { __ } = wp.i18n;

function Edit({ attributes, setAttributes }) {
  const { heading, text } = attributes;
  const blockProps = useBlockProps({ className: 'cta' });

  return el('div', blockProps, [
    el(RichText, {
      key: 'heading',
      tagName: 'h2',
      className: 'cta__heading',
      placeholder: __('Heading…', 'juniper-theme'),
      value: heading,
      allowedFormats: [],
      onChange: (value) => setAttributes({ heading: value }),
    }),
    el(RichText, {
      key: 'text',
      tagName: 'div',
      className: 'cta__text',
      placeholder: __('Text…', 'juniper-theme'),
      value: text,
      onChange: (value) => setAttributes({ text: value }),
    }),
  ]);
}

registerBlockType('juniper-theme/cta', {
  edit: Edit,
  save: () => null,
});
