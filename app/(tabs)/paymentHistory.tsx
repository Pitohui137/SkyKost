
import React, { useState, useEffect } from "react";
import { Stack } from "expo-router";
import { ScrollView, StyleSheet, View, Text, Pressable, Platform, ActivityIndicator } from "react-native";
import { IconSymbol } from "@/components/IconSymbol";
import { colors, commonStyles } from "@/styles/commonStyles";
import { getPayments, Payment } from "@/utils/database";

export default function PaymentHistoryScreen() {
  const [loading, setLoading] = useState(true);
  const [selectedYear, setSelectedYear] = useState('2024');
  const [payments, setPayments] = useState<Payment[]>([]);

  useEffect(() => {
    loadPayments();
  }, []);

  const loadPayments = async () => {
    try {
      setLoading(true);
      const data = await getPayments();
      setPayments(data);
      console.log('Payments loaded:', data.length);
    } catch (error) {
      console.error('Error loading payments:', error);
    } finally {
      setLoading(false);
    }
  };

  const formatCurrency = (amount: number) => {
    return `Rp ${amount.toLocaleString('id-ID')}`;
  };

  const getStatusColor = (status: Payment['status']) => {
    switch (status) {
      case 'lunas':
        return '#4CAF50';
      case 'pending':
        return '#FF9800';
      case 'gagal':
        return '#F44336';
      default:
        return colors.textSecondary;
    }
  };

  const getStatusIcon = (status: Payment['status']) => {
    switch (status) {
      case 'lunas':
        return 'checkmark.circle.fill';
      case 'pending':
        return 'clock.fill';
      case 'gagal':
        return 'xmark.circle.fill';
      default:
        return 'circle';
    }
  };

  const getStatusText = (status: Payment['status']) => {
    switch (status) {
      case 'lunas':
        return 'Lunas';
      case 'pending':
        return 'Pending';
      case 'gagal':
        return 'Gagal';
      default:
        return status;
    }
  };

  const filteredPayments = payments.filter(p => p.year === selectedYear);
  const totalPaid = filteredPayments
    .filter(p => p.status === 'lunas')
    .reduce((sum, p) => sum + p.amount, 0);

  const years = Array.from(new Set(payments.map(p => p.year))).sort().reverse();

  if (loading) {
    return (
      <View style={[commonStyles.container, styles.loadingContainer]}>
        <ActivityIndicator size="large" color={colors.primary} />
        <Text style={styles.loadingText}>Memuat riwayat...</Text>
      </View>
    );
  }

  return (
    <>
      {Platform.OS === 'ios' && (
        <Stack.Screen
          options={{
            title: "Riwayat Pembayaran",
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
          {/* Summary Card */}
          <View style={[commonStyles.card, styles.summaryCard]}>
            <View style={styles.summaryHeader}>
              <IconSymbol name="chart.bar.fill" size={32} color={colors.primary} />
              <Text style={styles.summaryTitle}>Total Dibayar {selectedYear}</Text>
            </View>
            <Text style={styles.summaryAmount}>{formatCurrency(totalPaid)}</Text>
            <View style={styles.summaryStats}>
              <View style={styles.statItem}>
                <Text style={styles.statValue}>{filteredPayments.filter(p => p.status === 'lunas').length}</Text>
                <Text style={styles.statLabel}>Pembayaran</Text>
              </View>
              <View style={styles.statDivider} />
              <View style={styles.statItem}>
                <Text style={styles.statValue}>
                  {filteredPayments.length > 0 
                    ? formatCurrency(Math.round(totalPaid / filteredPayments.filter(p => p.status === 'lunas').length))
                    : 'Rp 0'}
                </Text>
                <Text style={styles.statLabel}>Rata-rata</Text>
              </View>
            </View>
          </View>

          {/* Filter Section */}
          <View style={styles.filterSection}>
            <Text style={styles.sectionTitle}>Riwayat Pembayaran</Text>
            <View style={styles.filterButtons}>
              {years.map(year => (
                <Pressable 
                  key={year}
                  style={[
                    styles.filterButton,
                    selectedYear === year && styles.filterButtonActive
                  ]}
                  onPress={() => setSelectedYear(year)}
                >
                  <Text style={[
                    styles.filterButtonText,
                    selectedYear === year && styles.filterButtonTextActive
                  ]}>
                    {year}
                  </Text>
                </Pressable>
              ))}
            </View>
          </View>

          {/* Payments List */}
          {filteredPayments.length > 0 ? (
            filteredPayments.map((payment) => (
              <View key={payment.id} style={[commonStyles.card, styles.paymentCard]}>
                <View style={styles.paymentHeader}>
                  <View style={[
                    styles.statusIcon,
                    { backgroundColor: `${getStatusColor(payment.status)}20` }
                  ]}>
                    <IconSymbol 
                      name={getStatusIcon(payment.status)} 
                      size={28} 
                      color={getStatusColor(payment.status)} 
                    />
                  </View>
                  <View style={styles.paymentInfo}>
                    <Text style={styles.paymentDate}>{payment.month} {payment.year}</Text>
                    <Text style={styles.paymentMethod}>{payment.method}</Text>
                  </View>
                  <View style={styles.paymentRight}>
                    <Text style={styles.paymentAmount}>{formatCurrency(payment.amount)}</Text>
                    <Text style={[styles.paymentStatusText, { color: getStatusColor(payment.status) }]}>
                      {getStatusText(payment.status)}
                    </Text>
                  </View>
                </View>

                <View style={styles.paymentFooter}>
                  <View style={styles.confirmationContainer}>
                    <IconSymbol name="number" size={14} color={colors.textSecondary} />
                    <Text style={styles.confirmationNumber}>{payment.confirmationNumber}</Text>
                  </View>
                  <Pressable 
                    style={styles.downloadButton}
                    onPress={() => console.log('Unduh bukti:', payment.id)}
                  >
                    <IconSymbol name="arrow.down.doc" size={16} color={colors.primary} />
                    <Text style={styles.downloadButtonText}>Bukti</Text>
                  </Pressable>
                </View>
              </View>
            ))
          ) : (
            <View style={[commonStyles.card, styles.emptyCard]}>
              <IconSymbol name="doc.text" size={48} color={colors.textSecondary} />
              <Text style={styles.emptyText}>Tidak ada pembayaran di tahun {selectedYear}</Text>
            </View>
          )}

          {/* Export Section */}
          <View style={styles.exportSection}>
            <Text style={styles.exportTitle}>Butuh laporan lengkap?</Text>
            <Pressable 
              style={styles.exportButton}
              onPress={() => console.log('Ekspor semua pembayaran')}
            >
              <IconSymbol name="square.and.arrow.up" size={20} color="#FFFFFF" />
              <Text style={styles.exportButtonText}>Ekspor Semua Pembayaran</Text>
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
  summaryCard: {
    marginBottom: 24,
    backgroundColor: colors.secondary,
  },
  summaryHeader: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 16,
    gap: 12,
  },
  summaryTitle: {
    fontSize: 16,
    fontWeight: '600',
    color: colors.text,
  },
  summaryAmount: {
    fontSize: 38,
    fontWeight: '800',
    color: colors.primary,
    marginBottom: 20,
  },
  summaryStats: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  statItem: {
    flex: 1,
    alignItems: 'center',
  },
  statValue: {
    fontSize: 20,
    fontWeight: '700',
    color: colors.text,
    marginBottom: 4,
  },
  statLabel: {
    fontSize: 14,
    color: colors.textSecondary,
  },
  statDivider: {
    width: 1,
    height: 40,
    backgroundColor: colors.highlight,
  },
  filterSection: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 16,
  },
  sectionTitle: {
    fontSize: 20,
    fontWeight: '700',
    color: colors.text,
  },
  filterButtons: {
    flexDirection: 'row',
    gap: 8,
  },
  filterButton: {
    paddingVertical: 6,
    paddingHorizontal: 16,
    borderRadius: 16,
    backgroundColor: colors.card,
    borderWidth: 1,
    borderColor: colors.secondary,
  },
  filterButtonActive: {
    backgroundColor: colors.primary,
    borderColor: colors.primary,
  },
  filterButtonText: {
    fontSize: 14,
    fontWeight: '600',
    color: colors.textSecondary,
  },
  filterButtonTextActive: {
    color: '#FFFFFF',
  },
  paymentCard: {
    marginBottom: 12,
  },
  paymentHeader: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 12,
  },
  statusIcon: {
    width: 48,
    height: 48,
    borderRadius: 24,
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 12,
  },
  paymentInfo: {
    flex: 1,
  },
  paymentDate: {
    fontSize: 16,
    fontWeight: '600',
    color: colors.text,
    marginBottom: 4,
  },
  paymentMethod: {
    fontSize: 14,
    color: colors.textSecondary,
  },
  paymentRight: {
    alignItems: 'flex-end',
  },
  paymentAmount: {
    fontSize: 18,
    fontWeight: '700',
    color: colors.text,
    marginBottom: 2,
  },
  paymentStatusText: {
    fontSize: 12,
    fontWeight: '600',
  },
  paymentFooter: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingTop: 12,
    borderTopWidth: 1,
    borderTopColor: colors.secondary,
  },
  confirmationContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 6,
  },
  confirmationNumber: {
    fontSize: 12,
    color: colors.textSecondary,
    fontFamily: Platform.OS === 'ios' ? 'Courier' : 'monospace',
  },
  downloadButton: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 6,
    paddingVertical: 6,
    paddingHorizontal: 12,
    backgroundColor: colors.secondary,
    borderRadius: 6,
  },
  downloadButtonText: {
    fontSize: 13,
    fontWeight: '600',
    color: colors.primary,
  },
  emptyCard: {
    alignItems: 'center',
    paddingVertical: 48,
  },
  emptyText: {
    fontSize: 14,
    color: colors.textSecondary,
    marginTop: 12,
  },
  exportSection: {
    marginTop: 24,
    padding: 20,
    backgroundColor: colors.card,
    borderRadius: 12,
    alignItems: 'center',
    boxShadow: '0px 2px 8px rgba(0, 0, 0, 0.1)',
    elevation: 3,
  },
  exportTitle: {
    fontSize: 16,
    fontWeight: '600',
    color: colors.text,
    marginBottom: 16,
  },
  exportButton: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 8,
    backgroundColor: colors.primary,
    paddingVertical: 12,
    paddingHorizontal: 24,
    borderRadius: 8,
  },
  exportButtonText: {
    fontSize: 16,
    fontWeight: '600',
    color: '#FFFFFF',
  },
});
