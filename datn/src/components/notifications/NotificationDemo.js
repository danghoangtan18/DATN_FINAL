import React from 'react';
import { useNotifications } from '../../hooks/useNotifications';

const NotificationDemo = () => {
  const { showNotification, showSuccess, showError, showInfo } = useNotifications();

  const testNotifications = [
    {
      type: 'order',
      title: 'Đặt hàng thành công',
      message: 'Đơn hàng #12345 đã được tạo thành công với tổng tiền 2.500.000đ'
    },
    {
      type: 'booking',
      title: 'Đặt sân thành công',
      message: 'Đã đặt sân Tennis Court A lúc 18:00 ngày mai'
    },
    {
      type: 'promotion',
      title: 'Khuyến mãi mới',
      message: 'Flash Sale 50% - Giảm giá tất cả sản phẩm thể thao'
    },
    {
      type: 'success',
      title: 'Cập nhật thành công',
      message: 'Thông tin tài khoản đã được cập nhật'
    },
    {
      type: 'warning',
      title: 'Đơn hàng sắp hết hạn',
      message: 'Đơn hàng #12344 chưa được thanh toán, còn 2 giờ nữa sẽ tự động hủy'
    }
  ];

  return (
    <div style={{ 
      padding: '20px',
      background: 'white',
      borderRadius: '8px',
      boxShadow: '0 2px 10px rgba(0,0,0,0.1)',
      margin: '20px'
    }}>
      <h3 style={{ marginBottom: '20px', color: '#2c3e50' }}>
        Test Notification System
      </h3>
      
      <div style={{ display: 'flex', flexWrap: 'wrap', gap: '10px', marginBottom: '20px' }}>
        <button
          onClick={() => showSuccess('Thao tác thành công!')}
          style={{
            padding: '8px 16px',
            background: '#28a745',
            color: 'white',
            border: 'none',
            borderRadius: '4px',
            cursor: 'pointer'
          }}
        >
          Success Toast
        </button>
        
        <button
          onClick={() => showError('Có lỗi xảy ra!')}
          style={{
            padding: '8px 16px',
            background: '#dc3545',
            color: 'white',
            border: 'none',
            borderRadius: '4px',
            cursor: 'pointer'
          }}
        >
          Error Toast
        </button>
        
        <button
          onClick={() => showInfo('Thông tin quan trọng')}
          style={{
            padding: '8px 16px',
            background: '#17a2b8',
            color: 'white',
            border: 'none',
            borderRadius: '4px',
            cursor: 'pointer'
          }}
        >
          Info Toast
        </button>
      </div>

      <h4 style={{ marginBottom: '15px', color: '#495057' }}>
        Test Business Notifications:
      </h4>
      
      <div style={{ display: 'flex', flexDirection: 'column', gap: '8px' }}>
        {testNotifications.map((notification, index) => (
          <button
            key={index}
            onClick={() => showNotification(notification)}
            style={{
              padding: '12px 16px',
              background: '#f8f9fa',
              border: '1px solid #dee2e6',
              borderRadius: '4px',
              cursor: 'pointer',
              textAlign: 'left',
              transition: 'background-color 0.2s ease'
            }}
            onMouseEnter={(e) => e.target.style.backgroundColor = '#e9ecef'}
            onMouseLeave={(e) => e.target.style.backgroundColor = '#f8f9fa'}
          >
            <div style={{ fontWeight: '600', color: '#2c3e50', marginBottom: '4px' }}>
              {notification.title}
            </div>
            <div style={{ fontSize: '14px', color: '#6c757d' }}>
              {notification.message}
            </div>
            <div style={{ fontSize: '12px', color: '#adb5bd', marginTop: '4px' }}>
              Type: {notification.type}
            </div>
          </button>
        ))}
      </div>

      <div style={{ 
        marginTop: '20px', 
        padding: '15px', 
        background: '#e3f2fd', 
        borderRadius: '4px',
        fontSize: '14px',
        color: '#1976d2'
      }}>
        <strong>Hướng dẫn:</strong>
        <ul style={{ marginTop: '8px', marginBottom: 0 }}>
          <li>Click các button để test notification toast</li>
          <li>Toast sẽ xuất hiện ở góc phải màn hình</li>
          <li>Tự động biến mất sau 5 giây hoặc click để đóng</li>
          <li>Hệ thống sẽ tự động show notification khi có API response</li>
        </ul>
      </div>
    </div>
  );
};

export default NotificationDemo;