// Service để quản lý thông báo realtime
class NotificationService {
  static instance = null;
  
  constructor() {
    if (NotificationService.instance) {
      return NotificationService.instance;
    }
    
    this.listeners = [];
    this.isConnected = false;
    NotificationService.instance = this;
  }

  // Singleton pattern
  static getInstance() {
    if (!NotificationService.instance) {
      NotificationService.instance = new NotificationService();
    }
    return NotificationService.instance;
  }

  // Khởi tạo kết nối (có thể dùng WebSocket hoặc polling)
  initialize(userId) {
    this.userId = userId;
    
    // Có thể implement WebSocket connection ở đây
    // this.connectWebSocket();
    
    // Hoặc dùng polling để check notifications định kỳ
    this.startPolling();
    
    this.isConnected = true;
  }

  // Polling để check notifications mới
  startPolling() {
    setInterval(async () => {
      if (this.userId) {
        try {
          const response = await fetch(`/api/notifications/unread/${this.userId}`);
          const notifications = await response.json();
          
          if (notifications && notifications.length > 0) {
            notifications.forEach(notification => {
              this.showNotification(notification);
            });
          }
        } catch (error) {
          console.error('Lỗi khi polling notifications:', error);
        }
      }
    }, 10000); // Check mỗi 10 giây
  }

  // Hiển thị thông báo
  showNotification(notification) {
    const event = new CustomEvent('newNotification', {
      detail: notification
    });
    window.dispatchEvent(event);
  }

  // Các method để trigger thông báo từ API response
  onOrderCreated(orderData) {
    this.showNotification({
      type: 'order',
      title: 'Đặt hàng thành công',
      message: `Đơn hàng #${orderData.code} đã được tạo thành công`,
      data: orderData
    });
  }

  onOrderStatusChanged(orderData) {
    const statusMessages = {
      'confirmed': 'Đơn hàng đã được xác nhận',
      'shipping': 'Đơn hàng đang được giao',
      'delivered': 'Đơn hàng đã được giao thành công',
      'cancelled': 'Đơn hàng đã bị hủy'
    };

    this.showNotification({
      type: 'order',
      title: 'Cập nhật đơn hàng',
      message: `${statusMessages[orderData.status]} - #${orderData.code}`,
      data: orderData
    });
  }

  onBookingCreated(bookingData) {
    this.showNotification({
      type: 'booking',
      title: 'Đặt sân thành công',
      message: `Đã đặt sân ${bookingData.court_name} lúc ${bookingData.start_time}`,
      data: bookingData
    });
  }

  onBookingStatusChanged(bookingData) {
    const statusMessages = {
      'confirmed': 'Đặt sân đã được xác nhận',
      'cancelled': 'Đặt sân đã bị hủy',
      'completed': 'Đã hoàn thành sân'
    };

    this.showNotification({
      type: 'booking',
      title: 'Cập nhật đặt sân',
      message: `${statusMessages[bookingData.status]} - ${bookingData.court_name}`,
      data: bookingData
    });
  }

  onNewPromotion(promotionData) {
    this.showNotification({
      type: 'promotion',
      title: 'Khuyến mãi mới',
      message: `${promotionData.title} - Giảm ${promotionData.discount_value}${promotionData.discount_type === 'percent' ? '%' : 'đ'}`,
      data: promotionData
    });
  }

  onProfileUpdated() {
    this.showNotification({
      type: 'success',
      title: 'Cập nhật thành công',
      message: 'Thông tin tài khoản đã được cập nhật'
    });
  }

  onBirthdayVoucher(voucherData) {
    this.showNotification({
      type: 'promotion',
      title: 'Chúc mừng sinh nhật! 🎉',
      message: `Bạn nhận được voucher sinh nhật giảm ${voucherData.discount_value}${voucherData.discount_type === 'percent' ? '%' : 'đ'}`,
      data: voucherData
    });
  }

  // Method để show notification từ API interceptor
  handleApiResponse(response, action = '') {
    if (response.data && response.data.notification) {
      this.showNotification(response.data.notification);
    }

    // Tự động trigger notification dựa vào API endpoint
    if (action.includes('order')) {
      if (action.includes('create')) {
        this.onOrderCreated(response.data);
      } else if (action.includes('update-status')) {
        this.onOrderStatusChanged(response.data);
      }
    } else if (action.includes('booking')) {
      if (action.includes('create')) {
        this.onBookingCreated(response.data);
      } else if (action.includes('update-status')) {
        this.onBookingStatusChanged(response.data);
      }
    } else if (action.includes('profile/update')) {
      this.onProfileUpdated();
    }
  }

  // Ngắt kết nối
  disconnect() {
    this.isConnected = false;
    this.userId = null;
  }
}

export default NotificationService;