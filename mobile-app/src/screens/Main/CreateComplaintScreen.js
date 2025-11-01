import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  StyleSheet,
  ScrollView,
  ActivityIndicator,
  Alert,
  Image,
} from 'react-native';
import * as Location from 'expo-location';
import * as ImagePicker from 'expo-image-picker';
import { complainService } from '../../services/authService';

export default function CreateComplaintScreen({ navigation }) {
  const [complaintType, setComplaintType] = useState('');
  const [location, setLocation] = useState('');
  const [latitude, setLatitude] = useState(null);
  const [longitude, setLongitude] = useState(null);
  const [description, setDescription] = useState('');
  const [image, setImage] = useState(null);
  const [loading, setLoading] = useState(false);
  const [gpsStatus, setGpsStatus] = useState('Getting your GPS coordinates...');
  const [gpsLoading, setGpsLoading] = useState(true);

  useEffect(() => {
    getLocation();
  }, []);

  const getLocation = async () => {
    try {
      const { status } = await Location.requestForegroundPermissionsAsync();
      if (status !== 'granted') {
        setGpsStatus('Permission denied. Please enable GPS.');
        setGpsLoading(false);
        return;
      }

      const location = await Location.getCurrentPositionAsync({
        accuracy: Location.Accuracy.High,
      });

      setLatitude(location.coords.latitude);
      setLongitude(location.coords.longitude);
      setGpsStatus(`GPS: ${location.coords.latitude.toFixed(6)}, ${location.coords.longitude.toFixed(6)}`);

      // Reverse geocode to get address
      const address = await Location.reverseGeocodeAsync({
        latitude: location.coords.latitude,
        longitude: location.coords.longitude,
      });

      if (address[0]) {
        const addr = address[0];
        const parts = [];
        if (addr.street) parts.push(addr.street);
        if (addr.district) parts.push(addr.district);
        if (addr.city) parts.push(addr.city);
        setLocation(parts.slice(0, 3).join(', '));
      }

      setGpsLoading(false);
    } catch (error) {
      setGpsStatus('Unable to get GPS. Please enter location manually.');
      setGpsLoading(false);
    }
  };

  const pickImage = async () => {
    const { status } = await ImagePicker.requestMediaLibraryPermissionsAsync();
    if (status !== 'granted') {
      Alert.alert('Error', 'Permission to access photos is required');
      return;
    }

    const result = await ImagePicker.launchImageLibraryAsync({
      mediaTypes: ['images'],
      allowsEditing: true,
      aspect: [4, 3],
      quality: 0.8,
    });

    if (!result.canceled) {
      setImage(result.assets[0]);
    }
  };

  const handleSubmit = async () => {
    if (!complaintType || !location || !description || !latitude || !longitude) {
      Alert.alert('Error', 'Please fill in all required fields');
      return;
    }

    console.log('Starting complaint submission...');
    setLoading(true);
    try {
      const formData = new FormData();
      formData.append('complaint_type', complaintType);
      formData.append('location', location);
      formData.append('description', description);
      formData.append('latitude', latitude);
      formData.append('longitude', longitude);
      
      console.log('Form data prepared:', {
        complaint_type: complaintType,
        location,
        description,
        latitude,
        longitude,
        hasImage: !!image
      });

      if (image) {
        const imageUri = image.uri;
        const filename = imageUri.split('/').pop();
        const match = /\.(\w+)$/.exec(filename);
        const type = match ? `image/${match[1]}` : 'image/jpeg';

        console.log('Attaching image:', { filename, type, uri: imageUri });
        formData.append('image', {
          uri: imageUri,
          name: filename,
          type: type,
        });
      }

      console.log('Calling API to submit complaint...');
      const response = await complainService.submitComplaint(formData);
      console.log('API Response:', response);

      if (response.success) {
        Alert.alert(
          'Success',
          `Complaint submitted successfully!\nPrediction: ${response.data.prediction}\nConfidence: ${response.data.confidence}%`,
          [
            {
              text: 'OK',
              onPress: () => {
                navigation.navigate('Dashboard');
                // Reset form
                setComplaintType('');
                setLocation('');
                setDescription('');
                setImage(null);
                getLocation();
              },
            },
          ]
        );
      }
    } catch (error) {
      console.error('Complaint submission error:', error);
      console.error('Error response:', error.response?.data);
      const errorMessage = error.response?.data?.message || error.message || 'Failed to submit complaint';
      Alert.alert('Error', errorMessage);
    } finally {
      setLoading(false);
    }
  };

  return (
    <ScrollView style={styles.container}>
      <View style={styles.header}>
        <Text style={styles.title}>Submit Complaint</Text>
        <Text style={styles.subtitle}>
          Please describe your issue below. Our team will review it and respond shortly.
        </Text>
      </View>

      <View style={styles.form}>
        {/* Complaint Type */}
        <View style={styles.inputGroup}>
          <Text style={styles.label}>Complaint Type</Text>
          <View style={styles.pickerContainer}>
            <TouchableOpacity
              style={styles.picker}
              onPress={() => {
                Alert.alert('Select Type', '', [
                  { text: 'Garbage Overflow', onPress: () => setComplaintType('Garbage Overflow') },
                  { text: 'Uncollected Waste', onPress: () => setComplaintType('Uncollected Waste') },
                  { text: 'Illegal Dumping', onPress: () => setComplaintType('Illegal Dumping') },
                  { text: 'Others', onPress: () => setComplaintType('Others') },
                  { text: 'Cancel', style: 'cancel' },
                ]);
              }}
            >
              <Text style={complaintType ? styles.pickerText : styles.pickerPlaceholder}>
                {complaintType || 'Select type'}
              </Text>
            </TouchableOpacity>
          </View>
        </View>

        {/* Location */}
        <View style={styles.inputGroup}>
          <View style={styles.labelRow}>
            <Text style={styles.label}>Location</Text>
            <TouchableOpacity onPress={getLocation} style={styles.autoDetectBtn}>
              <Text style={styles.autoDetectIcon}>📍</Text>
              <Text style={styles.autoDetectText}>Auto-detect GPS</Text>
            </TouchableOpacity>
          </View>
          <View style={styles.inputContainer}>
            <TextInput
              style={styles.input}
              placeholder="Start typing address..."
              placeholderTextColor="#6b7280"
              value={location}
              onChangeText={setLocation}
            />
          </View>
          <View style={styles.gpsStatus}>
            {gpsLoading ? (
              <ActivityIndicator color="#eab308" size="small" />
            ) : (
              <Text style={styles.gpsIcon}>📍</Text>
            )}
            <Text style={styles.gpsStatusText}>{gpsStatus}</Text>
          </View>
        </View>

        {/* Description */}
        <View style={styles.inputGroup}>
          <Text style={styles.label}>Description</Text>
          <TextInput
            style={[styles.input, styles.textArea]}
            placeholder="Describe the issue..."
            placeholderTextColor="#6b7280"
            value={description}
            onChangeText={setDescription}
            multiline
            numberOfLines={4}
            textAlignVertical="top"
          />
        </View>

        {/* Image Upload */}
        <View style={styles.inputGroup}>
          <Text style={styles.label}>Upload Image (optional)</Text>
          <TouchableOpacity style={styles.imageUpload} onPress={pickImage}>
            {image ? (
              <Image source={{ uri: image.uri }} style={styles.imagePreview} />
            ) : (
              <>
                <Text style={styles.uploadIcon}>🖼️</Text>
                <Text style={styles.imageUploadText}>Click to upload</Text>
              </>
            )}
          </TouchableOpacity>
        </View>

        {/* Submit Button */}
        <TouchableOpacity
          style={[styles.submitButton, loading && styles.submitButtonDisabled]}
          onPress={handleSubmit}
          disabled={loading}
        >
          {loading ? (
            <ActivityIndicator color="#000" />
          ) : (
            <Text style={styles.submitButtonText}>Submit Complaint</Text>
          )}
        </TouchableOpacity>
      </View>
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#0d1a14',
  },
  header: {
    padding: 20,
    paddingTop: 60,
  },
  title: {
    fontSize: 20,
    fontWeight: 'bold',
    color: '#a7f3d0',
    marginBottom: 8,
  },
  subtitle: {
    fontSize: 14,
    color: '#9ca3af',
  },
  form: {
    padding: 20,
  },
  inputGroup: {
    marginBottom: 20,
  },
  label: {
    fontSize: 14,
    color: '#d1d5db',
    marginBottom: 8,
  },
  labelRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 8,
  },
  autoDetectBtn: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 4,
  },
  autoDetectText: {
    fontSize: 12,
    color: '#22c55e',
  },
  pickerContainer: {
    backgroundColor: 'rgba(255, 255, 255, 0.1)',
    borderRadius: 12,
    borderWidth: 1,
    borderColor: 'rgba(255, 255, 255, 0.1)',
  },
  picker: {
    paddingHorizontal: 16,
    paddingVertical: 12,
  },
  pickerText: {
    color: '#fff',
    fontSize: 14,
  },
  pickerPlaceholder: {
    color: '#6b7280',
    fontSize: 14,
  },
  inputContainer: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  input: {
    flex: 1,
    backgroundColor: 'rgba(255, 255, 255, 0.1)',
    borderRadius: 12,
    paddingHorizontal: 16,
    paddingVertical: 12,
    borderWidth: 1,
    borderColor: 'rgba(255, 255, 255, 0.1)',
    color: '#fff',
    fontSize: 14,
  },
  textArea: {
    minHeight: 100,
  },
  gpsStatus: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 8,
    marginTop: 8,
  },
  gpsIcon: {
    fontSize: 16,
  },
  gpsStatusText: {
    fontSize: 12,
    color: '#9ca3af',
  },
  imageUpload: {
    height: 120,
    backgroundColor: 'rgba(255, 255, 255, 0.05)',
    borderRadius: 16,
    borderWidth: 2,
    borderStyle: 'dashed',
    borderColor: 'rgba(34, 197, 94, 0.3)',
    justifyContent: 'center',
    alignItems: 'center',
  },
  uploadIcon: {
    fontSize: 32,
    marginBottom: 8,
  },
  imagePreview: {
    width: '100%',
    height: '100%',
    borderRadius: 14,
  },
  imageUploadText: {
    fontSize: 12,
    color: '#9ca3af',
    marginTop: 8,
  },
  submitButton: {
    backgroundColor: '#88e7b5',
    borderRadius: 16,
    paddingVertical: 16,
    alignItems: 'center',
    marginTop: 8,
  },
  submitButtonDisabled: {
    opacity: 0.6,
  },
  submitButtonText: {
    color: '#000',
    fontSize: 16,
    fontWeight: '600',
  },
});
