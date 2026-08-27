/* global wp */

import ImagePickerControl from './ImagePickerControl';

const {
  Button, TextControl, TextareaControl, Card, CardBody, Icon,
} = wp.components;
const { __ } = wp.i18n;
const { createElement: el, cloneElement } = wp.element;

// Field type -> renderer. Add an entry here to support a new field
// type in every RepeaterControl usage across the theme.
const FIELD_RENDERERS = {
  text: (field, value, onChange) => el(TextControl, {
    label: field.label,
    value: value || '',
    onChange,
  }),
  textarea: (field, value, onChange) => el(TextareaControl, {
    label: field.label,
    value: value || '',
    onChange,
  }),
  image: (field, value, onChange) => el(ImagePickerControl, {
    label: field.label,
    value: value || null,
    onChange,
  }),
};

function emptyRow(fields) {
  return fields.reduce((row, field) => ({
    ...row,
    [field.name]: field.default !== undefined ? field.default : '',
  }), {});
}

function renderField(field, row, onRowChange) {
  const renderer = FIELD_RENDERERS[field.type];

  if (!renderer) {
    throw new Error(`RepeaterControl: unknown field type "${field.type}"`);
  }

  const control = renderer(
    field,
    row[field.name],
    (value) => onRowChange({ ...row, [field.name]: value }),
  );

  return cloneElement(control, { key: field.name });
}

/**
 * Generic schema-driven repeater, replacing the ACF "Repeater" field.
 *
 * Example:
 *   el(RepeaterControl, {
 *     label: __('Slides', 'juniper-theme'),
 *     value: attributes.slides,
 *     onChange: (slides) => setAttributes({ slides }),
 *     fields: [
 *       { name: 'title', type: 'text', label: __('Title', 'juniper-theme') },
 *       { name: 'image', type: 'image', label: __('Image', 'juniper-theme') },
 *     ],
 *     minRows: 1,
 *     maxRows: 6,
 *   })
 *
 * @param {Object}   props
 * @param {string}   [props.label]
 * @param {Object[]} props.value    Array of row objects.
 * @param {Function} props.onChange
 * @param {Object[]} props.fields   Field schema: { name, type, label, default }
 *                                  (see FIELD_RENDERERS for supported types).
 * @param {number}   [props.minRows]
 * @param {?number}  [props.maxRows]
 */
export default function RepeaterControl({
  label, value, onChange, fields, minRows = 0, maxRows = null,
}) {
  const rows = Array.isArray(value) ? value : [];
  const atMax = maxRows !== null && rows.length >= maxRows;
  const atMin = rows.length <= minRows;

  const updateRow = (index, row) => {
    const next = [...rows];
    next[index] = row;
    onChange(next);
  };

  const addRow = () => {
    if (atMax) {
      return;
    }

    onChange([...rows, emptyRow(fields)]);
  };

  const removeRow = (index) => {
    if (atMin) {
      return;
    }

    onChange(rows.filter((_, rowIndex) => rowIndex !== index));
  };

  const moveRow = (index, offset) => {
    const target = index + offset;

    if (target < 0 || target >= rows.length) {
      return;
    }

    const next = [...rows];
    [next[index], next[target]] = [next[target], next[index]];
    onChange(next);
  };

  const renderRowField = (field, row, index) => renderField(
    field,
    row,
    (next) => updateRow(index, next),
  );

  const renderRow = (row, index) => el(Card, {
    key: index,
    className: 'juniper-repeater__row',
  }, el(CardBody, {}, [
    ...fields.map((field) => renderRowField(field, row, index)),
    el('div', {
      key: 'row-controls',
      className: 'juniper-repeater__row-controls',
    }, [
      el(Button, {
        key: 'up',
        icon: el(Icon, { icon: 'arrow-up-alt2' }),
        label: __('Move up', 'juniper-theme'),
        onClick: () => moveRow(index, -1),
        disabled: index === 0,
      }),
      el(Button, {
        key: 'down',
        icon: el(Icon, { icon: 'arrow-down-alt2' }),
        label: __('Move down', 'juniper-theme'),
        onClick: () => moveRow(index, 1),
        disabled: index === rows.length - 1,
      }),
      el(Button, {
        key: 'remove',
        icon: el(Icon, { icon: 'trash' }),
        label: __('Remove row', 'juniper-theme'),
        isDestructive: true,
        onClick: () => removeRow(index),
        disabled: atMin,
      }),
    ]),
  ]));

  const heading = label
    ? el('h3', { key: 'label', className: 'juniper-repeater__label' }, label)
    : null;

  return el('div', { className: 'juniper-repeater' }, [
    heading,
    el('div', {
      key: 'rows',
      className: 'juniper-repeater__rows',
    }, rows.map(renderRow)),
    el(Button, {
      key: 'add',
      variant: 'secondary',
      onClick: addRow,
      disabled: atMax,
    }, __('Add row', 'juniper-theme')),
  ]);
}
