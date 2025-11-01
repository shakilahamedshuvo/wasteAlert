import React, { useEffect, useState } from 'react';
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  TouchableOpacity,
  RefreshControl,
} from 'react-native';
import { useAuth } from '../../context/AuthContext';
import { complainService } from '../../services/authService';

export default function DashboardScreen({ navigation }) {
  const { user } = useAuth();
  const [complaints, setComplaints] = useState([]);
  const [stats, setStats] = useState({ total: 0, pending: 0, inProgress: 0, completed: 0 });
  const [refreshing, setRefreshing] = useState(false);

  useEffect(() => {
    loadComplaints();
  }, []);

  const loadComplaints = async () => {
    try {
      const response = await complainService.getComplaints();
      if (response.success) {
        setComplaints(response.data);
        calculateStats(response.data);
      }
    } catch (error) {
      console.error('Error loading complaints:', error);
    }
  };

  const calculateStats = (data) => {
    const total = data.length;
    const pending = data.filter((c) => c.status === 'pending').length;
    const inProgress = data.filter((c) => c.status === 'in-progress').length;
    const completed = data.filter((c) => c.status === 'completed').length;
    setStats({ total, pending, inProgress, completed });
  };

  const onRefresh = async () => {
    setRefreshing(true);
    await loadComplaints();
    setRefreshing(false);
  };

  return (
    <ScrollView
      style={styles.container}
      refreshControl={<RefreshControl refreshing={refreshing} onRefresh={onRefresh} />}
    >
      {/* Header */}
      <View style={styles.header}>
        <View>
          <Text style={styles.greeting}>Welcome back,</Text>
          <Text style={styles.userName}>{user?.name}</Text>
        </View>
      </View>

      {/* Stats Cards */}
      <View style={styles.statsContainer}>
        <View style={[styles.statCard, { backgroundColor: '#3b82f620' }]}>
          <Text style={styles.statIcon}>📋</Text>
          <Text style={styles.statValue}>{stats.total}</Text>
          <Text style={styles.statLabel}>Total</Text>
        </View>

        <View style={[styles.statCard, { backgroundColor: '#eab30820' }]}>
          <Text style={styles.statIcon}>⏰</Text>
          <Text style={styles.statValue}>{stats.pending}</Text>
          <Text style={styles.statLabel}>Pending</Text>
        </View>

        <View style={[styles.statCard, { backgroundColor: '#a855f720' }]}>
          <Text style={styles.statIcon}>📈</Text>
          <Text style={styles.statValue}>{stats.inProgress}</Text>
          <Text style={styles.statLabel}>In Progress</Text>
        </View>

        <View style={[styles.statCard, { backgroundColor: '#22c55e20' }]}>
          <Text style={styles.statIcon}>✅</Text>
          <Text style={styles.statValue}>{stats.completed}</Text>
          <Text style={styles.statLabel}>Completed</Text>
        </View>
      </View>

      {/* Quick Actions */}
      <View style={styles.section}>
        <Text style={styles.sectionTitle}>Quick Actions</Text>
        
        <TouchableOpacity
          style={styles.actionCard}
          onPress={() => navigation.navigate('Create')}
        >
          <View style={styles.actionIcon}>
            <Text style={styles.iconText}>➕</Text>
          </View>
          <View style={styles.actionContent}>
            <Text style={styles.actionTitle}>Submit New Complaint</Text>
            <Text style={styles.actionSubtitle}>Report a waste management issue</Text>
          </View>
        </TouchableOpacity>

        <TouchableOpacity
          style={styles.actionCard}
          onPress={() => navigation.navigate('Complaints')}
        >
          <View style={styles.actionIcon}>
            <Text style={styles.iconText}>📋</Text>
          </View>
          <View style={styles.actionContent}>
            <Text style={styles.actionTitle}>View My Complaints</Text>
            <Text style={styles.actionSubtitle}>Track status of your reports</Text>
          </View>
        </TouchableOpacity>
      </View>

      {/* Recent Complaints */}
      {complaints.length > 0 && (
        <View style={styles.section}>
          <Text style={styles.sectionTitle}>Recent Complaints</Text>
          {complaints.slice(0, 3).map((complaint) => (
            <TouchableOpacity
              key={complaint.id}
              style={styles.complaintCard}
              onPress={() => navigation.navigate('Complaints', {
                screen: 'ComplaintDetail',
                params: { id: complaint.id }
              })}
            >
              <View style={styles.complaintHeader}>
                <Text style={styles.complaintType}>{complaint.complaint_type}</Text>
                <View style={[
                  styles.statusBadge,
                  complaint.status === 'pending' && { backgroundColor: '#eab30820' },
                  complaint.status === 'in-progress' && { backgroundColor: '#a855f720' },
                  complaint.status === 'completed' && { backgroundColor: '#22c55e20' },
                ]}>
                  <Text style={[
                    styles.statusText,
                    complaint.status === 'pending' && { color: '#eab308' },
                    complaint.status === 'in-progress' && { color: '#a855f7' },
                    complaint.status === 'completed' && { color: '#22c55e' },
                  ]}>
                    {complaint.status}
                  </Text>
                </View>
              </View>
              <Text style={styles.complaintLocation}>{complaint.location}</Text>
              <Text style={styles.complaintDate}>{complaint.created_at}</Text>
            </TouchableOpacity>
          ))}
        </View>
      )}
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
  greeting: {
    fontSize: 14,
    color: '#9ca3af',
  },
  userName: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#a7f3d0',
    marginTop: 4,
  },
  statsContainer: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    paddingHorizontal: 20,
    gap: 12,
  },
  statCard: {
    flex: 1,
    minWidth: '45%',
    padding: 16,
    borderRadius: 16,
    alignItems: 'center',
  },
  statValue: {
    fontSize: 28,
    fontWeight: 'bold',
    color: '#fff',
    marginTop: 8,
  },
  statLabel: {
    fontSize: 12,
    color: '#9ca3af',
    marginTop: 4,
  },
  section: {
    padding: 20,
  },
  sectionTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#a7f3d0',
    marginBottom: 16,
  },
  actionCard: {
    flexDirection: 'row',
    backgroundColor: 'rgba(255, 255, 255, 0.05)',
    borderRadius: 16,
    padding: 16,
    marginBottom: 12,
    borderWidth: 1,
    borderColor: 'rgba(255, 255, 255, 0.1)',
  },
  actionIcon: {
    width: 48,
    height: 48,
    borderRadius: 12,
    backgroundColor: 'rgba(34, 197, 94, 0.1)',
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 16,
  },
  iconText: {
    fontSize: 24,
  },
  actionContent: {
    flex: 1,
    justifyContent: 'center',
  },
  actionTitle: {
    fontSize: 16,
    fontWeight: '600',
    color: '#fff',
  },
  actionSubtitle: {
    fontSize: 12,
    color: '#9ca3af',
    marginTop: 4,
  },
  complaintCard: {
    backgroundColor: 'rgba(255, 255, 255, 0.05)',
    borderRadius: 16,
    padding: 16,
    marginBottom: 12,
    borderWidth: 1,
    borderColor: 'rgba(255, 255, 255, 0.1)',
  },
  complaintHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 8,
  },
  complaintType: {
    fontSize: 16,
    fontWeight: '600',
    color: '#fff',
  },
  statusBadge: {
    paddingHorizontal: 12,
    paddingVertical: 4,
    borderRadius: 12,
  },
  statusText: {
    fontSize: 12,
    fontWeight: '600',
    textTransform: 'capitalize',
  },
  complaintLocation: {
    fontSize: 14,
    color: '#9ca3af',
    marginBottom: 4,
  },
  complaintDate: {
    fontSize: 12,
    color: '#6b7280',
  },
});
