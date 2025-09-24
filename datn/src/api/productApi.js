// src/api/productApi.js
import api from "./apiConfig";

// Gọi danh sách sản phẩm
export const fetchProducts = (page = 1, filters = {}) => {
  const params = { page, ...filters };
  return api.get('/products', { params });
};

// Gọi chi tiết sản phẩm theo ID
export const fetchProductById = (id) => {
  return api.get(`/products/${id}`);
};

// Gọi chi tiết sản phẩm theo slug (sửa lại đường dẫn cho đúng với backend)
export const fetchProductBySlug = (slug) => {
  return api.get(`/products/slug/${slug}`);
};



