import api from './api';

export const authService = {
  // Register new user
  register: async (name, email, phone, password) => {
    const response = await api.post('/register', {
      name,
      email,
      phone,
      password,
    });
    return response.data;
  },

  // Verify OTP
  verifyOtp: async (userId, otpCode) => {
    const response = await api.post('/verify-otp', {
      user_id: userId,
      otp_code: otpCode,
    });
    return response.data;
  },

  // Login
  login: async (email, password) => {
    const response = await api.post('/login', {
      email,
      password,
    });
    return response.data;
  },

  // Logout
  logout: async () => {
    const response = await api.post('/logout');
    return response.data;
  },

  // Get profile
  getProfile: async () => {
    const response = await api.get('/profile');
    return response.data;
  },

  // Get team locations
  getTeamLocations: async () => {
    const response = await api.get('/team-locations');
    return response.data;
  },
};

export const complainService = {
  // Submit complaint
  submitComplaint: async (formData) => {
    const response = await api.post('/complaints', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    });
    return response.data;
  },

  // Get all complaints
  getComplaints: async () => {
    const response = await api.get('/complaints');
    return response.data;
  },

  // Get single complaint
  getComplaint: async (id) => {
    const response = await api.get(`/complaints/${id}`);
    return response.data;
  },
};
