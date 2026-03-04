function registerFilters() {
  const { registerCheckoutFilters } = window.wc.blocksCheckout;

  // Adjust the place order button label.
  registerCheckoutFilters('woocommerce-store-toolkit', {
    placeOrderButtonLabel: (value, extensions, args) => {
      // Use the place order button text from the server if set.
      return woo_st_place_order_button_params.place_order_button_text ?? value;
    },
  });
}

function waitForBlocksCheckout() {
  if (window.wc && window.wc.blocksCheckout) {
    registerFilters();
  } else {
    setTimeout(waitForBlocksCheckout, 100); // Check every 100ms
  }
}

// Start the polling mechanism
waitForBlocksCheckout();
