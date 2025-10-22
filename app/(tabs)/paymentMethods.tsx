
import React, { useState, useEffect } from "react";
import { Stack } from "expo-router";
import { ScrollView, StyleSheet, View, Text, Pressable, Platform, Alert, ActivityIndicator, TextInput, Modal } from "react-native";
import { IconSymbol } from "@/components/IconSymbol";
import { colors, commonStyles } from "@/styles/commonStyles";
import { getPaymentMethods, setDefaultPaymentMethod, deletePaymentMethod, savePaymentMethod, PaymentMethod } from "@/utils/database";

export default function PaymentMethodsScreen() {
  const [loading, setLoading] = useState(true);
  const [paymentMethods, setPaymentMethods] = useState<PaymentMethod[]>([]);
  const [showAddModal, setShowAddModal] = useState(false);
  const [newMethodType, setNewMethodType] = useState<'card' | 'bank' | 'ewallet'>('bank');
  const [newMethodName, setNewMethodName] = useState('');
  const [newMethodLast4, setNewMethodLast4] = useState('');
  const [saving, setSaving] = useState(false);

  useEffect(() => {
    loadPaymentMethods();
  }, []);

  const loadPaymentMethods = async () => {
    try {
      setLoading(true);
      const data = await getPaymentMethods();
      setPaymentMethods(data);
      console.log('Payment methods loaded:', data.length);
    } catch (error) {
      console.error('Error loading payment methods:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleAddPaymentMethod = () => {
    setShowAddModal(true);
    setNewMethodType('bank');
    setNewMethodName('');
    setNewMethodLast4('');
  };

  const handleSaveNewMethod = async () => {
    if (!newMethodName.trim()) {
      Alert.alert('Error', 'Mohon isi nama metode pembayaran');
      return;
    }

    if (!newMethodLast4.trim() || newMethodLast4.length < 4) {
      Alert.alert('Error', 'Mohon isi 4 digit terakhir dengan benar');
      return;
    }

    try {
      setSaving(true);
      const isFirstMethod = paymentMethods.length === 0;
      
      await savePaymentMethod({
        type: newMethodType,
        name: newMethodName.trim(),
        last4: newMethodLast4.trim(),
        is_default: isFirstMethod,
      });

      await loadPaymentMethods();
      setShowAddModal(false);
      Alert.alert('Berhasil', 'Metode pembayaran berhasil ditambahkan');
      console.log('Payment method added successfully');
    } catch (error) {
      console.error('Error saving payment method:', error);
      Alert.alert('Error', 'Gagal menambahkan metode pembayaran');
    } finally {
      setSaving(false);
    }
  };

  const handleSetDefault = async (id: string) => {
    try {
      await setDefaultPaymentMethod(id);
      await loadPaymentMethods();
      console.log('Default payment method set:', id);
    } catch (error) {
      console.error('Error setting default payment method:', error);
      Alert.alert('Error', 'Gagal mengatur metode pembayaran default');
    }
  };

  const handleRemove = (id: string) => {
    Alert.alert(
      'Hapus Metode Pembayaran',
      'Apakah Anda yakin ingin menghapus metode pembayaran ini?',
      [
        { text: 'Batal', style: 'cancel' },
        {
          text: 'Hapus',
          style: 'destructive',
          onPress: async () => {
            try {
              await deletePaymentMethod(id);
              await loadPaymentMethods();
              console.log('Payment method removed:', id);
            } catch (error) {
              console.error('Error removing payment method:', error);
              Alert.alert('Error', 'Gagal menghapus metode pembayaran');
            }
          },
        },
      ]
    );
  };

  const getMethodIcon = (type: PaymentMethod['type']) => {
    switch (type) {
      case 'card':
        return 'creditcard.fill';
      case 'bank':
        return 'building.columns.fill';
      case 'ewallet':
        return 'wallet.pass.fill';
      default:
        return 'creditcard.fill';
    }
  };

  const getMethodColor = (type: PaymentMethod['type']) => {
    switch (type) {
      case 'card':
        return colors.secondary;
      case 'bank':
        return colors.highlight;
      case 'ewallet':
        return '#E1F5FE';
      default:
        return colors.secondary;
    }
  };

  if (loading) {
    return (
      <View style={[commonStyles.container, styles.loadingContainer]}>
        <ActivityIndicator size="large" color={colors.primary} />
        <Text style={styles.loadingText}>Memuat metode pembayaran...</Text>
      </View>
    );
  }

  return (
    <>
      {Platform.OS === 'ios' && (
        <Stack.Screen
          options={{
            title: "Metode Pembayaran",
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
            <Text style={styles.headerTitle}>Kelola Metode Pembayaran</Text>
            <Text style={styles.headerSubtext}>
              Tambah atau hapus metode pembayaran untuk kost Anda
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
            <Text style={styles.addButtonText}>Tambah Metode Pembayaran Baru</Text>
            <IconSymbol name="chevron.right" size={20} color={colors.textSecondary} />
          </Pressable>

          {/* Payment Methods List */}
          <View style={styles.sectionHeader}>
            <Text style={styles.sectionTitle}>Metode Pembayaran Anda</Text>
          </View>

          {paymentMethods.length > 0 ? (
            paymentMethods.map((method) => (
              <View key={method.id} style={[commonStyles.card, styles.methodCard]}>
                <View style={styles.methodHeader}>
                  <View style={[
                    styles.methodIcon,
                    { backgroundColor: getMethodColor(method.type) }
                  ]}>
                    <IconSymbol 
                      name={getMethodIcon(method.type)} 
                      size={24} 
                      color={colors.primary} 
                    />
                  </View>
                  <View style={styles.methodDetails}>
                    <Text style={styles.methodName}>{method.name}</Text>
                    <Text style={styles.methodNumber}>
                      {method.type === 'card' ? '•••• ' : method.type === 'ewallet' ? '' : 'Rekening ••••'}
                      {method.last4}
                    </Text>
                  </View>
                  {method.is_default && (
                    <View style={styles.defaultBadge}>
                      <Text style={styles.defaultBadgeText}>Default</Text>
                    </View>
                  )}
                </View>

                <View style={styles.methodActions}>
                  {!method.is_default && (
                    <Pressable 
                      style={styles.actionButton}
                      onPress={() => handleSetDefault(method.id)}
                    >
                      <IconSymbol name="star" size={18} color={colors.primary} />
                      <Text style={styles.actionButtonText}>Jadikan Default</Text>
                    </Pressable>
                  )}
                  <Pressable 
                    style={[styles.actionButton, styles.removeButton]}
                    onPress={() => handleRemove(method.id)}
                  >
                    <IconSymbol name="trash" size={18} color={colors.accent} />
                    <Text style={[styles.actionButtonText, styles.removeButtonText]}>Hapus</Text>
                  </Pressable>
                </View>
              </View>
            ))
          ) : (
            <View style={[commonStyles.card, styles.emptyCard]}>
              <IconSymbol name="creditcard" size={48} color={colors.textSecondary} />
              <Text style={styles.emptyText}>Belum ada metode pembayaran</Text>
            </View>
          )}

          {/* Security Notice */}
          <View style={styles.securityNotice}>
            <IconSymbol name="lock.shield.fill" size={24} color={colors.primary} />
            <View style={styles.securityTextContainer}>
              <Text style={styles.securityTitle}>Aman & Terenkripsi</Text>
              <Text style={styles.securityText}>
                Informasi pembayaran Anda dienkripsi dan disimpan dengan aman
              </Text>
            </View>
          </View>
        </ScrollView>

        {/* Add Payment Method Modal */}
        <Modal
          visible={showAddModal}
          animationType="slide"
          transparent={true}
          onRequestClose={() => setShowAddModal(false)}
        >
          <View style={styles.modalOverlay}>
            <View style={styles.modalContent}>
              <View style={styles.modalHeader}>
                <Text style={styles.modalTitle}>Tambah Metode Pembayaran</Text>
                <Pressable onPress={() => setShowAddModal(false)}>
                  <IconSymbol name="xmark.circle.fill" size={28} color={colors.textSecondary} />
                </Pressable>
              </View>

              <View style={styles.modalBody}>
                <Text style={styles.inputLabel}>Tipe Metode</Text>
                <View style={styles.typeSelector}>
                  <Pressable
                    style={[styles.typeButton, newMethodType === 'bank' && styles.typeButtonActive]}
                    onPress={() => setNewMethodType('bank')}
                  >
                    <IconSymbol name="building.columns.fill" size={20} color={newMethodType === 'bank' ? '#FFFFFF' : colors.primary} />
                    <Text style={[styles.typeButtonText, newMethodType === 'bank' && styles.typeButtonTextActive]}>Bank</Text>
                  </Pressable>
                  <Pressable
                    style={[styles.typeButton, newMethodType === 'card' && styles.typeButtonActive]}
                    onPress={() => setNewMethodType('card')}
                  >
                    <IconSymbol name="creditcard.fill" size={20} color={newMethodType === 'card' ? '#FFFFFF' : colors.primary} />
                    <Text style={[styles.typeButtonText, newMethodType === 'card' && styles.typeButtonTextActive]}>Kartu</Text>
                  </Pressable>
                  <Pressable
                    style={[styles.typeButton, newMethodType === 'ewallet' && styles.typeButtonActive]}
                    onPress={() => setNewMethodType('ewallet')}
                  >
                    <IconSymbol name="wallet.pass.fill" size={20} color={newMethodType === 'ewallet' ? '#FFFFFF' : colors.primary} />
                    <Text style={[styles.typeButtonText, newMethodType === 'ewallet' && styles.typeButtonTextActive]}>E-Wallet</Text>
                  </Pressable>
                </View>

                <Text style={styles.inputLabel}>Nama {newMethodType === 'bank' ? 'Bank' : newMethodType === 'card' ? 'Kartu' : 'E-Wallet'}</Text>
                <TextInput
                  style={styles.textInput}
                  placeholder={`Contoh: ${newMethodType === 'bank' ? 'BCA' : newMethodType === 'card' ? 'Visa' : 'GoPay'}`}
                  placeholderTextColor={colors.textSecondary}
                  value={newMethodName}
                  onChangeText={setNewMethodName}
                  editable={!saving}
                />

                <Text style={styles.inputLabel}>4 Digit Terakhir</Text>
                <TextInput
                  style={styles.textInput}
                  placeholder="1234"
                  placeholderTextColor={colors.textSecondary}
                  value={newMethodLast4}
                  onChangeText={setNewMethodLast4}
                  keyboardType="number-pad"
                  maxLength={4}
                  editable={!saving}
                />
              </View>

              <View style={styles.modalFooter}>
                <Pressable
                  style={[styles.modalButton, styles.modalButtonCancel]}
                  onPress={() => setShowAddModal(false)}
                  disabled={saving}
                >
                  <Text style={styles.modalButtonTextCancel}>Batal</Text>
                </Pressable>
                <Pressable
                  style={[styles.modalButton, styles.modalButtonSave, saving && styles.modalButtonDisabled]}
                  onPress={handleSaveNewMethod}
                  disabled={saving}
                >
                  {saving ? (
                    <ActivityIndicator color="#FFFFFF" />
                  ) : (
                    <Text style={styles.modalButtonTextSave}>Simpan</Text>
                  )}
                </Pressable>
              </View>
            </View>
          </View>
        </Modal>
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
  emptyCard: {
    alignItems: 'center',
    paddingVertical: 48,
  },
  emptyText: {
    fontSize: 14,
    color: colors.textSecondary,
    marginTop: 12,
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
  modalOverlay: {
    flex: 1,
    backgroundColor: 'rgba(0, 0, 0, 0.5)',
    justifyContent: 'flex-end',
  },
  modalContent: {
    backgroundColor: colors.background,
    borderTopLeftRadius: 24,
    borderTopRightRadius: 24,
    paddingTop: 24,
    paddingBottom: Platform.OS === 'ios' ? 40 : 24,
    maxHeight: '80%',
  },
  modalHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: 24,
    marginBottom: 24,
  },
  modalTitle: {
    fontSize: 22,
    fontWeight: '700',
    color: colors.text,
  },
  modalBody: {
    paddingHorizontal: 24,
    marginBottom: 24,
  },
  inputLabel: {
    fontSize: 14,
    fontWeight: '600',
    color: colors.text,
    marginBottom: 8,
    marginTop: 16,
  },
  typeSelector: {
    flexDirection: 'row',
    gap: 8,
  },
  typeButton: {
    flex: 1,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    backgroundColor: colors.secondary,
    paddingVertical: 12,
    borderRadius: 8,
    gap: 6,
  },
  typeButtonActive: {
    backgroundColor: colors.primary,
  },
  typeButtonText: {
    fontSize: 14,
    fontWeight: '600',
    color: colors.primary,
  },
  typeButtonTextActive: {
    color: '#FFFFFF',
  },
  textInput: {
    backgroundColor: colors.card,
    borderRadius: 8,
    paddingVertical: 12,
    paddingHorizontal: 16,
    fontSize: 16,
    color: colors.text,
    borderWidth: 1,
    borderColor: colors.secondary,
  },
  modalFooter: {
    flexDirection: 'row',
    paddingHorizontal: 24,
    gap: 12,
  },
  modalButton: {
    flex: 1,
    paddingVertical: 14,
    borderRadius: 8,
    alignItems: 'center',
    justifyContent: 'center',
  },
  modalButtonCancel: {
    backgroundColor: colors.secondary,
  },
  modalButtonSave: {
    backgroundColor: colors.primary,
  },
  modalButtonDisabled: {
    opacity: 0.6,
  },
  modalButtonTextCancel: {
    fontSize: 16,
    fontWeight: '600',
    color: colors.text,
  },
  modalButtonTextSave: {
    fontSize: 16,
    fontWeight: '600',
    color: '#FFFFFF',
  },
});
