
import React from "react";
import { Stack } from "expo-router";
import { ScrollView, StyleSheet, View, Text, Pressable, Platform } from "react-native";
import { IconSymbol } from "@/components/IconSymbol";
import { colors, commonStyles } from "@/styles/commonStyles";
import { LinearGradient } from "expo-linear-gradient";

export default function HomeScreen() {
  const rentDueDate = "March 1, 2024";
  const rentAmount = "$1,500";
  const outstandingBalance = "$0";
  
  const recentPayments = [
    { id: '1', date: 'Feb 1, 2024', amount: '$1,500', status: 'Paid' },
    { id: '2', date: 'Jan 1, 2024', amount: '$1,500', status: 'Paid' },
    { id: '3', date: 'Dec 1, 2023', amount: '$1,500', status: 'Paid' },
  ];

  return (
    <>
      {Platform.OS === 'ios' && (
        <Stack.Screen
          options={{
            title: "Dashboard",
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
            <Text style={styles.welcomeText}>Welcome back!</Text>
            <Text style={styles.welcomeSubtext}>Here&apos;s your payment overview</Text>
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
                <Text style={styles.cardLabel}>Next Payment Due</Text>
              </View>
              <Text style={styles.rentAmount}>{rentAmount}</Text>
              <Text style={styles.rentDueDate}>Due on {rentDueDate}</Text>
              
              <Pressable 
                style={styles.payButton}
                onPress={() => console.log('Pay now pressed')}
              >
                <Text style={styles.payButtonText}>Pay Now</Text>
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
                <Text style={styles.balanceLabel}>Outstanding Balance</Text>
                <Text style={styles.balanceAmount}>{outstandingBalance}</Text>
              </View>
            </View>
            {outstandingBalance === "$0" && (
              <View style={styles.statusBadge}>
                <IconSymbol name="checkmark.circle.fill" size={16} color="#4CAF50" />
                <Text style={styles.statusText}>All caught up!</Text>
              </View>
            )}
          </View>

          {/* Recent Payments Section */}
          <View style={styles.sectionHeader}>
            <Text style={styles.sectionTitle}>Recent Payments</Text>
            <Pressable onPress={() => console.log('View all pressed')}>
              <Text style={styles.viewAllText}>View All</Text>
            </Pressable>
          </View>

          {recentPayments.map((payment) => (
            <View key={payment.id} style={[commonStyles.card, styles.paymentCard]}>
              <View style={styles.paymentIconContainer}>
                <IconSymbol name="checkmark.circle.fill" size={28} color="#4CAF50" />
              </View>
              <View style={styles.paymentDetails}>
                <Text style={styles.paymentDate}>{payment.date}</Text>
                <Text style={styles.paymentStatus}>{payment.status}</Text>
              </View>
              <Text style={styles.paymentAmount}>{payment.amount}</Text>
            </View>
          ))}

          {/* Quick Actions */}
          <View style={styles.sectionHeader}>
            <Text style={styles.sectionTitle}>Quick Actions</Text>
          </View>

          <View style={styles.quickActionsContainer}>
            <Pressable 
              style={[commonStyles.card, styles.quickActionCard]}
              onPress={() => console.log('Set up auto-pay pressed')}
            >
              <View style={[styles.quickActionIcon, { backgroundColor: colors.secondary }]}>
                <IconSymbol name="arrow.clockwise" size={24} color={colors.primary} />
              </View>
              <Text style={styles.quickActionText}>Set Up Auto-Pay</Text>
            </Pressable>

            <Pressable 
              style={[commonStyles.card, styles.quickActionCard]}
              onPress={() => console.log('Download receipt pressed')}
            >
              <View style={[styles.quickActionIcon, { backgroundColor: colors.secondary }]}>
                <IconSymbol name="arrow.down.doc" size={24} color={colors.primary} />
              </View>
              <Text style={styles.quickActionText}>Download Receipt</Text>
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
    fontSize: 48,
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
    marginBottom: 24,
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
