import api from './apiConfig';

// User profile APIs
export const getUserProfile = () => {
  return api.get('/user/profile');
};

export const updateProfile = (profileData) => {
  return api.put('/user/profile', profileData);
};

export const changePassword = (passwordData) => {
  return api.put('/user/change-password', passwordData);
};

export const uploadAvatar = (file) => {
  const formData = new FormData();
  formData.append('avatar', file);
  return api.post('/user/avatar', formData, {
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  });
};

// Notification APIs
export const getNotifications = (page = 1) => {
  return api.get('/notifications', { params: { page } });
};

export const getUnreadNotifications = () => {
  return api.get('/notifications/unread');
};

export const markNotificationAsRead = (id) => {
  return api.patch(`/notifications/${id}/read`);
};

export const markAllNotificationsAsRead = () => {
  return api.patch('/notifications/mark-all-read');
};

// Favorite APIs
export const getFavorites = (page = 1) => {
  return api.get('/favorites', { params: { page } });
};

export const addToFavorites = (productId) => {
  return api.post('/favorites', { product_id: productId });
};

export const removeFromFavorites = (productId) => {
  return api.delete(`/favorites/${productId}`);
};

// Review APIs
export const getProductReviews = (productId, page = 1) => {
  return api.get(`/products/${productId}/reviews`, { params: { page } });
};

export const createReview = (productId, reviewData) => {
  return api.post(`/products/${productId}/reviews`, reviewData);
};

export const updateReview = (reviewId, reviewData) => {
  return api.put(`/reviews/${reviewId}`, reviewData);
};

export const deleteReview = (reviewId) => {
  return api.delete(`/reviews/${reviewId}`);
};