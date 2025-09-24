import axios from 'axios';

export const fetchCategories = () => {
  const API_BASE_URL = process.env.REACT_APP_API_BASE_URL || "http://localhost:8000/api";
  return axios.get(`${API_BASE_URL}/categories`);
};
