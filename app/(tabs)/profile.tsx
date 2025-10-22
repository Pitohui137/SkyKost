
import { View, Text, StyleSheet, ScrollView, Platform, Pressable, Alert } from "react-native";
import { IconSymbol } from "@/components/IconSymbol";
import { useTheme } from "@react-navigation/native";
import React, { useState, useEffect } from "react";
import { colors, commonStyles } from "@/styles/commonStyles";
import { Stack, router } from "expo-router";
import { getKostInfo, KostInfo, clearAllData, initializeSampleData } from "@/utils/database";
import { supabase } from "@/lib/supabase";

export default function ProfileScreen() {
  const theme = useTheme();
  const [kostInfo, setKostInfo] = useState<KostInfo | null>(null);
  const [userEmail, setUserEmail] = useState<string>('');

  useEffect(() => {
    loadKostInfo();
    loadUserInfo();
  }, []);

  const loadUserInfo = async () => {
    try {
      const { data: { user } } = await supabase.auth.getUser();
      if (user) {
        setUserEmail(user.email || '');
        console.log('User info loaded:', user.email);
      }
    } catch (error) {
      console.error('Error loading user info:', error);
    }
  };

  const loadKostInfo = async () => {
    try {
      const data = await getKostInfo();
      setKostInfo(data);
      console.log('Kost info loaded');
    } catch (error) {
      console.error('Error loading kost info:', error);
    }
  };

  const handleResetData = () => {
    Alert.alert(
      'Reset Data',
      'Apakah Anda yakin ingin menghapus semua data dan memuat ulang data contoh?',
      [
        { text: 'Batal', style: 'cancel' },
        {
          text: 'Reset',
          style: 'destructive',
          onPress: async () => {
            try {
              await clearAllData();
              await initializeSampleData();
              await loadKostInfo();
              Alert.alert('Berhasil', 'Data telah direset');
              console.log('Data reset successfully');
            } catch (error) {
              console.error('Error resetting data:', error);
              Alert.alert('Error', 'Gagal mereset data');
            }
          },
        },
      ]
    );
  };

  const handleSignOut = () => {
    Alert.alert(
      'Keluar',
      'Apakah Anda yakin ingin keluar?',
      [
        { text: 'Batal', style: 'cancel' },
        {
          text: 'Keluar',
          style: 'destructive',
          onPress: async () => {
            try {
              const { error } = await supabase.auth.signOut();
              if (error) {
                console.error('Sign out error:', error);
                Alert.alert('Error', 'Gagal keluar. Silakan coba lagi.');
                return;
              }
              console.log('User signed out successfully');
              router.replace('/auth');
            } catch (error) {
              console.error('Sign out error:', error);
              Alert.alert('Error', 'Terjadi kesalahan. Silakan coba lagi.');
            }
          },
        },
      ]
    );
  };

  return (
    <>
      {Platform.OS === 'ios' && (
        <Stack.Screen
          options={{
            title: "Profil",
            headerLargeTitle: true,
          }}
        />
      )}
      <View style={[commonStyles.container]}>
        <ScrollView 
          style={styles.scrollView}
          contentContainerStyle={[
            styles.scrollContent,
            Platform.OS !== 'ios' && styles.scrollContentWithTabBar
          ]}
          showsVerticalScrollIndicator={false}
        >
          {/* Profile Header */}
          <View style={[commonStyles.card, styles.profileCard]}>
            <View style={styles.avatarContainer}>
              <View style={styles.avatar}>
                <IconSymbol name="person.fill" size={48} color={colors.primary} />
              </View>
            </View>
            <Text style={styles.profileName}>Penghuni Kost</Text>
            {userEmail && (
              <Text style={styles.profileEmail}>{userEmail}</Text>
            )}
            {kostInfo && (
              <Text style={styles.profileSubtext}>
                {kostInfo.name} - Kamar {kostInfo.room_number}
              </Text>
            )}
          </View>

          {/* Kost Information */}
          {kostInfo && (
            <View style={styles.section}>
              <Text style={styles.sectionTitle}>Informasi Kost</Text>
              
              <View style={[commonStyles.card, styles.infoCard]}>
                <View style={styles.infoRow}>
                  <IconSymbol name="house.fill" size={20} color={colors.primary} />
                  <View style={styles.infoContent}>
                    <Text style={styles.infoLabel}>Nama Kost</Text>
                    <Text style={styles.infoValue}>{kostInfo.name}</Text>
                  </View>
                </View>

                <View style={styles.infoRow}>
                  <IconSymbol name="location.fill" size={20} color={colors.primary} />
                  <View style={styles.infoContent}>
                    <Text style={styles.infoLabel}>Alamat</Text>
                    <Text style={styles.infoValue}>{kostInfo.address}</Text>
                  </View>
                </View>

                <View style={styles.infoRow}>
                  <IconSymbol name="door.left.hand.open" size={20} color={colors.primary} />
                  <View style={styles.infoContent}>
                    <Text style={styles.infoLabel}>Nomor Kamar</Text>
                    <Text style={styles.infoValue}>{kostInfo.room_number}</Text>
                  </View>
                </View>

                <View style={styles.infoRow}>
                  <IconSymbol name="banknote" size={20} color={colors.primary} />
                  <View style={styles.infoContent}>
                    <Text style={styles.infoLabel}>Biaya Bulanan</Text>
                    <Text style={styles.infoValue}>Rp {kostInfo.monthly_rent.toLocaleString('id-ID')}</Text>
                  </View>
                </View>
              </View>
            </View>
          )}

          {/* Owner Information */}
          {kostInfo && (
            <View style={styles.section}>
              <Text style={styles.sectionTitle}>Informasi Pemilik</Text>
              
              <View style={[commonStyles.card, styles.infoCard]}>
                <View style={styles.infoRow}>
                  <IconSymbol name="person.circle.fill" size={20} color={colors.primary} />
                  <View style={styles.infoContent}>
                    <Text style={styles.infoLabel}>Nama Pemilik</Text>
                    <Text style={styles.infoValue}>{kostInfo.owner_name}</Text>
                  </View>
                </View>

                <View style={styles.infoRow}>
                  <IconSymbol name="phone.fill" size={20} color={colors.primary} />
                  <View style={styles.infoContent}>
                    <Text style={styles.infoLabel}>Nomor Telepon</Text>
                    <Text style={styles.infoValue}>{kostInfo.owner_phone}</Text>
                  </View>
                </View>

                <Pressable 
                  style={styles.contactButton}
                  onPress={() => {
                    Alert.alert(
                      'Hubungi Pemilik',
                      `Hubungi ${kostInfo.owner_name} di ${kostInfo.owner_phone}?`,
                      [
                        { text: 'Batal', style: 'cancel' },
                        { text: 'Hubungi', onPress: () => console.log('Calling owner:', kostInfo.owner_phone) }
                      ]
                    );
                  }}
                >
                  <IconSymbol name="phone.circle.fill" size={20} color="#FFFFFF" />
                  <Text style={styles.contactButtonText}>Hubungi Pemilik</Text>
                </Pressable>
              </View>
            </View>
          )}

          {/* Settings */}
          <View style={styles.section}>
            <Text style={styles.sectionTitle}>Pengaturan</Text>
            
            <Pressable 
              style={[commonStyles.card, styles.settingItem]}
              onPress={() => {
                Alert.alert('Info', 'Fitur edit profil akan segera hadir');
                console.log('Edit profil pressed');
              }}
            >
              <IconSymbol name="pencil" size={20} color={colors.primary} />
              <Text style={styles.settingText}>Edit Profil</Text>
              <IconSymbol name="chevron.right" size={20} color={colors.textSecondary} />
            </Pressable>

            <Pressable 
              style={[commonStyles.card, styles.settingItem]}
              onPress={() => {
                Alert.alert('Info', 'Fitur notifikasi akan segera hadir');
                console.log('Notifikasi pressed');
              }}
            >
              <IconSymbol name="bell.fill" size={20} color={colors.primary} />
              <Text style={styles.settingText}>Notifikasi</Text>
              <IconSymbol name="chevron.right" size={20} color={colors.textSecondary} />
            </Pressable>

            <Pressable 
              style={[commonStyles.card, styles.settingItem]}
              onPress={handleResetData}
            >
              <IconSymbol name="arrow.clockwise" size={20} color={colors.accent} />
              <Text style={[styles.settingText, { color: colors.accent }]}>Reset Data</Text>
              <IconSymbol name="chevron.right" size={20} color={colors.textSecondary} />
            </Pressable>

            <Pressable 
              style={[commonStyles.card, styles.settingItem, styles.signOutItem]}
              onPress={handleSignOut}
            >
              <IconSymbol name="rectangle.portrait.and.arrow.right" size={20} color="#F44336" />
              <Text style={[styles.settingText, { color: '#F44336' }]}>Keluar</Text>
              <IconSymbol name="chevron.right" size={20} color={colors.textSecondary} />
            </Pressable>
          </View>

          {/* App Info */}
          <View style={styles.appInfo}>
            <Text style={styles.appInfoText}>Aplikasi Manajemen Kost-an</Text>
            <Text style={styles.appInfoVersion}>Versi 1.0.0</Text>
          </View>
        </ScrollView>
      </View>
    </>
  );
}

