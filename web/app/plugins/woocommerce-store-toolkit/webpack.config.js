const defaultConfig = require('@wordpress/scripts/config/webpack.config');
module.exports = {
  ...defaultConfig,
  entry: {
    'empty-cart-block': './js/empty-cart-block.js',
    'empty-cart-frontend': './js/empty-cart-frontend.js',
    'place-order-button-frontend': './js/place-order-button-frontend.js',
  },
  output: {
    filename: '[name].js',
    path: __dirname + '/dist',
  },
};
