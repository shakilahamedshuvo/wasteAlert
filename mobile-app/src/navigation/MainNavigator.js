import React from 'react';
import { Text } from 'react-native';
import { createBottomTabNavigator } from '@react-navigation/bottom-tabs';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import DashboardScreen from '../screens/Main/DashboardScreen';
import CreateComplaintScreen from '../screens/Main/CreateComplaintScreen';
import ComplaintsListScreen from '../screens/Main/ComplaintsListScreen';
import ComplaintDetailScreen from '../screens/Main/ComplaintDetailScreen';
import ProfileScreen from '../screens/Main/ProfileScreen';
import TeamLocationsScreen from '../screens/Main/TeamLocationsScreen';

const Tab = createBottomTabNavigator();
const Stack = createNativeStackNavigator();

function ComplaintsStack() {
  return (
    <Stack.Navigator
      screenOptions={{
        headerShown: false,
        contentStyle: { backgroundColor: '#0d1a14' },
      }}
    >
      <Stack.Screen name="ComplaintsList" component={ComplaintsListScreen} />
      <Stack.Screen name="ComplaintDetail" component={ComplaintDetailScreen} />
    </Stack.Navigator>
  );
}

export default function MainNavigator() {
  return (
    <Tab.Navigator
      screenOptions={{
        headerShown: false,
        tabBarStyle: {
          backgroundColor: '#0d1a14',
          borderTopWidth: 1,
          borderTopColor: '#22c55e30',
          paddingBottom: 8,
          paddingTop: 8,
          height: 65,
        },
        tabBarActiveTintColor: '#22c55e',
        tabBarInactiveTintColor: '#6b7280',
        tabBarLabelStyle: {
          fontSize: 12,
          fontWeight: '600',
        },
      }}
    >
      <Tab.Screen
        name="Dashboard"
        component={DashboardScreen}
        options={{
          tabBarIcon: ({ color, size }) => <Text style={{ fontSize: 24 }}>🏠</Text>,
        }}
      />
      <Tab.Screen
        name="Create"
        component={CreateComplaintScreen}
        options={{
          tabBarIcon: ({ color, size }) => <Text style={{ fontSize: 24 }}>➕</Text>,
          tabBarLabel: 'Submit',
        }}
      />
      <Tab.Screen
        name="Complaints"
        component={ComplaintsStack}
        options={{
          tabBarIcon: ({ color, size }) => <Text style={{ fontSize: 24 }}>📋</Text>,
        }}
      />
      <Tab.Screen
        name="Team"
        component={TeamLocationsScreen}
        options={{
          tabBarIcon: ({ color, size }) => <Text style={{ fontSize: 24 }}>📍</Text>,
          tabBarLabel: 'Team',
        }}
      />
      <Tab.Screen
        name="Profile"
        component={ProfileScreen}
        options={{
          tabBarIcon: ({ color, size }) => <Text style={{ fontSize: 24 }}>👤</Text>,
        }}
      />
    </Tab.Navigator>
  );
}
