
import AsyncStorage from '@react-native-async-storage/async-storage';

// Database keys
const KEYS = {
  PAYMENTS: '@kost_payments',
  PAYMENT_METHODS: '@kost_payment_methods',
  KOST_INFO: '@kost_info',
};

// Types
export interface Payment {
  id: string;
  date: string;
  amount: number;
  status: 'lunas' | 'pending' | 'gagal'; // paid, pending, failed
  method: string;
  confirmationNumber: string;
  month: string;
  year: string;
}

export interface PaymentMethod {
  id: string;
  type: 'card' | 'bank' | 'ewallet';
  name: string;
  last4: string;
  isDefault: boolean;
}

export interface KostInfo {
  name: string;
  address: string;
  roomNumber: string;
  monthlyRent: number;
  ownerName: string;
  ownerPhone: string;
}

// Payment operations
export const getPayments = async (): Promise<Payment[]> => {
  try {
    const data = await AsyncStorage.getItem(KEYS.PAYMENTS);
    return data ? JSON.parse(data) : [];
  } catch (error) {
    console.error('Error getting payments:', error);
    return [];
  }
};

export const savePayment = async (payment: Payment): Promise<void> => {
  try {
    const payments = await getPayments();
    const updatedPayments = [...payments, payment];
    await AsyncStorage.setItem(KEYS.PAYMENTS, JSON.stringify(updatedPayments));
    console.log('Payment saved successfully');
  } catch (error) {
    console.error('Error saving payment:', error);
    throw error;
  }
};

export const updatePayment = async (id: string, updates: Partial<Payment>): Promise<void> => {
  try {
    const payments = await getPayments();
    const updatedPayments = payments.map(p => 
      p.id === id ? { ...p, ...updates } : p
    );
    await AsyncStorage.setItem(KEYS.PAYMENTS, JSON.stringify(updatedPayments));
    console.log('Payment updated successfully');
  } catch (error) {
    console.error('Error updating payment:', error);
    throw error;
  }
};

export const deletePayment = async (id: string): Promise<void> => {
  try {
    const payments = await getPayments();
    const updatedPayments = payments.filter(p => p.id !== id);
    await AsyncStorage.setItem(KEYS.PAYMENTS, JSON.stringify(updatedPayments));
    console.log('Payment deleted successfully');
  } catch (error) {
    console.error('Error deleting payment:', error);
    throw error;
  }
};

// Payment method operations
export const getPaymentMethods = async (): Promise<PaymentMethod[]> => {
  try {
    const data = await AsyncStorage.getItem(KEYS.PAYMENT_METHODS);
    return data ? JSON.parse(data) : [];
  } catch (error) {
    console.error('Error getting payment methods:', error);
    return [];
  }
};

export const savePaymentMethod = async (method: PaymentMethod): Promise<void> => {
  try {
    const methods = await getPaymentMethods();
    const updatedMethods = [...methods, method];
    await AsyncStorage.setItem(KEYS.PAYMENT_METHODS, JSON.stringify(updatedMethods));
    console.log('Payment method saved successfully');
  } catch (error) {
    console.error('Error saving payment method:', error);
    throw error;
  }
};

export const updatePaymentMethod = async (id: string, updates: Partial<PaymentMethod>): Promise<void> => {
  try {
    const methods = await getPaymentMethods();
    const updatedMethods = methods.map(m => 
      m.id === id ? { ...m, ...updates } : m
    );
    await AsyncStorage.setItem(KEYS.PAYMENT_METHODS, JSON.stringify(updatedMethods));
    console.log('Payment method updated successfully');
  } catch (error) {
    console.error('Error updating payment method:', error);
    throw error;
  }
};

export const deletePaymentMethod = async (id: string): Promise<void> => {
  try {
    const methods = await getPaymentMethods();
    const updatedMethods = methods.filter(m => m.id !== id);
    await AsyncStorage.setItem(KEYS.PAYMENT_METHODS, JSON.stringify(updatedMethods));
    console.log('Payment method deleted successfully');
  } catch (error) {
    console.error('Error deleting payment method:', error);
    throw error;
  }
};

