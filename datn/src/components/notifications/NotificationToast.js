import React, { useState, useEffect } from 'react';
import { motion, AnimatePresence } from 'framer-motion';

const NotificationToast = () => {
  const [notifications, setNotifications] = useState([]);

  // Lắng nghe sự kiện thông báo mới
  useEffect(() => {
    const handleNewNotification = (event) => {
      const notification = event.detail;
      
      // Thêm notification vào list
      setNotifications(prev => [...prev, {
        ...notification,
        id: Date.now() + Math.random(),
        timestamp: new Date()
      }]);

      // Tự động xóa sau 5 giây
      setTimeout(() => {
        setNotifications(prev => prev.filter(n => n.id !== notification.id));
      }, 5000);
    };

    window.addEventListener('newNotification', handleNewNotification);
    return () => window.removeEventListener('newNotification', handleNewNotification);
  }, []);

  const removeNotification = (id) => {
    setNotifications(prev => prev.filter(n => n.id !== id));
  };

  const getNotificationIcon = (type) => {
    switch (type) {
      case 'success':
        return { icon: 'fas fa-check-circle', color: '#28a745' };
      case 'order':
        return { icon: 'fas fa-shopping-cart', color: '#007bff' };
      case 'promotion':
        return { icon: 'fas fa-gift', color: '#fd7e14' };
      case 'booking':
        return { icon: 'fas fa-calendar-check', color: '#6f42c1' };
      case 'warning':
        return { icon: 'fas fa-exclamation-triangle', color: '#ffc107' };
      case 'info':
      default:
        return { icon: 'fas fa-info-circle', color: '#17a2b8' };
    }
  };

  return (
    <div style={{
      position: 'fixed',
      top: '20px',
      right: '20px',
      zIndex: 10000,
      maxWidth: '400px'
    }}>
      <AnimatePresence>
        {notifications.map((notification) => {
          const { icon, color } = getNotificationIcon(notification.type);
          
          return (
            <motion.div
              key={notification.id}
              initial={{ opacity: 0, x: 300, scale: 0.9 }}
              animate={{ opacity: 1, x: 0, scale: 1 }}
              exit={{ opacity: 0, x: 300, scale: 0.9 }}
              transition={{ 
                type: "spring",
                stiffness: 300,
                damping: 30 
              }}
              style={{
                background: 'linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%)',
                border: `2px solid ${color}20`,
                borderLeft: `5px solid ${color}`,
                borderRadius: '12px',
                padding: '16px 20px',
                marginBottom: '12px',
                boxShadow: '0 8px 32px rgba(0, 0, 0, 0.12), 0 4px 16px rgba(0, 0, 0, 0.08)',
                backdropFilter: 'blur(10px)',
                cursor: 'pointer',
                position: 'relative',
                overflow: 'hidden'
              }}
              onClick={() => removeNotification(notification.id)}
              whileHover={{ scale: 1.02, y: -2 }}
            >
              {/* Background pattern */}
              <div style={{
                position: 'absolute',
                top: 0,
                right: 0,
                width: '100px',
                height: '100px',
                background: `linear-gradient(135deg, ${color}08, transparent)`,
                borderRadius: '50%',
                transform: 'translate(30px, -30px)'
              }} />
              
              <div style={{ 
                display: 'flex', 
                alignItems: 'flex-start', 
                gap: '12px',
                position: 'relative'
              }}>
                <div style={{
                  minWidth: '40px',
                  height: '40px',
                  borderRadius: '50%',
                  background: `linear-gradient(135deg, ${color}15, ${color}25)`,
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  border: `2px solid ${color}30`
                }}>
                  <i 
                    className={icon} 
                    style={{ 
                      fontSize: '16px', 
                      color: color,
                      fontWeight: 'bold'
                    }}
                  />
                </div>
                
                <div style={{ flex: 1 }}>
                  <div style={{
                    fontWeight: '600',
                    fontSize: '15px',
                    color: '#2c3e50',
                    marginBottom: '4px',
                    lineHeight: 1.3
                  }}>
                    {notification.title}
                  </div>
                  
                  <div style={{
                    fontSize: '13px',
                    color: '#6c757d',
                    lineHeight: 1.4,
                    marginBottom: '6px'
                  }}>
                    {notification.message}
                  </div>
                  
                  <div style={{
                    fontSize: '11px',
                    color: '#adb5bd',
                    fontWeight: '500'
                  }}>
                    {notification.timestamp.toLocaleTimeString('vi-VN', {
                      hour: '2-digit',
                      minute: '2-digit'
                    })}
                  </div>
                </div>
                
                <button
                  onClick={(e) => {
                    e.stopPropagation();
                    removeNotification(notification.id);
                  }}
                  style={{
                    background: 'none',
                    border: 'none',
                    color: '#adb5bd',
                    cursor: 'pointer',
                    padding: '4px',
                    borderRadius: '4px',
                    transition: 'all 0.2s ease'
                  }}
                  onMouseEnter={(e) => {
                    e.target.style.background = '#f8f9fa';
                    e.target.style.color = '#6c757d';
                  }}
                  onMouseLeave={(e) => {
                    e.target.style.background = 'none';
                    e.target.style.color = '#adb5bd';
                  }}
                >
                  <i className="fas fa-times" style={{ fontSize: '12px' }} />
                </button>
              </div>
              
              {/* Progress bar */}
              <motion.div
                initial={{ width: '100%' }}
                animate={{ width: '0%' }}
                transition={{ duration: 5, ease: 'linear' }}
                style={{
                  position: 'absolute',
                  bottom: 0,
                  left: 0,
                  height: '3px',
                  background: `linear-gradient(90deg, ${color}, ${color}80)`,
                  borderRadius: '0 0 12px 12px'
                }}
              />
            </motion.div>
          );
        })}
      </AnimatePresence>
    </div>
  );
};

export default NotificationToast;