import api from './apiConfig';

export const fetchCategories = () => {
  return api.get('/categories');
};
