
import React, { useState } from "react";
import { Stack } from "expo-router";
import { ScrollView, StyleSheet, View, Text, Pressable, Platform } from "react-native";
import { IconSymbol } from "@/components/IconSymbol";
import { colors, commonStyles } from "@/styles/commonStyles";

interface Payment {
  id: string;
  date: string;
  amount: string;
  status: 'paid' | 'pending' | 'failed';
  method: string;
  confirmationNumber: string;
}

export default function PaymentHistoryScreen() {
  const [selectedYear, setSelectedYear] = useState('2024');
  
  const payments: Payment[] = [
    {
      id: '1',
      date: 'February 1, 2024',
      amount: '$1,500.00',
      status: 'paid',
      method: 'Visa •••• 4242',
      confirmationNumber: 'PAY-2024-02-001',
    },
    {
      id: '2',
      date: 'January 1, 2024',
      amount: '$1,500.00',
      status: 'paid',
      method: 'Visa •••• 4242',
      confirmationNumber: 'PAY-2024-01-001',
    },
    {
      id: '3',
      date: 'December 1, 2023',
      amount: '$1,500.00',
      status: 'paid',
      method: 'Chase Bank •••• 1234',
      confirmationNumber: 'PAY-2023-12-001',
    },
    {
      id: '4',
      date: 'November 1, 2023',
      amount: '$1,500.00',
      status: 'paid',
      method: 'Visa •••• 4242',
      confirmationNumber: 'PAY-2023-11-001',
    },
  ];

  const getStatusColor = (status: Payment['status']) => {
    switch (status) {
      case 'paid':
        return '#4CAF50';
      case 'pending':
        return '#FF9800';
      case 'failed':
        return '#F44336';
      default:
        return colors.textSecondary;
    }
  };

  const getStatusIcon = (status: Payment['status']) => {
    switch (status) {
      case 'paid':
        return 'checkmark.circle.fill';
      case 'pending':
        return 'clock.fill';
      case 'failed':
        return 'xmark.circle.fill';
      default:
        return 'circle';
    }
  };

  const totalPaid = payments
    .filter(p => p.status === 'paid')
    .reduce((sum, p) => sum + parseFloat(p.amount.replace('$', '').replace(',', '')), 0);

  return (
    <>
      {Platform.OS === 'ios' && (
        <Stack.Screen
          options={{
            title: "Payment History",
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
              <Text style={styles.summaryTitle}>Total Paid in {selectedYear}</Text>
            </View>
            <Text style={styles.summaryAmount}>${totalPaid.toLocaleString()}</Text>
            <View style={styles.summaryStats}>
              <View style={styles.statItem}>
                <Text style={styles.statValue}>{payments.filter(p => p.status === 'paid').length}</Text>
                <Text style={styles.statLabel}>Payments</Text>
              </View>
              <View style={styles.statDivider} />
              <View style={styles.statItem}>
                <Text style={styles.statValue}>$1,500</Text>
                <Text style={styles.statLabel}>Avg. Payment</Text>
              </View>
            </View>
          </View>

          {/* Filter Section */}
          <View style={styles.filterSection}>
            <Text style={styles.sectionTitle}>Payment History</Text>
            <View style={styles.filterButtons}>
              <Pressable 
                style={[
                  styles.filterButton,
                  selectedYear === '2024' && styles.filterButtonActive
                ]}
                onPress={() => setSelectedYear('2024')}
              >
                <Text style={[
                  styles.filterButtonText,
                  selectedYear === '2024' && styles.filterButtonTextActive
                ]}>
                  2024
                </Text>
              </Pressable>
              <Pressable 
                style={[
                  styles.filterButton,
                  selectedYear === '2023' && styles.filterButtonActive
                ]}
                onPress={() => setSelectedYear('2023')}
              >
                <Text style={[
                  styles.filterButtonText,
                  selectedYear === '2023' && styles.filterButtonTextActive
                ]}>
                  2023
                </Text>
              </Pressable>
            </View>
          </View>

          {/* Payments List */}
          {payments.map((payment) => (
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
                  <Text style={styles.paymentDate}>{payment.date}</Text>
                  <Text style={styles.paymentMethod}>{payment.method}</Text>
                </View>
                <Text style={styles.paymentAmount}>{payment.amount}</Text>
              </View>

              <View style={styles.paymentFooter}>
                <View style={styles.confirmationContainer}>
                  <IconSymbol name="number" size={14} color={colors.textSecondary} />
                  <Text style={styles.confirmationNumber}>{payment.confirmationNumber}</Text>
                </View>
                <Pressable 
                  style={styles.downloadButton}
                  onPress={() => console.log('Download receipt:', payment.id)}
                >
                  <IconSymbol name="arrow.down.doc" size={16} color={colors.primary} />
                  <Text style={styles.downloadButtonText}>Receipt</Text>
                </Pressable>
              </View>
            </View>
          ))}

          {/* Export Section */}
          <View style={styles.exportSection}>
            <Text style={styles.exportTitle}>Need a full report?</Text>
            <Pressable 
              style={styles.exportButton}
              onPress={() => console.log('Export all payments')}
            >
              <IconSymbol name="square.and.arrow.up" size={20} color="#FFFFFF" />
              <Text style={styles.exportButtonText}>Export All Payments</Text>
            </Pressable>
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
    fontSize: 42,
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
    fontSize: 24,
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
  paymentAmount: {
    fontSize: 20,
    fontWeight: '700',
    color: colors.text,
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
