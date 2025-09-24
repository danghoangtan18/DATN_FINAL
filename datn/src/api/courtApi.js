import api from './apiConfig';

// Court APIs
export const getCourts = (page = 1, filters = {}) => {
  const params = { page, ...filters };
  return api.get('/courts', { params });
};

export const getCourtById = (id) => {
  return api.get(`/courts/${id}`);
};

export const getCourtsByLocation = (locationId) => {
  return api.get(`/courts/location/${locationId}`);
};

// Booking APIs
export const createBooking = (bookingData) => {
  return api.post('/court-bookings', bookingData);
};

export const getBookings = (page = 1, status = '') => {
  const params = { page };
  if (status) params.status = status;
  return api.get('/court-bookings', { params });
};

export const getBookingById = (id) => {
  return api.get(`/court-bookings/${id}`);
};

export const updateBookingStatus = (id, status) => {
  return api.patch(`/court-bookings/${id}/status`, { status });
};

export const cancelBooking = (id, reason = '') => {
  return api.patch(`/court-bookings/${id}/cancel`, { reason });
};

// Check court availability
export const checkCourtAvailability = (courtId, date, startTime, endTime) => {
  return api.get(`/courts/${courtId}/availability`, {
    params: { date, start_time: startTime, end_time: endTime }
  });
};

// Location APIs
export const getLocations = () => {
  return api.get('/locations');
};

export const getLocationById = (id) => {
  return api.get(`/locations/${id}`);
};