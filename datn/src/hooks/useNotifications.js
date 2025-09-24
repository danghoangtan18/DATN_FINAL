import { useEffect } from 'react';
import NotificationService from '../services/NotificationService';
import { useAuth } from '../context/AuthContext';

export const useNotifications = () => {
  const { user } = useAuth();
  
  useEffect(() => {
    if (user) {
      const notificationService = NotificationService.getInstance();
      notificationService.initialize(user.ID);

      return () => {
        notificationService.disconnect();
      };
    }
  }, [user]);

  return {
    showNotification: (notification) => {
      const notificationService = NotificationService.getInstance();
      notificationService.showNotification(notification);
    },
    
    showSuccess: (message) => {
      const notificationService = NotificationService.getInstance();
      notificationService.showNotification({
        type: 'success',
        title: 'Thành công',
        message: message
      });
    },
    
    showError: (message) => {
      const notificationService = NotificationService.getInstance();
      notificationService.showNotification({
        type: 'warning',
        title: 'Lỗi',
        message: message
      });
    },
    
    showInfo: (message) => {
      const notificationService = NotificationService.getInstance();
      notificationService.showNotification({
        type: 'info',
        title: 'Thông báo',
        message: message
      });
    }
  };
};