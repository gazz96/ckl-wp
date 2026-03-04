const { useState } = wp.element;
const { __ } = wp.i18n;
const { useDispatch } = wp.data;
const { subscribe, select } = wp.data;

const EmptyCartButton = () => {
  const [isLoading, setIsLoading] = useState(false);
  const { invalidateResolutionForStore } = useDispatch('wc/store/cart');

  const handleClick = async () => {
    if (isLoading) {
      return;
    }

    setIsLoading(true);

    try {
      const response = await fetch(wc_cart_fragments_params.ajax_url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
        },
        body: new URLSearchParams({
          action: 'woo_st_empty_cart',
          security: woo_st_empty_cart_params.empty_cart_nonce,
        }),
      });

      const result = await response.json();

      if (result.success) {
        invalidateResolutionForStore();
      } else {
        console.error('Error:', result);
        alert(__('Error emptying cart. Please try again.', 'woocommerce-store-toolkit'));
      }
    } catch (error) {
      console.error('Fetch error:', error);
      alert(__('Error emptying cart. Please try again.', 'woocommerce-store-toolkit'));
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <button className='button wc-empty-cart' onClick={handleClick} disabled={isLoading}>
      {isLoading ? __('Emptying Cart...', 'woocommerce-store-toolkit') : __('Empty Cart', 'woocommerce-store-toolkit')}
    </button>
  );
};

const addEmptyCartButton = () => {
  const target = document.querySelector('.wp-block-woocommerce-cart-items-block');
  if (target && target.children.length > 0) {
    wp.element.render(<EmptyCartButton />, target.appendChild(document.createElement('div')));
    return true;
  }
  return false;
};

wp.domReady(() => {
  const unsubscribe = subscribe(() => {
    const cartItemsLoaded = addEmptyCartButton();
    if (cartItemsLoaded) {
      unsubscribe();
    }
  });
});
