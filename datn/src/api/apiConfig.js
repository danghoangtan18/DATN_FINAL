import axios from 'axios';
import NotificationService from '../services/NotificationService';

// Tạo instance axios với cấu hình cơ bản
const api = axios.create({
  baseURL: process.env.REACT_APP_API_BASE_URL || 'http://localhost:8000/api',
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json',
  }
});

// Request interceptor - Thêm token vào header
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Response interceptor - Xử lý response và notification
api.interceptors.response.use(
  (response) => {
    const notificationService = NotificationService.getInstance();
    const config = response.config;
    
    // Xử lý notification dựa vào URL và method
    if (config.method === 'post') {
      if (config.url.includes('/orders')) {
        notificationService.onOrderCreated(response.data);
      } else if (config.url.includes('/court-bookings')) {
        notificationService.onBookingCreated(response.data);
      } else if (config.url.includes('/profile')) {
        notificationService.onProfileUpdated();
      }
    }
    
    if (config.method === 'put' || config.method === 'patch') {
      if (config.url.includes('/orders') && config.url.includes('/status')) {
        notificationService.onOrderStatusChanged(response.data);
      } else if (config.url.includes('/court-bookings') && config.url.includes('/status')) {
        notificationService.onBookingStatusChanged(response.data);
      } else if (config.url.includes('/profile')) {
        notificationService.onProfileUpdated();
      }
    }

    // Kiểm tra nếu response có thông báo đặc biệt
    if (response.data && response.data.notification) {
      notificationService.showNotification(response.data.notification);
    }

    return response;
  },
  (error) => {
    const notificationService = NotificationService.getInstance();
    
    // Xử lý lỗi và hiển thị thông báo
    if (error.response) {
      const { status, data } = error.response;
      
      switch (status) {
        case 401:
          notificationService.showNotification({
            type: 'warning',
            title: 'Phiên đăng nhập hết hạn',
            message: 'Vui lòng đăng nhập lại'
          });
          // Redirect to login
          localStorage.removeItem('token');
          window.location.href = '/login';
          break;
          
        case 403:
          notificationService.showNotification({
            type: 'warning',
            title: 'Không có quyền truy cập',
            message: 'Bạn không có quyền thực hiện hành động này'
          });
          break;
          
        case 404:
          notificationService.showNotification({
            type: 'warning',
            title: 'Không tìm thấy',
            message: 'Tài nguyên không tồn tại'
          });
          break;
          
        case 422:
          // Validation errors
          if (data.errors) {
            const firstError = Object.values(data.errors)[0];
            notificationService.showNotification({
              type: 'warning',
              title: 'Dữ liệu không hợp lệ',
              message: Array.isArray(firstError) ? firstError[0] : firstError
            });
          }
          break;
          
        case 500:
          notificationService.showNotification({
            type: 'warning',
            title: 'Lỗi máy chủ',
            message: 'Có lỗi xảy ra, vui lòng thử lại sau'
          });
          break;
          
        default:
          if (data.message) {
            notificationService.showNotification({
              type: 'warning',
              title: 'Có lỗi xảy ra',
              message: data.message
            });
          }
      }
    } else if (error.request) {
      notificationService.showNotification({
        type: 'warning',
        title: 'Lỗi kết nối',
        message: 'Không thể kết nối đến máy chủ'
      });
    }
    
    return Promise.reject(error);
  }
);

export default api;