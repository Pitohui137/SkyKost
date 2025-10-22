
import React, { useState } from "react";
import { Stack } from "expo-router";
import { ScrollView, StyleSheet, View, Text, Pressable, Platform, TextInput, Alert } from "react-native";
import { IconSymbol } from "@/components/IconSymbol";
import { colors, commonStyles } from "@/styles/commonStyles";

interface PaymentMethod {
  id: string;
  type: 'card' | 'bank';
  name: string;
  last4: string;
  isDefault: boolean;
}

export default function PaymentMethodsScreen() {
  const [paymentMethods, setPaymentMethods] = useState<PaymentMethod[]>([
    { id: '1', type: 'card', name: 'Visa', last4: '4242', isDefault: true },
    { id: '2', type: 'bank', name: 'Chase Bank', last4: '1234', isDefault: false },
  ]);

  const handleAddPaymentMethod = () => {
    Alert.alert(
      'Add Payment Method',
      'This feature would open a form to add a new payment method.',
      [{ text: 'OK' }]
    );
    console.log('Add payment method pressed');
  };

  const handleSetDefault = (id: string) => {
    setPaymentMethods(methods =>
      methods.map(method => ({
        ...method,
        isDefault: method.id === id,
      }))
    );
    console.log('Set default payment method:', id);
  };

  const handleRemove = (id: string) => {
    Alert.alert(
      'Remove Payment Method',
      'Are you sure you want to remove this payment method?',
      [
        { text: 'Cancel', style: 'cancel' },
        {
          text: 'Remove',
          style: 'destructive',
          onPress: () => {
            setPaymentMethods(methods => methods.filter(method => method.id !== id));
            console.log('Removed payment method:', id);
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
            title: "Payment Methods",
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
          {/* Header Section */}
          <View style={styles.headerSection}>
            <Text style={styles.headerTitle}>Manage Payment Methods</Text>
            <Text style={styles.headerSubtext}>
              Add or remove payment methods for your rent payments
            </Text>
          </View>

          {/* Add Payment Method Button */}
          <Pressable 
            style={styles.addButton}
            onPress={handleAddPaymentMethod}
          >
            <View style={styles.addButtonIcon}>
              <IconSymbol name="plus" size={24} color={colors.primary} />
            </View>
            <Text style={styles.addButtonText}>Add New Payment Method</Text>
            <IconSymbol name="chevron.right" size={20} color={colors.textSecondary} />
          </Pressable>

          {/* Payment Methods List */}
          <View style={styles.sectionHeader}>
            <Text style={styles.sectionTitle}>Your Payment Methods</Text>
          </View>

          {paymentMethods.map((method) => (
            <View key={method.id} style={[commonStyles.card, styles.methodCard]}>
              <View style={styles.methodHeader}>
                <View style={[
                  styles.methodIcon,
                  { backgroundColor: method.type === 'card' ? colors.secondary : colors.highlight }
                ]}>
                  <IconSymbol 
                    name={method.type === 'card' ? 'creditcard.fill' : 'building.columns.fill'} 
                    size={24} 
                    color={colors.primary} 
                  />
                </View>
                <View style={styles.methodDetails}>
                  <Text style={styles.methodName}>{method.name}</Text>
                  <Text style={styles.methodNumber}>
                    {method.type === 'card' ? '•••• ' : 'Account ••••'}
                    {method.last4}
                  </Text>
                </View>
                {method.isDefault && (
                  <View style={styles.defaultBadge}>
                    <Text style={styles.defaultBadgeText}>Default</Text>
                  </View>
                )}
              </View>

              <View style={styles.methodActions}>
                {!method.isDefault && (
                  <Pressable 
                    style={styles.actionButton}
                    onPress={() => handleSetDefault(method.id)}
                  >
                    <IconSymbol name="star" size={18} color={colors.primary} />
                    <Text style={styles.actionButtonText}>Set as Default</Text>
                  </Pressable>
                )}
                <Pressable 
                  style={[styles.actionButton, styles.removeButton]}
                  onPress={() => handleRemove(method.id)}
                >
                  <IconSymbol name="trash" size={18} color={colors.accent} />
                  <Text style={[styles.actionButtonText, styles.removeButtonText]}>Remove</Text>
                </Pressable>
              </View>
            </View>
          ))}

          {/* Security Notice */}
          <View style={styles.securityNotice}>
            <IconSymbol name="lock.shield.fill" size={24} color={colors.primary} />
            <View style={styles.securityTextContainer}>
              <Text style={styles.securityTitle}>Secure & Encrypted</Text>
              <Text style={styles.securityText}>
                Your payment information is encrypted and stored securely
              </Text>
            </View>
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
  headerSection: {
    marginBottom: 24,
  },
  headerTitle: {
    fontSize: 28,
    fontWeight: '700',
    color: colors.text,
    marginBottom: 8,
  },
  headerSubtext: {
    fontSize: 16,
    color: colors.textSecondary,
    lineHeight: 22,
  },
  addButton: {
    backgroundColor: colors.card,
    borderRadius: 12,
    padding: 16,
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 24,
    boxShadow: '0px 2px 8px rgba(0, 0, 0, 0.1)',
    elevation: 3,
    borderWidth: 2,
    borderColor: colors.secondary,
    borderStyle: 'dashed',
  },
  addButtonIcon: {
    width: 48,
    height: 48,
    borderRadius: 24,
    backgroundColor: colors.secondary,
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 12,
  },
  addButtonText: {
    flex: 1,
    fontSize: 16,
    fontWeight: '600',
    color: colors.text,
  },
  sectionHeader: {
    marginBottom: 12,
  },
  sectionTitle: {
    fontSize: 20,
    fontWeight: '700',
    color: colors.text,
  },
  methodCard: {
    marginBottom: 12,
  },
  methodHeader: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 16,
  },
  methodIcon: {
    width: 56,
    height: 56,
    borderRadius: 28,
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 12,
  },
  methodDetails: {
    flex: 1,
  },
  methodName: {
    fontSize: 18,
    fontWeight: '600',
    color: colors.text,
    marginBottom: 4,
  },
  methodNumber: {
    fontSize: 14,
    color: colors.textSecondary,
  },
  defaultBadge: {
    backgroundColor: colors.primary,
    paddingVertical: 4,
    paddingHorizontal: 12,
    borderRadius: 12,
  },
  defaultBadgeText: {
    fontSize: 12,
    fontWeight: '600',
    color: '#FFFFFF',
  },
  methodActions: {
    flexDirection: 'row',
    gap: 8,
  },
  actionButton: {
    flex: 1,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    backgroundColor: colors.secondary,
    paddingVertical: 10,
    paddingHorizontal: 16,
    borderRadius: 8,
    gap: 6,
  },
  actionButtonText: {
    fontSize: 14,
    fontWeight: '600',
    color: colors.primary,
  },
  removeButton: {
    backgroundColor: '#FFEBEE',
  },
  removeButtonText: {
    color: colors.accent,
  },
  securityNotice: {
    flexDirection: 'row',
    backgroundColor: colors.secondary,
    padding: 16,
    borderRadius: 12,
    marginTop: 24,
    gap: 12,
  },
  securityTextContainer: {
    flex: 1,
  },
  securityTitle: {
    fontSize: 16,
    fontWeight: '600',
    color: colors.text,
    marginBottom: 4,
  },
  securityText: {
    fontSize: 14,
    color: colors.textSecondary,
    lineHeight: 20,
  },
});
