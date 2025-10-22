
import React from 'react';
import { Platform } from 'react-native';
import { NativeTabs, Icon, Label } from 'expo-router/unstable-native-tabs';
import { Stack } from 'expo-router';
import FloatingTabBar, { TabBarItem } from '@/components/FloatingTabBar';
import { colors } from '@/styles/commonStyles';

export default function TabLayout() {
  // Define the tabs configuration
  const tabs: TabBarItem[] = [
    {
      name: '(home)',
      route: '/(tabs)/(home)/',
      icon: 'house.fill',
      label: 'Beranda',
    },
    {
      name: 'paymentMethods',
      route: '/(tabs)/paymentMethods',
      icon: 'creditcard.fill',
      label: 'Pembayaran',
    },
    {
      name: 'paymentHistory',
      route: '/(tabs)/paymentHistory',
      icon: 'clock.fill',
      label: 'Riwayat',
    },
    {
      name: 'profile',
      route: '/(tabs)/profile',
      icon: 'person.fill',
      label: 'Profil',
    },
  ];

  // Use NativeTabs for iOS, custom FloatingTabBar for Android and Web
  if (Platform.OS === 'ios') {
    return (
      <NativeTabs>
        <NativeTabs.Trigger name="(home)">
          <Icon sf="house.fill" drawable="ic_home" />
          <Label>Beranda</Label>
        </NativeTabs.Trigger>
        <NativeTabs.Trigger name="paymentMethods">
          <Icon sf="creditcard.fill" drawable="ic_payment" />
          <Label>Pembayaran</Label>
        </NativeTabs.Trigger>
        <NativeTabs.Trigger name="paymentHistory">
          <Icon sf="clock.fill" drawable="ic_history" />
          <Label>Riwayat</Label>
        </NativeTabs.Trigger>
        <NativeTabs.Trigger name="profile">
          <Icon sf="person.fill" drawable="ic_profile" />
          <Label>Profil</Label>
        </NativeTabs.Trigger>
      </NativeTabs>
    );
  }

  // For Android and Web, use Stack navigation with custom floating tab bar
  return (
    <>
      <Stack
        screenOptions={{
          headerShown: false,
          animation: 'none',
        }}
      >
        <Stack.Screen name="(home)" />
        <Stack.Screen name="paymentMethods" />
        <Stack.Screen name="paymentHistory" />
        <Stack.Screen name="profile" />
      </Stack>
      <FloatingTabBar tabs={tabs} />
    </>
  );
}
