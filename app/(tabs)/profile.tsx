
import { View, Text, StyleSheet, ScrollView, Platform, Pressable, Alert } from "react-native";
import { IconSymbol } from "@/components/IconSymbol";
import { useTheme } from "@react-navigation/native";
import { SafeAreaView } from "react-native-safe-area-context";
import React, { useState, useEffect } from "react";
import { colors, commonStyles } from "@/styles/commonStyles";
import { Stack } from "expo-router";
import { getKostInfo, KostInfo, clearAllData, initializeSampleData } from "@/utils/database";

export default function ProfileScreen() {
  const theme = useTheme();
  const [kostInfo, setKostInfo] = useState<KostInfo | null>(null);

  useEffect(() => {
    loadKostInfo();
  }, []);

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
            {kostInfo && (
              <Text style={styles.profileSubtext}>
                {kostInfo.name} - Kamar {kostInfo.roomNumber}
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
                    <Text style={styles.infoValue}>{kostInfo.roomNumber}</Text>
                  </View>
                </View>

                <View style={styles.infoRow}>
                  <IconSymbol name="banknote" size={20} color={colors.primary} />
                  <View style={styles.infoContent}>
                    <Text style={styles.infoLabel}>Biaya Bulanan</Text>
                    <Text style={styles.infoValue}>Rp {kostInfo.monthlyRent.toLocaleString('id-ID')}</Text>
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
                    <Text style={styles.infoValue}>{kostInfo.ownerName}</Text>
                  </View>
                </View>

                <View style={styles.infoRow}>
                  <IconSymbol name="phone.fill" size={20} color={colors.primary} />
                  <View style={styles.infoContent}>
                    <Text style={styles.infoLabel}>Nomor Telepon</Text>
                    <Text style={styles.infoValue}>{kostInfo.ownerPhone}</Text>
                  </View>
                </View>

                <Pressable 
                  style={styles.contactButton}
                  onPress={() => console.log('Hubungi pemilik')}
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
              onPress={() => console.log('Edit profil')}
            >
              <IconSymbol name="pencil" size={20} color={colors.primary} />
              <Text style={styles.settingText}>Edit Profil</Text>
              <IconSymbol name="chevron.right" size={20} color={colors.textSecondary} />
            </Pressable>

            <Pressable 
              style={[commonStyles.card, styles.settingItem]}
              onPress={() => console.log('Notifikasi')}
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
