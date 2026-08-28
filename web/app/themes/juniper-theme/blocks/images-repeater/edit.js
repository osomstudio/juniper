/* global wp */

// eslint-disable-next-line max-len -- shared control's path exceeds line length
import RepeaterControl from '../../src/js/block-editor/controls/RepeaterControl';

const { registerBlockType } = wp.blocks;
const { useBlockProps } = wp.blockEditor;
const { createElement: el } = wp.element;
const { __ } = wp.i18n;

function Edit({ attributes, setAttributes }) {
  const blockProps = useBlockProps({ className: 'images-repeater' });

  return el('div', blockProps, el(RepeaterControl, {
    label: __('Images', 'juniper-theme'),
    value: attributes.images,
    onChange: (images) => setAttributes({ images }),
    fields: [
      { name: 'image', type: 'image', label: __('Image', 'juniper-theme') },
      { name: 'caption', type: 'text', label: __('Caption', 'juniper-theme') },
    ],
  }));
}

registerBlockType('juniper-theme/images-repeater', {
  edit: Edit,
  save: () => null,
});
