import api from './apiConfig';

// Order APIs
export const createOrder = (orderData) => {
  return api.post('/orders', orderData);
};

export const getOrders = (page = 1, status = '') => {
  const params = { page };
  if (status) params.status = status;
  return api.get('/orders', { params });
};

export const getOrderById = (id) => {
  return api.get(`/orders/${id}`);
};

export const updateOrderStatus = (id, status) => {
  return api.patch(`/orders/${id}/status`, { status });
};

export const cancelOrder = (id, reason = '') => {
  return api.patch(`/orders/${id}/cancel`, { reason });
};

// Cart APIs
export const addToCart = (productId, quantity = 1) => {
  return api.post('/cart/add', { 
    product_id: productId, 
    quantity 
  });
};

export const getCart = () => {
  return api.get('/cart');
};

export const updateCartItem = (itemId, quantity) => {
  return api.patch(`/cart/${itemId}`, { quantity });
};

export const removeFromCart = (itemId) => {
  return api.delete(`/cart/${itemId}`);
};

export const clearCart = () => {
  return api.delete('/cart/clear');
};

// Checkout
export const checkout = (checkoutData) => {
  return api.post('/checkout', checkoutData);
};