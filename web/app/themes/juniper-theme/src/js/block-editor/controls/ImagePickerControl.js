/* global wp */

const { MediaUpload, MediaUploadCheck } = wp.blockEditor;
const { Button, BaseControl } = wp.components;
const { __ } = wp.i18n;
const { createElement: el } = wp.element;

/**
 * Reusable image picker control, replacing the ACF "Image" field.
 *
 * Value shape is { id, url, alt } (or null when empty) so render.php
 * never has to hit the database to resolve the attachment.
 *
 * @param {Object}        props
 * @param {string}        props.label
 * @param {?Object}       props.value
 * @param {Function}      props.onChange
 * @param {string}        [props.help]
 */
export default function ImagePickerControl({
  label, value, onChange, help,
}) {
  const hasImage = Boolean(value && value.url);

  const onSelect = (media) => onChange({
    id: media.id,
    url: media.url,
    alt: media.alt || '',
  });

  const renderPreview = () => {
    if (!hasImage) {
      return null;
    }

    return el('img', {
      key: 'preview',
      src: value.url,
      alt: value.alt || '',
      className: 'juniper-image-picker__preview',
    });
  };

  const renderRemoveButton = () => {
    if (!hasImage) {
      return null;
    }

    return el(Button, {
      key: 'remove',
      variant: 'link',
      isDestructive: true,
      onClick: (event) => {
        event.stopPropagation();
        onChange(null);
      },
    }, __('Remove image', 'juniper-theme'));
  };

  const selectLabel = hasImage
    ? __('Replace image', 'juniper-theme')
    : __('Select image', 'juniper-theme');

  const media = el(MediaUpload, {
    onSelect,
    allowedTypes: ['image'],
    value: hasImage ? value.id : null,
    render: ({ open }) => el('div', { className: 'juniper-image-picker' }, [
      renderPreview(),
      el(Button, {
        key: 'select',
        variant: 'secondary',
        onClick: open,
      }, selectLabel),
      renderRemoveButton(),
    ]),
  });

  return el(BaseControl, { label, help }, el(MediaUploadCheck, {}, media));
}
