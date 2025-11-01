import axios from 'axios';
import AsyncStorage from '@react-native-async-storage/async-storage';

// Change this to your Laravel server IP (use your computer's local IP for testing on physical device)
const API_BASE_URL = 'http://192.168.0.101/WasteAlert/public/api/v1'; // Using XAMPP Apache server

const api = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
  timeout: 10000, // 10 second timeout
});

// Add token to requests automatically
api.interceptors.request.use(
  async (config) => {
    const token = await AsyncStorage.getItem('token');
    console.log('API Request:', config.method?.toUpperCase(), config.url);
    console.log('Token from storage:', token ? `${token.substring(0, 20)}...` : 'No token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
      console.log('Authorization header set');
    } else {
      console.log('WARNING: No token found in storage!');
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

export default api;
