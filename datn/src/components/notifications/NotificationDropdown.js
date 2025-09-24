import React, { useState, useEffect, useRef, useCallback } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { getUnreadNotifications, markNotificationAsRead } from '../../api/userApi';
import { useNotifications } from '../../hooks/useNotifications';

const NotificationDropdown = ({ userId }) => {
  const [isOpen, setIsOpen] = useState(false);
  const [notifications, setNotifications] = useState([]);
  const [loading, setLoading] = useState(false);
  const [unreadCount, setUnreadCount] = useState(0);
  const dropdownRef = useRef(null);
  const { showError } = useNotifications();

  // Đóng dropdown khi click outside
  useEffect(() => {
    const handleClickOutside = (event) => {
      if (dropdownRef.current && !dropdownRef.current.contains(event.target)) {
        setIsOpen(false);
      }
    };

    document.addEventListener('mousedown', handleClickOutside);
    return () => document.removeEventListener('mousedown', handleClickOutside);
  }, []);

  // Load notifications khi mở dropdown
  const loadNotifications = useCallback(async () => {
    if (!userId) return;
    
    setLoading(true);
    try {
      const response = await getUnreadNotifications();
      setNotifications(response.data || []);
      setUnreadCount(response.data?.length || 0);
    } catch (error) {
      showError('Lỗi khi tải thông báo');
    } finally {
      setLoading(false);
    }
  }, [userId, showError]);

  // Toggle dropdown
  const toggleDropdown = () => {
    setIsOpen(!isOpen);
    if (!isOpen) {
      loadNotifications();
    }
  };

  // Đánh dấu đã đọc
  const handleMarkAsRead = async (notificationId) => {
    try {
      await markNotificationAsRead(notificationId);
      setNotifications(prev => 
        prev.filter(n => n.Notifications_ID !== notificationId)
      );
      setUnreadCount(prev => Math.max(0, prev - 1));
    } catch (error) {
      showError('Lỗi khi đánh dấu thông báo');
    }
  };

  // Format thời gian
  const formatTime = (dateString) => {
    const date = new Date(dateString);
    const now = new Date();
    const diffInMinutes = Math.floor((now - date) / (1000 * 60));
    
    if (diffInMinutes < 1) return 'Vừa xong';
    if (diffInMinutes < 60) return `${diffInMinutes} phút trước`;
    
    const diffInHours = Math.floor(diffInMinutes / 60);
    if (diffInHours < 24) return `${diffInHours} giờ trước`;
    
    const diffInDays = Math.floor(diffInHours / 24);
    return `${diffInDays} ngày trước`;
  };

  // Load notifications định kỳ
  useEffect(() => {
    if (userId) {
      loadNotifications();
      const interval = setInterval(loadNotifications, 30000); // 30 giây
      return () => clearInterval(interval);
    }
  }, [userId, loadNotifications]);

  return (
    <div className="notification-dropdown" ref={dropdownRef} style={{ position: 'relative' }}>
      {/* Bell Icon Button */}
      <button
        onClick={toggleDropdown}
        style={{
          position: 'relative',
          background: 'none',
          border: 'none',
          cursor: 'pointer',
          padding: '8px',
          borderRadius: '50%',
          transition: 'background-color 0.2s ease'
        }}
        onMouseEnter={(e) => e.target.style.backgroundColor = '#f8f9fa'}
        onMouseLeave={(e) => e.target.style.backgroundColor = 'transparent'}
      >
        <i className="fas fa-bell" style={{ fontSize: '18px', color: '#6c757d' }} />
        
        {/* Badge số lượng thông báo */}
        {unreadCount > 0 && (
          <span style={{
            position: 'absolute',
            top: '4px',
            right: '4px',
            background: 'linear-gradient(135deg, #ff6b6b, #ee5a52)',
            color: 'white',
            borderRadius: '50%',
            width: '18px',
            height: '18px',
            fontSize: '11px',
            fontWeight: 'bold',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            border: '2px solid white',
            boxShadow: '0 2px 4px rgba(0,0,0,0.1)'
          }}>
            {unreadCount > 99 ? '99+' : unreadCount}
          </span>
        )}
      </button>

      {/* Dropdown Menu */}
      <AnimatePresence>
        {isOpen && (
          <motion.div
            initial={{ opacity: 0, y: -10, scale: 0.95 }}
            animate={{ opacity: 1, y: 0, scale: 1 }}
            exit={{ opacity: 0, y: -10, scale: 0.95 }}
            transition={{ duration: 0.2 }}
            style={{
              position: 'absolute',
              top: '100%',
              right: 0,
              width: '400px',
              maxHeight: '500px',
              background: 'white',
              border: '1px solid #e9ecef',
              borderRadius: '12px',
              boxShadow: '0 10px 40px rgba(0, 0, 0, 0.15)',
              zIndex: 1000,
              overflow: 'hidden'
            }}
          >
            {/* Header */}
            <div style={{
              padding: '16px 20px',
              borderBottom: '1px solid #f1f3f4',
              background: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
              color: 'white'
            }}>
              <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
                <h6 style={{ margin: 0, fontSize: '16px', fontWeight: '600' }}>
                  Thông báo ({unreadCount})
                </h6>
                <i className="fas fa-bell" style={{ opacity: 0.8 }} />
              </div>
            </div>

            {/* Content */}
            <div style={{ maxHeight: '400px', overflowY: 'auto' }}>
              {loading ? (
                <div style={{ 
                  padding: '40px 20px', 
                  textAlign: 'center',
                  color: '#6c757d'
                }}>
                  <i className="fas fa-spinner fa-spin" style={{ fontSize: '20px', marginBottom: '10px' }} />
                  <div>Đang tải...</div>
                </div>
              ) : notifications.length === 0 ? (
                <div style={{ 
                  padding: '40px 20px', 
                  textAlign: 'center',
                  color: '#6c757d'
                }}>
                  <i className="fas fa-bell-slash" style={{ fontSize: '30px', marginBottom: '10px', opacity: 0.5 }} />
                  <div>Không có thông báo mới</div>
                </div>
              ) : (
                notifications.map((notification) => (
                  <motion.div
                    key={notification.Notifications_ID}
                    whileHover={{ backgroundColor: '#f8f9fa' }}
                    onClick={() => handleMarkAsRead(notification.Notifications_ID)}
                    style={{
                      padding: '16px 20px',
                      borderBottom: '1px solid #f1f3f4',
                      cursor: 'pointer',
                      transition: 'background-color 0.2s ease'
                    }}
                  >
                    <div style={{ display: 'flex', gap: '12px' }}>
                      <div style={{
                        minWidth: '36px',
                        height: '36px',
                        borderRadius: '50%',
                        background: 'linear-gradient(135deg, #667eea, #764ba2)',
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'center',
                        color: 'white'
                      }}>
                        <i className={notification.icon || 'fas fa-info-circle'} style={{ fontSize: '14px' }} />
                      </div>
                      
                      <div style={{ flex: 1 }}>
                        <div style={{
                          fontWeight: '600',
                          fontSize: '14px',
                          color: '#2c3e50',
                          marginBottom: '4px',
                          lineHeight: 1.3
                        }}>
                          {notification.Title}
                        </div>
                        
                        <div style={{
                          fontSize: '13px',
                          color: '#6c757d',
                          lineHeight: 1.4,
                          marginBottom: '6px'
                        }}>
                          {notification.Message}
                        </div>
                        
                        <div style={{
                          fontSize: '11px',
                          color: '#adb5bd',
                          fontWeight: '500'
                        }}>
                          {formatTime(notification.Created_at)}
                        </div>
                      </div>

                      {/* Unread indicator */}
                      <div style={{
                        width: '8px',
                        height: '8px',
                        borderRadius: '50%',
                        background: '#007bff',
                        marginTop: '6px'
                      }} />
                    </div>
                  </motion.div>
                ))
              )}
            </div>

            {/* Footer */}
            {notifications.length > 0 && (
              <div style={{
                padding: '12px 20px',
                borderTop: '1px solid #f1f3f4',
                background: '#f8f9fa',
                textAlign: 'center'
              }}>
                <button
                  style={{
                    background: 'none',
                    border: 'none',
                    color: '#007bff',
                    fontSize: '13px',
                    fontWeight: '500',
                    cursor: 'pointer',
                    padding: '4px 8px',
                    borderRadius: '4px',
                    transition: 'background-color 0.2s ease'
                  }}
                  onMouseEnter={(e) => e.target.style.backgroundColor = '#e3f2fd'}
                  onMouseLeave={(e) => e.target.style.backgroundColor = 'transparent'}
                >
                  Xem tất cả thông báo
                </button>
              </div>
            )}
          </motion.div>
        )}
      </AnimatePresence>
    </div>
  );
};

export default NotificationDropdown;