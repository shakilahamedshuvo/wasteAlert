# 🗑️ WasteAlert

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.0-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12.0">
  <img src="https://img.shields.io/badge/React_Native-0.81-61DAFB?style=for-the-badge&logo=react&logoColor=black" alt="React Native">
  <img src="https://img.shields.io/badge/Expo-54.0-000020?style=for-the-badge&logo=expo&logoColor=white" alt="Expo SDK 54">
  <img src="https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2">
</p>

## 📋 About WasteAlert

WasteAlert is a comprehensive waste management solution that enables citizens to report waste-related issues in their communities. The platform uses AI-powered image classification to identify and categorize waste types, assigns reports to nearby cleanup teams, and provides real-time tracking of complaint resolution.

### 🌟 Key Features

- **📱 Mobile App (React Native)**
  - User registration with OTP verification
  - Report waste issues with photo upload
  - AI-powered waste classification (Recyclable/Organic)
  - Real-time location tracking
  - View live team member locations on map
  - Track complaint status and history
  - Push notifications for updates

- **🖥️ Web Dashboard (Laravel)**
  - Admin panel for complaint management
  - Team member dashboard with assigned tasks
  - Location-based team assignment
  - Real-time complaint tracking
  - User and team management

- **🤖 AI Integration**
  - Machine Learning model for waste classification
  - Confidence scoring for predictions
  - Recyclability determination

## 🛠️ Tech Stack

### Backend
- **Framework:** Laravel 12.0
- **Database:** MySQL
- **Authentication:** Laravel Sanctum (Token-based API)
- **Real-time:** Pusher
- **Email:** Laravel Mail
- **Location Services:** Stevebauman Location Package

### Mobile App
- **Framework:** React Native 0.81.5
- **SDK:** Expo 54.0
- **Navigation:** React Navigation v7
- **State Management:** React Context API
- **HTTP Client:** Axios
- **Maps:** React Native Maps (Google Maps)
- **Storage:** AsyncStorage

### ML/AI
- **Model:** Custom image classification model
- **API:** Flask-based prediction service (port 5000)

## 📦 Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js 20.15.1 or higher
- MySQL
- XAMPP (or similar local server)
- Expo CLI
- Android Studio / Xcode (for mobile testing)

### Backend Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/shakilahamedshuvo/wasteAlert.git
   cd wasteAlert
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Environment configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database** in `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=wastealert
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Configure mail** in `.env`:
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=your-email@gmail.com
   MAIL_PASSWORD=your-app-password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=your-email@gmail.com
   MAIL_FROM_NAME="WasteAlert"
   ```

6. **Run migrations**
   ```bash
   php artisan migrate
   ```

7. **Publish Sanctum**
   ```bash
   php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
   ```

8. **Create storage link**
   ```bash
   php artisan storage:link
   ```

9. **Start the server**
   ```bash
   # Using XAMPP Apache (recommended for network access)
   # Access via: http://your-ip-address/WasteAlert/public
   
   # OR using Laravel's built-in server
   php artisan serve --host=0.0.0.0 --port=8000
   ```

### Mobile App Setup

1. **Navigate to mobile app directory**
   ```bash
   cd mobile-app
   ```

2. **Install dependencies**
   ```bash
   npm install --legacy-peer-deps
   ```

3. **Configure API URL**
   
   Edit `mobile-app/src/services/api.js`:
   ```javascript
   const API_BASE_URL = 'http://YOUR_IP_ADDRESS/WasteAlert/public/api/v1';
   ```
   Replace `YOUR_IP_ADDRESS` with your computer's local IP (e.g., `192.168.0.101`)

4. **Start Expo**
   ```bash
   npm start
   ```

5. **Run on device**
   - Scan QR code with Expo Go app (Android/iOS)
   - Or press `a` for Android emulator
   - Or press `i` for iOS simulator

## 📱 Mobile App Setup Guide

For detailed mobile app setup instructions, see [MOBILE_APP_SETUP.md](MOBILE_APP_SETUP.md)

## 🚀 API Endpoints

### Authentication
- `POST /api/v1/register` - Register new user
- `POST /api/v1/verify-otp` - Verify OTP
- `POST /api/v1/login` - User login
- `POST /api/v1/logout` - User logout (authenticated)
- `GET /api/v1/profile` - Get user profile (authenticated)

### Complaints
- `GET /api/v1/complaints` - Get all complaints (authenticated)
- `POST /api/v1/complaints` - Submit new complaint (authenticated)
- `GET /api/v1/complaints/{id}` - Get complaint details (authenticated)

### Team
- `GET /api/v1/team-locations` - Get all team member locations (authenticated)

## 🗂️ Database Schema

### Main Tables
- `users` - User accounts (citizens, team members, admins)
- `complains` - Waste complaint reports
- `otp` - OTP verification codes
- `personal_access_tokens` - API authentication tokens
- `notifications` - User notifications
- `jobs` - Background job queue

## 🎨 App Screenshots

### Mobile App
- **Login & Registration** with OTP verification
- **Dashboard** with recent complaints
- **Create Complaint** with photo upload and location
- **Complaint List** with status tracking
- **Team Locations** with live map view
- **Profile** management

### Web Dashboard
- **Admin Dashboard** with complaint overview
- **Team Dashboard** with assigned tasks
- **Complaint Management** with status updates

## 🔐 Security Features

- Token-based API authentication (Laravel Sanctum)
- OTP email verification for new users
- Password hashing with bcrypt
- CORS protection
- SQL injection prevention
- XSS protection

## 🌐 Network Configuration

For mobile app to connect to backend:
1. Both devices must be on the same WiFi network
2. Configure `usesCleartextTraffic: true` in `app.json` (Android HTTP support)
3. Use computer's local IP address (not `localhost`)
4. Ensure firewall allows connections on port 80/8000

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 👥 Team

- **Developer:** Shakil Ahamed Shuvo
- **Email:** shakilahamedshuvooo@gmail.com
- **GitHub:** [@shakilahamedshuvo](https://github.com/shakilahamedshuvo)

## 🙏 Acknowledgments

- Laravel Framework
- React Native & Expo
- React Navigation
- All open-source contributors

---

<p align="center">Made with ❤️ for a cleaner environment</p>
