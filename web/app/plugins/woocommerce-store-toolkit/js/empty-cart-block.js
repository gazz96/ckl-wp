const { registerBlockType } = wp.blocks;
const { createElement: el } = wp.element;
const { __ } = wp.i18n;

registerBlockType('custom/empty-cart', {
  title: __('Empty Cart Button', 'woocommerce-store-toolkit'),
  icon: 'cart',
  category: 'woocommerce',
  edit: () => {
    return el('div', { className: 'empty-cart-button-block' }, __('Empty Cart Button', 'woocommerce-store-toolkit'));
  },
  save: () => {
    return el('div', { className: 'empty-cart-button-block' }, __('Empty Cart Button', 'woocommerce-store-toolkit'));
  },
});
