# WasteAlert - React Native Mobile App Integration

## ✅ What Has Been Created

### Backend API (Laravel - webapp folder)

**New Controllers in `app/Http/Controllers/webapp/`:**

1. **AuthApiController.php**
   - `register()` - User registration with OTP
   - `verifyOtp()` - OTP verification with token generation
   - `login()` - User login with Sanctum token
   - `logout()` - User logout
   - `profile()` - Get authenticated user profile

2. **ComplainApiController.php**
   - `store()` - Submit complaint with GPS, image, ML prediction
   - `index()` - Get all user complaints
   - `show($id)` - Get single complaint details

**API Routes (`routes/api.php`):**
- All routes prefixed with `/api/v1`
- Public: register, verify-otp, login
- Protected (Sanctum): logout, profile, complaints CRUD

### Mobile App (React Native with Expo)

**Project Structure:**
```
mobile-app/
├── App.js                           # Root component
├── package.json                     # Dependencies
├── app.json                         # Expo config
├── src/
│   ├── context/
│   │   └── AuthContext.js          # Auth state management
│   ├── services/
│   │   ├── api.js                  # Axios configuration
│   │   └── authService.js          # API calls
│   ├── navigation/
│   │   ├── AuthNavigator.js        # Login/Register flow
│   │   └── MainNavigator.js        # Bottom tabs (Dashboard, Create, Complaints, Profile)
│   └── screens/
│       ├── Auth/
│       │   ├── LoginScreen.js      # Email/password login
│       │   ├── RegisterScreen.js   # Sign up form
│       │   └── OtpVerificationScreen.js  # OTP input
│       └── Main/
│           ├── DashboardScreen.js          # Stats & recent complaints
│           ├── CreateComplaintScreen.js    # Submit complaint with GPS & image
│           ├── ComplaintsListScreen.js     # All user complaints
│           ├── ComplaintDetailScreen.js    # Single complaint view
│           └── ProfileScreen.js            # User info & logout
```

## 🎯 Features Implemented

### ✅ Authentication
- Sign up with OTP verification
- Login with email/password
- Auto-login (token stored in AsyncStorage)
- Logout
- Profile display

### ✅ Complaint Management
- Submit complaint with:
  - Auto GPS location detection
  - Reverse geocoding (coordinates → address)
  - Manual location input
  - Image upload from gallery
  - ML prediction (Flask API integration)
  - Complaint type selection
  - Description
- View all complaints
- View complaint details
- Track status (pending, in-progress, completed)
- View on Google Maps

### ✅ Dashboard
- Statistics (Total, Pending, In Progress, Completed)
- Recent complaints preview
- Quick action buttons

### ✅ UI/UX
- **Exact same design as web:**
  - Dark theme (#0d1a14)
  - Green accents (#22c55e)
  - Glassmorphism cards
  - Same typography and spacing
- Bottom tab navigation
- Pull-to-refresh
- Loading states
- Error handling

## 📱 How to Run

### Backend Setup

1. **Install Sanctum** (if not already):
```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

2. **Update User Model** (`app/Models/User.php`):
```php
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;
    // ...
}
```

3. **Start Laravel server**:
```bash
php artisan serve
# Or use XAMPP (already running on your system)
```

### Mobile App Setup

1. **Navigate to mobile app**:
```powershell
cd d:\xampp\htdocs\WasteAlert\mobile-app
```

2. **Install dependencies**:
```powershell
npm install
```

3. **Update API URL** in `src/services/api.js`:
```javascript
const API_BASE_URL = 'http://YOUR_LOCAL_IP/api/v1';
// Example: 'http://192.168.1.100/api/v1'
```

Find your IP:
```powershell
ipconfig
# Look for IPv4 Address under your WiFi adapter
```

4. **Start Expo**:
```powershell
npm start
```

5. **Run on device**:
   - Install **Expo Go** from Play Store (Android) or App Store (iOS)
   - Scan QR code from terminal
   - Make sure phone and computer are on **same WiFi**

## 🔑 Key Points

### ✅ What Was NOT Changed
- ❌ No changes to existing web code
- ❌ No changes to existing controllers
- ❌ No changes to existing routes
- ❌ No changes to database
- ✅ Only **NEW** controllers added in `webapp/` folder
- ✅ Only **NEW** API routes added

### ✅ Authentication
- Uses **Laravel Sanctum** (lightweight, perfect for mobile)
- Tokens stored in AsyncStorage
- Auto-logout on token expiration
- Same OTP system as web

### ✅ Features Match Web
- All user features implemented
- Same UI design and colors
- Same ML prediction flow
- Same GPS location tracking
- Same image upload process

### ✅ Not Included (User App Only)
- ❌ Team features (not in mobile app)
- ❌ Admin features (not needed)
- ❌ Real-time notifications (can be added later)

## 📡 API Testing

Test API endpoints using Postman:

**Base URL**: `http://localhost/api/v1`

1. **Register**:
```
POST /register
Body: {
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "01712345678",
  "password": "password123"
}
```

2. **Verify OTP**:
```
POST /verify-otp
Body: {
  "user_id": 1,
  "otp_code": "123456"
}
Response: { "token": "..." }
```

3. **Login**:
```
POST /login
Body: {
  "email": "john@example.com",
  "password": "password123"
}
Response: { "token": "..." }
```

4. **Get Complaints** (Protected):
```
GET /complaints
Headers: {
  "Authorization": "Bearer YOUR_TOKEN"
}
```

## 🐛 Common Issues

**1. Cannot connect to API**
- Solution: Use your computer's IP, not `localhost`
- Check firewall settings
- Ensure both on same WiFi

**2. GPS not working**
- Solution: Enable location permissions
- Try outdoors for better signal

**3. Image upload fails**
- Solution: Check Flask API is running
- Verify photo permissions granted

**4. OTP not sending**
- Solution: Check Laravel mail config
- Verify SMTP settings in .env

## 📊 Project Status

✅ **Complete Features:**
- Backend API controllers (webapp folder)
- Mobile app with all screens
- Authentication flow
- Complaint submission
- GPS location tracking
- Image upload
- ML prediction integration
- Dashboard with statistics
- Complaint tracking
- Profile management

✅ **Documentation:**
- README.md with setup instructions
- API endpoint documentation
- Troubleshooting guide
- .gitignore for mobile app

## 🚀 Next Steps (Optional Enhancements)

1. **Push Notifications** (Firebase Cloud Messaging)
2. **Real-time Updates** (WebSockets/Pusher)
3. **Offline Support** (Cache complaints locally)
4. **Camera Feature** (Take photo directly)
5. **Location History** (Save frequent locations)
6. **Dark/Light Theme Toggle**

## 📝 Summary

✅ **Backend**: New API controllers in `webapp/` folder - NO changes to existing code
✅ **Mobile**: Complete React Native app with Expo
✅ **Features**: All user features from web implemented
✅ **UI**: Exact same design as web application
✅ **Ready**: Can be installed and tested immediately

Everything is set up and ready to run! Just install dependencies and update the API URL with your local IP address.
