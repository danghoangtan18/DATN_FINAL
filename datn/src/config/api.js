// API Configuration
export const API_CONFIG = {
  BASE_URL: process.env.REACT_APP_API_URL || 'http://localhost:8000',
  API_BASE_URL: process.env.REACT_APP_API_BASE_URL || 'http://localhost:8000/api',
  ADMIN_URL: process.env.REACT_APP_ADMIN_URL || 'http://127.0.0.1:8000/admin',
  IMAGE_BASE_URL: process.env.REACT_APP_IMAGE_BASE_URL || 'http://localhost:8000',
};

// API Endpoints
export const API_ENDPOINTS = {
  // Products
  PRODUCTS: `${API_CONFIG.API_BASE_URL}/products`,
  PRODUCT_SEARCH: `${API_CONFIG.API_BASE_URL}/products/search`,
  PRODUCT_BY_SLUG: `${API_CONFIG.API_BASE_URL}/products/slug`,
  
  // Categories
  CATEGORIES: `${API_CONFIG.API_BASE_URL}/categories`,
  
  // Orders
  ORDERS: `${API_CONFIG.API_BASE_URL}/orders`,
  CHECK_PURCHASED: `${API_CONFIG.API_BASE_URL}/orders/check-purchased`,
  
  // Comments & Reviews
  PRODUCT_COMMENTS: (productId) => `${API_CONFIG.API_BASE_URL}/products/${productId}/comments`,
  PRODUCT_RATINGS: (productId) => `${API_CONFIG.API_BASE_URL}/products/${productId}/ratings`,
  EXPERT_REVIEWS: `${API_CONFIG.API_BASE_URL}/expert-reviews`,
  
  // User & Auth
  NOTIFICATIONS: `${API_CONFIG.API_BASE_URL}/notifications`,
  NOTIFICATIONS_READ_ALL: `${API_CONFIG.API_BASE_URL}/notifications/read-all`,
  
  // Contact
  CONTACT: `${API_CONFIG.API_BASE_URL}/contact`,
};

// Helper function to get image URL
export const getImageUrl = (imagePath) => {
  if (!imagePath) return '/img/no-image.png';
  if (imagePath.startsWith('http')) return imagePath;
  return `${API_CONFIG.IMAGE_BASE_URL}/${imagePath}`;
};