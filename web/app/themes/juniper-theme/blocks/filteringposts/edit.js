/* global wp */

const { registerBlockType } = wp.blocks;
const { useBlockProps } = wp.blockEditor;
const ServerSideRender = wp.serverSideRender;
const { createElement: el } = wp.element;

// No editable attributes - the block is a static form, so the editor just
// shows the real PHP-rendered markup instead of a hand-built preview.
function Edit() {
  const blockProps = useBlockProps();

  return el('div', blockProps, el(ServerSideRender, {
    block: 'juniper-theme/filteringposts',
  }));
}

registerBlockType('juniper-theme/filteringposts', {
  edit: Edit,
  save: () => null,
});