export const setDefaultPaymentMethod = async (id: string): Promise<void> => {
  try {
    const methods = await getPaymentMethods();
    const updatedMethods = methods.map(m => ({
      ...m,
      isDefault: m.id === id,
    }));
    await AsyncStorage.setItem(KEYS.PAYMENT_METHODS, JSON.stringify(updatedMethods));
    console.log('Default payment method set successfully');
  } catch (error) {
    console.error('Error setting default payment method:', error);
    throw error;
  }
};

// Kost info operations
export const getKostInfo = async (): Promise<KostInfo | null> => {
  try {
    const data = await AsyncStorage.getItem(KEYS.KOST_INFO);
    return data ? JSON.parse(data) : null;
  } catch (error) {
    console.error('Error getting kost info:', error);
    return null;
  }
};

export const saveKostInfo = async (info: KostInfo): Promise<void> => {
  try {
    await AsyncStorage.setItem(KEYS.KOST_INFO, JSON.stringify(info));
    console.log('Kost info saved successfully');
  } catch (error) {
    console.error('Error saving kost info:', error);
    throw error;
  }
};

// Initialize with sample data
export const initializeSampleData = async (): Promise<void> => {
  try {
    const existingPayments = await getPayments();
    const existingMethods = await getPaymentMethods();
    const existingKostInfo = await getKostInfo();

    // Initialize sample payments if none exist
    if (existingPayments.length === 0) {
      const samplePayments: Payment[] = [
        {
          id: '1',
          date: '2024-02-01',
          amount: 1500000,
          status: 'lunas',
          method: 'BCA •••• 4242',
          confirmationNumber: 'PAY-2024-02-001',
          month: 'Februari',
          year: '2024',
        },
        {
          id: '2',
          date: '2024-01-01',
          amount: 1500000,
          status: 'lunas',
          method: 'BCA •••• 4242',
          confirmationNumber: 'PAY-2024-01-001',
          month: 'Januari',
          year: '2024',
        },
        {
          id: '3',
          date: '2023-12-01',
          amount: 1500000,
          status: 'lunas',
          method: 'Mandiri •••• 1234',
          confirmationNumber: 'PAY-2023-12-001',
          month: 'Desember',
          year: '2023',
        },
      ];
      await AsyncStorage.setItem(KEYS.PAYMENTS, JSON.stringify(samplePayments));
      console.log('Sample payments initialized');
    }

    // Initialize sample payment methods if none exist
    if (existingMethods.length === 0) {
      const sampleMethods: PaymentMethod[] = [
        { id: '1', type: 'bank', name: 'BCA', last4: '4242', isDefault: true },
        { id: '2', type: 'bank', name: 'Mandiri', last4: '1234', isDefault: false },
        { id: '3', type: 'ewallet', name: 'GoPay', last4: '0856', isDefault: false },
      ];
      await AsyncStorage.setItem(KEYS.PAYMENT_METHODS, JSON.stringify(sampleMethods));
      console.log('Sample payment methods initialized');
    }

    // Initialize sample kost info if none exists
    if (!existingKostInfo) {
      const sampleKostInfo: KostInfo = {
        name: 'Kost Melati',
        address: 'Jl. Sudirman No. 123, Jakarta Selatan',
        roomNumber: 'A-205',
        monthlyRent: 1500000,
        ownerName: 'Ibu Siti',
        ownerPhone: '+62 812-3456-7890',
      };
      await AsyncStorage.setItem(KEYS.KOST_INFO, JSON.stringify(sampleKostInfo));
      console.log('Sample kost info initialized');
    }
  } catch (error) {
    console.error('Error initializing sample data:', error);
  }
};

// Clear all data (for testing)
export const clearAllData = async (): Promise<void> => {
  try {
    await AsyncStorage.multiRemove([KEYS.PAYMENTS, KEYS.PAYMENT_METHODS, KEYS.KOST_INFO]);
    console.log('All data cleared');
  } catch (error) {
    console.error('Error clearing data:', error);
    throw error;
  }
};
