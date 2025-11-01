import React, { useEffect, useState } from 'react';
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  Image,
  TouchableOpacity,
  ActivityIndicator,
  Linking,
} from 'react-native';
import { complainService } from '../../services/authService';

export default function ComplaintDetailScreen({ route }) {
  const { id } = route.params;
  const [complaint, setComplaint] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    loadComplaint();
  }, []);

  const loadComplaint = async () => {
    try {
      const response = await complainService.getComplaint(id);
      if (response.success) {
        setComplaint(response.data);
      }
    } catch (error) {
      console.error('Error loading complaint:', error);
    } finally {
      setLoading(false);
    }
  };

  const openMap = () => {
    if (complaint) {
      const url = `https://www.google.com/maps?q=${complaint.latitude},${complaint.longitude}`;
      Linking.openURL(url);
    }
  };

  const getStatusColor = (status) => {
    switch (status) {
      case 'pending':
        return { bg: '#eab30820', text: '#eab308' };
      case 'in-progress':
        return { bg: '#a855f720', text: '#a855f7' };
      case 'completed':
        return { bg: '#22c55e20', text: '#22c55e' };
      default:
        return { bg: '#6b728020', text: '#6b7280' };
    }
  };

  if (loading) {
    return (
      <View style={styles.centerContainer}>
        <ActivityIndicator size="large" color="#22c55e" />
      </View>
    );
  }

  if (!complaint) {
    return (
      <View style={styles.centerContainer}>
        <Text style={styles.errorText}>Complaint not found</Text>
      </View>
    );
  }

  const statusColors = getStatusColor(complaint.status);

  return (
    <ScrollView style={styles.container}>
      <View style={styles.header}>
        <View style={styles.headerTop}>
          <Text style={styles.title}>{complaint.complaint_type}</Text>
          <View style={[styles.statusBadge, { backgroundColor: statusColors.bg }]}>
            <Text style={[styles.statusText, { color: statusColors.text }]}>
              {complaint.status}
            </Text>
          </View>
        </View>
        <Text style={styles.date}>Submitted on {complaint.created_at}</Text>
      </View>

      {complaint.image && (
        <Image source={{ uri: complaint.image }} style={styles.image} />
      )}

      <View style={styles.content}>
        {/* Location */}
        <View style={styles.section}>
          <View style={styles.sectionHeader}>
            <Text style={styles.sectionIcon}>📍</Text>
            <Text style={styles.sectionTitle}>Location</Text>
          </View>
          <Text style={styles.sectionText}>{complaint.location}</Text>
          <TouchableOpacity style={styles.mapButton} onPress={openMap}>
            <Text style={styles.mapButtonText}>View on Map</Text>
          </TouchableOpacity>
        </View>

        {/* Description */}
        <View style={styles.section}>
          <View style={styles.sectionHeader}>
            <Text style={styles.sectionIcon}>📋</Text>
            <Text style={styles.sectionTitle}>Description</Text>
          </View>
          <Text style={styles.sectionText}>{complaint.description}</Text>
        </View>

        {/* Details */}
        <View style={styles.section}>
          <View style={styles.sectionHeader}>
            <Text style={styles.sectionIcon}>⏰</Text>
            <Text style={styles.sectionTitle}>Details</Text>
          </View>
          <View style={styles.detailRow}>
            <Text style={styles.detailLabel}>Status:</Text>
            <Text style={[styles.detailValue, { color: statusColors.text, textTransform: 'capitalize' }]}>
              {complaint.status}
            </Text>
          </View>
          {complaint.is_recycleable === 1 && (
            <View style={styles.detailRow}>
              <Text style={styles.detailLabel}>Type:</Text>
              <Text style={[styles.detailValue, { color: '#22c55e' }]}>♻️ Recyclable</Text>
            </View>
          )}
          <View style={styles.detailRow}>
            <Text style={styles.detailLabel}>Created:</Text>
            <Text style={styles.detailValue}>{complaint.created_at}</Text>
          </View>
          <View style={styles.detailRow}>
            <Text style={styles.detailLabel}>Updated:</Text>
            <Text style={styles.detailValue}>{complaint.updated_at}</Text>
          </View>
        </View>
      </View>
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#0d1a14',
  },
  centerContainer: {
    flex: 1,
    backgroundColor: '#0d1a14',
    justifyContent: 'center',
    alignItems: 'center',
  },
  header: {
    padding: 20,
    paddingTop: 60,
  },
  headerTop: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'flex-start',
    marginBottom: 8,
  },
  title: {
    flex: 1,
    fontSize: 24,
    fontWeight: 'bold',
    color: '#a7f3d0',
  },
  statusBadge: {
    paddingHorizontal: 12,
    paddingVertical: 6,
    borderRadius: 12,
  },
  statusText: {
    fontSize: 14,
    fontWeight: '600',
    textTransform: 'capitalize',
  },
  date: {
    fontSize: 12,
    color: '#6b7280',
  },
  image: {
    width: '100%',
    height: 250,
    backgroundColor: '#1f2937',
  },
  content: {
    padding: 20,
  },
  section: {
    marginBottom: 24,
  },
  sectionHeader: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 12,
    gap: 8,
  },
  sectionIcon: {
    fontSize: 20,
  },
  sectionTitle: {
    fontSize: 16,
    fontWeight: '600',
    color: '#a7f3d0',
  },
  sectionText: {
    fontSize: 14,
    color: '#d1d5db',
    lineHeight: 20,
  },
  mapButton: {
    backgroundColor: '#3b82f620',
    paddingVertical: 12,
    paddingHorizontal: 16,
    borderRadius: 12,
    marginTop: 12,
    alignItems: 'center',
  },
  mapButtonText: {
    color: '#3b82f6',
    fontSize: 14,
    fontWeight: '600',
  },
  detailRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    paddingVertical: 8,
    borderBottomWidth: 1,
    borderBottomColor: 'rgba(255, 255, 255, 0.05)',
  },
  detailLabel: {
    fontSize: 14,
    color: '#9ca3af',
  },
  detailValue: {
    fontSize: 14,
    color: '#d1d5db',
  },
  errorText: {
    fontSize: 16,
    color: '#9ca3af',
  },
});
