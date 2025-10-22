
import React, { useState, useEffect } from "react";
import { Stack } from "expo-router";
import { ScrollView, StyleSheet, View, Text, Pressable, Platform, ActivityIndicator } from "react-native";
import { IconSymbol } from "@/components/IconSymbol";
import { colors, commonStyles } from "@/styles/commonStyles";
import { LinearGradient } from "expo-linear-gradient";
import { getPayments, getKostInfo, initializeSampleData, Payment, KostInfo } from "@/utils/database";

export default function HomeScreen() {
  const [loading, setLoading] = useState(true);
  const [payments, setPayments] = useState<Payment[]>([]);
  const [kostInfo, setKostInfo] = useState<KostInfo | null>(null);

  useEffect(() => {
    loadData();
  }, []);

  const loadData = async () => {
    try {
      setLoading(true);
      await initializeSampleData();
      const paymentsData = await getPayments();
      const kostData = await getKostInfo();
      setPayments(paymentsData);
      setKostInfo(kostData);
      console.log('Data loaded successfully');
    } catch (error) {
      console.error('Error loading data:', error);
    } finally {
      setLoading(false);
    }
  };

  const formatCurrency = (amount: number) => {
    return `Rp ${amount.toLocaleString('id-ID')}`;
  };

  const getNextPaymentDate = () => {
    const today = new Date();
    const nextMonth = new Date(today.getFullYear(), today.getMonth() + 1, 1);
    return nextMonth.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
  };

  const recentPayments = payments.slice(0, 3);
  const outstandingBalance = 0;

  if (loading) {
    return (
      <View style={[commonStyles.container, styles.loadingContainer]}>
        <ActivityIndicator size="large" color={colors.primary} />
        <Text style={styles.loadingText}>Memuat data...</Text>
      </View>
    );
  }

  return (
    <>
      {Platform.OS === 'ios' && (
        <Stack.Screen
          options={{
            title: "Beranda",
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
          {/* Welcome Section */}
          <View style={styles.welcomeSection}>
            <Text style={styles.welcomeText}>Selamat Datang!</Text>
            <Text style={styles.welcomeSubtext}>
              {kostInfo ? `${kostInfo.name} - Kamar ${kostInfo.roomNumber}` : 'Ringkasan pembayaran kost Anda'}
            </Text>
          </View>

          {/* Rent Due Card */}
          <View style={[commonStyles.card, styles.rentDueCard]}>
            <LinearGradient
              colors={[colors.primary, '#5C6BC0']}
              start={{ x: 0, y: 0 }}
              end={{ x: 1, y: 1 }}
              style={styles.gradientCard}
            >
              <View style={styles.cardHeader}>
                <IconSymbol name="calendar" size={32} color="#FFFFFF" />
                <Text style={styles.cardLabel}>Pembayaran Berikutnya</Text>
              </View>
              <Text style={styles.rentAmount}>
                {kostInfo ? formatCurrency(kostInfo.monthlyRent) : 'Rp 1.500.000'}
              </Text>
              <Text style={styles.rentDueDate}>Jatuh tempo {getNextPaymentDate()}</Text>
              
              <Pressable 
                style={styles.payButton}
                onPress={() => console.log('Bayar sekarang pressed')}
              >
                <Text style={styles.payButtonText}>Bayar Sekarang</Text>
                <IconSymbol name="arrow.right" size={16} color={colors.primary} />
              </Pressable>
            </LinearGradient>
          </View>

          {/* Outstanding Balance Card */}
          <View style={[commonStyles.card, styles.balanceCard]}>
            <View style={styles.balanceHeader}>
              <View style={styles.balanceIconContainer}>
                <IconSymbol name="dollarsign.circle.fill" size={24} color={colors.primary} />
              </View>
              <View style={styles.balanceTextContainer}>
                <Text style={styles.balanceLabel}>Tunggakan</Text>
                <Text style={styles.balanceAmount}>{formatCurrency(outstandingBalance)}</Text>
              </View>
            </View>
            {outstandingBalance === 0 && (
              <View style={styles.statusBadge}>
                <IconSymbol name="checkmark.circle.fill" size={16} color="#4CAF50" />
                <Text style={styles.statusText}>Semua pembayaran lunas!</Text>
              </View>
            )}
          </View>

          {/* Kost Info Card */}
          {kostInfo && (
            <View style={[commonStyles.card, styles.kostInfoCard]}>
              <View style={styles.kostInfoHeader}>
                <IconSymbol name="house.fill" size={24} color={colors.primary} />
                <Text style={styles.kostInfoTitle}>Informasi Kost</Text>
              </View>
              <View style={styles.kostInfoRow}>
                <Text style={styles.kostInfoLabel}>Pemilik:</Text>
                <Text style={styles.kostInfoValue}>{kostInfo.ownerName}</Text>
              </View>
              <View style={styles.kostInfoRow}>
                <Text style={styles.kostInfoLabel}>Telepon:</Text>
                <Text style={styles.kostInfoValue}>{kostInfo.ownerPhone}</Text>
              </View>
              <View style={styles.kostInfoRow}>
                <Text style={styles.kostInfoLabel}>Alamat:</Text>
                <Text style={styles.kostInfoValue}>{kostInfo.address}</Text>
              </View>
            </View>
          )}

          {/* Recent Payments Section */}
          <View style={styles.sectionHeader}>
            <Text style={styles.sectionTitle}>Pembayaran Terakhir</Text>
            <Pressable onPress={() => console.log('Lihat semua pressed')}>
              <Text style={styles.viewAllText}>Lihat Semua</Text>
            </Pressable>
          </View>

          {recentPayments.length > 0 ? (
            recentPayments.map((payment) => (
              <View key={payment.id} style={[commonStyles.card, styles.paymentCard]}>
                <View style={styles.paymentIconContainer}>
                  <IconSymbol 
                    name={payment.status === 'lunas' ? 'checkmark.circle.fill' : 'clock.fill'} 
                    size={28} 
                    color={payment.status === 'lunas' ? '#4CAF50' : '#FF9800'} 
                  />
                </View>
                <View style={styles.paymentDetails}>
                  <Text style={styles.paymentDate}>{payment.month} {payment.year}</Text>
                  <Text style={styles.paymentStatus}>
                    {payment.status === 'lunas' ? 'Lunas' : payment.status === 'pending' ? 'Pending' : 'Gagal'}
                  </Text>
                </View>
                <Text style={styles.paymentAmount}>{formatCurrency(payment.amount)}</Text>
              </View>
            ))
          ) : (
            <View style={[commonStyles.card, styles.emptyCard]}>
              <Text style={styles.emptyText}>Belum ada riwayat pembayaran</Text>
            </View>
          )}

          {/* Quick Actions */}
          <View style={styles.sectionHeader}>
            <Text style={styles.sectionTitle}>Aksi Cepat</Text>
          </View>

          <View style={styles.quickActionsContainer}>
            <Pressable 
              style={[commonStyles.card, styles.quickActionCard]}
              onPress={() => console.log('Hubungi pemilik pressed')}
            >
              <View style={[styles.quickActionIcon, { backgroundColor: colors.secondary }]}>
                <IconSymbol name="phone.fill" size={24} color={colors.primary} />
              </View>
              <Text style={styles.quickActionText}>Hubungi Pemilik</Text>
            </Pressable>

            <Pressable 
              style={[commonStyles.card, styles.quickActionCard]}
              onPress={() => console.log('Unduh bukti pressed')}
            >
              <View style={[styles.quickActionIcon, { backgroundColor: colors.secondary }]}>
                <IconSymbol name="arrow.down.doc" size={24} color={colors.primary} />
              </View>
              <Text style={styles.quickActionText}>Unduh Bukti</Text>
            </Pressable>
          </View>
        </ScrollView>
      </View>
    </>
  );
}

const styles = StyleSheet.create({
  loadingContainer: {
    justifyContent: 'center',
    alignItems: 'center',
  },
  loadingText: {
    marginTop: 16,
    fontSize: 16,
    color: colors.textSecondary,
  },
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
  welcomeSection: {
    marginBottom: 24,
  },
  welcomeText: {
    fontSize: 28,
    fontWeight: '700',
    color: colors.text,
    marginBottom: 4,
  },
  welcomeSubtext: {
    fontSize: 16,
    color: colors.textSecondary,
  },
  rentDueCard: {
    padding: 0,
    overflow: 'hidden',
    marginBottom: 16,
  },
  gradientCard: {
    padding: 20,
    borderRadius: 12,
  },
  cardHeader: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 16,
  },
  cardLabel: {
    fontSize: 16,
    color: '#FFFFFF',
    marginLeft: 12,
    fontWeight: '600',
  },
  rentAmount: {
    fontSize: 42,
    fontWeight: '800',
    color: '#FFFFFF',
    marginBottom: 4,
  },
  rentDueDate: {
    fontSize: 16,
    color: 'rgba(255, 255, 255, 0.9)',
    marginBottom: 20,
  },
  payButton: {
    backgroundColor: '#FFFFFF',
    paddingVertical: 14,
    paddingHorizontal: 24,
    borderRadius: 8,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    gap: 8,
  },
  payButtonText: {
    fontSize: 16,
    fontWeight: '700',
    color: colors.primary,
  },
  balanceCard: {
    marginBottom: 16,
  },
  balanceHeader: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 12,
  },
  balanceIconContainer: {
    width: 48,
    height: 48,
    borderRadius: 24,
    backgroundColor: colors.secondary,
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 12,
  },
  balanceTextContainer: {
    flex: 1,
  },
  balanceLabel: {
    fontSize: 14,
    color: colors.textSecondary,
    marginBottom: 4,
  },
  balanceAmount: {
    fontSize: 28,
    fontWeight: '700',
    color: colors.text,
  },
  statusBadge: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#E8F5E9',
    paddingVertical: 8,
    paddingHorizontal: 12,
    borderRadius: 8,
    alignSelf: 'flex-start',
    gap: 6,
  },
  statusText: {
    fontSize: 14,
    color: '#4CAF50',
    fontWeight: '600',
  },
  kostInfoCard: {
    marginBottom: 24,
    backgroundColor: colors.secondary,
  },
  kostInfoHeader: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 16,
    gap: 8,
  },
  kostInfoTitle: {
    fontSize: 18,
    fontWeight: '700',
    color: colors.text,
  },
  kostInfoRow: {
    flexDirection: 'row',
    marginBottom: 8,
  },
  kostInfoLabel: {
    fontSize: 14,
    color: colors.textSecondary,
    width: 80,
  },
  kostInfoValue: {
    flex: 1,
    fontSize: 14,
    color: colors.text,
    fontWeight: '500',
  },
  sectionHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 12,
    marginTop: 8,
  },
  sectionTitle: {
    fontSize: 20,
    fontWeight: '700',
    color: colors.text,
  },
  viewAllText: {
    fontSize: 14,
    color: colors.primary,
    fontWeight: '600',
  },
  paymentCard: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 8,
  },
  paymentIconContainer: {
    marginRight: 12,
  },
  paymentDetails: {
    flex: 1,
  },
  paymentDate: {
    fontSize: 16,
    fontWeight: '600',
    color: colors.text,
    marginBottom: 2,
  },
  paymentStatus: {
    fontSize: 14,
    color: colors.textSecondary,
  },
  paymentAmount: {
    fontSize: 18,
    fontWeight: '700',
    color: colors.text,
  },
  emptyCard: {
    alignItems: 'center',
    paddingVertical: 32,
  },
  emptyText: {
    fontSize: 14,
    color: colors.textSecondary,
  },
  quickActionsContainer: {
    flexDirection: 'row',
    gap: 12,
    marginBottom: 16,
  },
  quickActionCard: {
    flex: 1,
    alignItems: 'center',
    paddingVertical: 20,
  },
  quickActionIcon: {
    width: 56,
    height: 56,
    borderRadius: 28,
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: 12,
  },
  quickActionText: {
    fontSize: 14,
    fontWeight: '600',
    color: colors.text,
    textAlign: 'center',
  },
});
