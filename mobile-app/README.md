# WasteAlert Mobile App

React Native mobile application for WasteAlert - A waste management complaint system.

## 🚀 Features

### User Features
- ✅ **Authentication**
  - Sign up with email verification (OTP)
  - Login/Logout
  - Profile management

- ✅ **Complaint Management**
  - Submit complaints with GPS location
  - Auto-detect location using device GPS
  - Reverse geocoding (coordinates → address)
  - Upload waste images
  - ML prediction (via Flask API) - Recyclable detection
  - Track complaint status (pending, in-progress, completed)
  - View complaint history
  - View detailed complaint information

- ✅ **Dashboard**
  - Statistics (Total, Pending, In Progress, Completed)
  - Recent complaints
  - Quick actions

## 📱 Tech Stack

- **Framework**: React Native (Expo)
- **Navigation**: React Navigation v6
- **State Management**: Context API
- **HTTP Client**: Axios
- **Storage**: AsyncStorage
- **Location**: Expo Location
- **Image Picker**: Expo Image Picker
- **Maps**: React Native Maps
- **Icons**: Lucide React Native

## 🛠️ Setup Instructions

### Prerequisites
- Node.js (v16 or higher)
- npm or yarn
- Expo CLI (`npm install -g expo-cli`)
- Android Studio (for Android) or Xcode (for iOS)
- Your Laravel backend running

### Installation

1. **Navigate to mobile app directory**
   ```bash
   cd mobile-app
   ```

2. **Install dependencies**
   ```bash
   npm install
   ```

3. **Configure API Base URL**
   
   Open `src/services/api.js` and change the IP address to your computer's local IP:
   ```javascript
   const API_BASE_URL = 'http://YOUR_IP_ADDRESS/api/v1';
   ```
   
   To find your IP:
   - Windows: `ipconfig` (look for IPv4 Address)
   - Mac/Linux: `ifconfig` (look for inet)
   - Example: `http://192.168.1.100/api/v1`

4. **Start the development server**
   ```bash
   npm start
   ```

5. **Run on device/emulator**
   - **Android**: Press `a` or run `npm run android`
   - **iOS**: Press `i` or run `npm run ios`
   - **Web**: Press `w` or run `npm run web`

### Running on Physical Device

1. **Install Expo Go app** from Play Store (Android) or App Store (iOS)

2. **Connect to same WiFi** as your development machine

3. **Scan QR code** shown in terminal after `npm start`

## 🔧 Backend Configuration

### 1. Install Laravel Sanctum (if not already installed)
```bash
cd d:\xampp\htdocs\WasteAlert
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

### 2. Update User Model
Add `HasApiTokens` trait to `app/Models/User.php`:
```php
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;
    // ...
}
```

### 3. Add Sanctum to API Middleware
In `app/Http/Kernel.php`, add to `api` middleware group:
```php
'api' => [
    \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    'throttle:api',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
],
```

### 4. Configure CORS
In `config/cors.php`:
```php
'paths' => ['api/*', 'sanctum/csrf-cookie'],
'allowed_origins' => ['*'],
'allowed_methods' => ['*'],
'allowed_headers' => ['*'],
'supports_credentials' => true,
```

### 5. Update .env
```env
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1,192.168.1.100
SESSION_DRIVER=cookie
SESSION_DOMAIN=null
```

## 📁 Project Structure

```
mobile-app/
├── App.js                      # Root component
├── app.json                    # Expo configuration
├── babel.config.js
├── package.json
└── src/
    ├── context/
    │   └── AuthContext.js      # Authentication context
    ├── navigation/
    │   ├── AuthNavigator.js    # Auth screens navigation
    │   └── MainNavigator.js    # Main app navigation
    ├── screens/
    │   ├── Auth/
    │   │   ├── LoginScreen.js
    │   │   ├── RegisterScreen.js
    │   │   └── OtpVerificationScreen.js
    │   └── Main/
    │       ├── DashboardScreen.js
    │       ├── CreateComplaintScreen.js
    │       ├── ComplaintsListScreen.js
    │       ├── ComplaintDetailScreen.js
    │       └── ProfileScreen.js
    └── services/
        ├── api.js              # Axios configuration
        └── authService.js      # API service functions
```

## 🎨 UI Design

The mobile app UI matches the web application with:
- Dark theme (#0d1a14 background)
- Green accent color (#22c55e)
- Glassmorphism design
- Smooth animations
- Consistent spacing and typography

## 📡 API Endpoints

All endpoints are prefixed with `/api/v1`

### Public Endpoints
- `POST /register` - Register new user
- `POST /verify-otp` - Verify OTP
- `POST /login` - Login user

### Protected Endpoints (require Bearer token)
- `POST /logout` - Logout user
- `GET /profile` - Get user profile
- `GET /complaints` - Get all user complaints
- `POST /complaints` - Submit new complaint
- `GET /complaints/{id}` - Get complaint details

## 🔐 Authentication Flow

1. User registers → OTP sent to email
2. User verifies OTP → Receives auth token
3. Token stored in AsyncStorage
4. Token sent in Authorization header for all API requests
5. Auto-login on app restart if token exists

## 📍 Location Features

- Auto-detect GPS coordinates on complaint submission
- Request location permissions
- Reverse geocoding (coordinates → address)
- Manual location input as fallback
- View location on Google Maps

## 📸 Image Upload

- Select from gallery
- Image preview
- Multipart form data upload
- ML prediction from Flask API
- Recyclable waste detection

## 🐛 Troubleshooting

### Cannot connect to API
- Ensure Laravel server is running
- Check API_BASE_URL has correct IP address
- Both device and computer on same WiFi
- Disable firewall if needed

### GPS not working
- Enable location services on device
- Grant location permissions to Expo Go
- Try outdoor for better GPS signal

### Image upload fails
- Grant photo library permissions
- Check image size (max 2MB)
- Ensure Flask API is running on port 5000

### OTP email not received
- Check Laravel mail configuration
- Verify SMTP settings in .env
- Check spam folder

## 📦 Build for Production

### Android APK
```bash
expo build:android
```

### iOS IPA
```bash
expo build:ios
```

### App Store / Play Store
Follow Expo documentation for publishing:
- https://docs.expo.dev/submit/android/
- https://docs.expo.dev/submit/ios/

## 🔄 Updates

To update dependencies:
```bash
npm update
```

To upgrade Expo SDK:
```bash
expo upgrade
```

## 📝 Notes

- This app uses **Expo managed workflow** for easier development
- **Sanctum** is used for API authentication (instead of Passport)
- UI design matches web application exactly
- All user features from web are implemented
- Team features are NOT included (user app only)

## 👨‍💻 Developer

Created for WasteAlert system as a mobile companion app.

## 📄 License

Same as main WasteAlert project.