const styles = StyleSheet.create({
  scrollView: {
    flex: 1,
  },
  scrollContent: {
    paddingHorizontal: 16,
    paddingTop: 16,
    paddingBottom: 16,
  },
  scrollContentWithTabBar: {
    paddingBottom: 100,
  },
  profileCard: {
    alignItems: 'center',
    paddingVertical: 32,
    marginBottom: 24,
  },
  avatarContainer: {
    marginBottom: 16,
  },
  avatar: {
    width: 100,
    height: 100,
    borderRadius: 50,
    backgroundColor: colors.secondary,
    justifyContent: 'center',
    alignItems: 'center',
  },
  profileName: {
    fontSize: 24,
    fontWeight: '700',
    color: colors.text,
    marginBottom: 4,
  },
  profileEmail: {
    fontSize: 14,
    color: colors.textSecondary,
    marginBottom: 4,
  },
  profileSubtext: {
    fontSize: 14,
    color: colors.textSecondary,
  },
  section: {
    marginBottom: 24,
  },
  sectionTitle: {
    fontSize: 18,
    fontWeight: '700',
    color: colors.text,
    marginBottom: 12,
  },
  infoCard: {
    gap: 16,
  },
  infoRow: {
    flexDirection: 'row',
    alignItems: 'flex-start',
    gap: 12,
  },
  infoContent: {
    flex: 1,
  },
  infoLabel: {
    fontSize: 12,
    color: colors.textSecondary,
    marginBottom: 4,
  },
  infoValue: {
    fontSize: 16,
    fontWeight: '600',
    color: colors.text,
  },
  contactButton: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    backgroundColor: colors.primary,
    paddingVertical: 12,
    paddingHorizontal: 24,
    borderRadius: 8,
    gap: 8,
    marginTop: 8,
  },
  contactButtonText: {
    fontSize: 16,
    fontWeight: '600',
    color: '#FFFFFF',
  },
  settingItem: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingVertical: 16,
    paddingHorizontal: 16,
    marginBottom: 8,
    gap: 12,
  },
  signOutItem: {
    marginTop: 8,
  },
  settingText: {
    flex: 1,
    fontSize: 16,
    fontWeight: '500',
    color: colors.text,
  },
  appInfo: {
    alignItems: 'center',
    paddingVertical: 24,
  },
  appInfoText: {
    fontSize: 14,
    color: colors.textSecondary,
    marginBottom: 4,
  },
  appInfoVersion: {
    fontSize: 12,
    color: colors.textSecondary,
  },
});
