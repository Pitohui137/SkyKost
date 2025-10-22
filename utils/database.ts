
import { supabase } from '@/lib/supabase';

// Types
export interface Payment {
  id: string;
  date: string;
  amount: number;
  status: 'lunas' | 'pending' | 'gagal';
  method: string;
  confirmation_number: string;
  month: string;
  year: string;
  user_id?: string;
  created_at?: string;
  updated_at?: string;
}

export interface PaymentMethod {
  id: string;
  type: 'card' | 'bank' | 'ewallet';
  name: string;
  last4: string;
  is_default: boolean;
  user_id?: string;
  created_at?: string;
  updated_at?: string;
}

export interface KostInfo {
  id?: string;
  name: string;
  address: string;
  room_number: string;
  monthly_rent: number;
  owner_name: string;
  owner_phone: string;
  user_id?: string;
  created_at?: string;
  updated_at?: string;
}

// Helper function to get current user
const getCurrentUser = async () => {
  const { data: { user }, error } = await supabase.auth.getUser();
  if (error) {
    console.error('Error getting current user:', error);
    return null;
  }
  return user;
};

// Payment operations
export const getPayments = async (): Promise<Payment[]> => {
  try {
    const user = await getCurrentUser();
    if (!user) {
      console.log('No user logged in');
      return [];
    }

    const { data, error } = await supabase
      .from('payments')
      .select('*')
      .eq('user_id', user.id)
      .order('date', { ascending: false });

    if (error) {
      console.error('Error getting payments:', error);
      return [];
    }

    // Transform snake_case to camelCase for frontend
    return (data || []).map(payment => ({
      id: payment.id,
      date: payment.date,
      amount: payment.amount,
      status: payment.status,
      method: payment.method,
      confirmation_number: payment.confirmation_number,
      month: payment.month,
      year: payment.year,
      user_id: payment.user_id,
      created_at: payment.created_at,
      updated_at: payment.updated_at,
    }));
  } catch (error) {
    console.error('Error getting payments:', error);
    return [];
  }
};

export const savePayment = async (payment: Omit<Payment, 'id'>): Promise<void> => {
  try {
    const user = await getCurrentUser();
    if (!user) {
      throw new Error('No user logged in');
    }

    const { error } = await supabase
      .from('payments')
      .insert([{
        user_id: user.id,
        date: payment.date,
        amount: payment.amount,
        status: payment.status,
        method: payment.method,
        confirmation_number: payment.confirmation_number,
        month: payment.month,
        year: payment.year,
      }]);

    if (error) {
      console.error('Error saving payment:', error);
      throw error;
    }

    console.log('Payment saved successfully');
  } catch (error) {
    console.error('Error saving payment:', error);
    throw error;
  }
};

export const updatePayment = async (id: string, updates: Partial<Payment>): Promise<void> => {
  try {
    const user = await getCurrentUser();
    if (!user) {
      throw new Error('No user logged in');
    }

    const { error } = await supabase
      .from('payments')
      .update({
        ...updates,
        updated_at: new Date().toISOString(),
      })
      .eq('id', id)
      .eq('user_id', user.id);

    if (error) {
      console.error('Error updating payment:', error);
      throw error;
    }

    console.log('Payment updated successfully');
  } catch (error) {
    console.error('Error updating payment:', error);
    throw error;
  }
};

export const deletePayment = async (id: string): Promise<void> => {
  try {
    const user = await getCurrentUser();
    if (!user) {
      throw new Error('No user logged in');
    }

    const { error } = await supabase
      .from('payments')
      .delete()
      .eq('id', id)
      .eq('user_id', user.id);

    if (error) {
      console.error('Error deleting payment:', error);
      throw error;
    }

    console.log('Payment deleted successfully');
  } catch (error) {
    console.error('Error deleting payment:', error);
    throw error;
  }
};

// Payment method operations
export const getPaymentMethods = async (): Promise<PaymentMethod[]> => {
  try {
    const user = await getCurrentUser();
    if (!user) {
      console.log('No user logged in');
      return [];
    }

    const { data, error } = await supabase
      .from('payment_methods')
      .select('*')
      .eq('user_id', user.id)
      .order('is_default', { ascending: false });

    if (error) {
      console.error('Error getting payment methods:', error);
      return [];
    }

    return (data || []).map(method => ({
      id: method.id,
      type: method.type,
      name: method.name,
      last4: method.last4,
      is_default: method.is_default,
      user_id: method.user_id,
      created_at: method.created_at,
      updated_at: method.updated_at,
    }));
  } catch (error) {
    console.error('Error getting payment methods:', error);
    return [];
  }
};

export const savePaymentMethod = async (method: Omit<PaymentMethod, 'id'>): Promise<void> => {
  try {
    const user = await getCurrentUser();
    if (!user) {
      throw new Error('No user logged in');
    }

    // If this is set as default, unset all other defaults first
    if (method.is_default) {
      await supabase
        .from('payment_methods')
        .update({ is_default: false })
        .eq('user_id', user.id);
    }

    const { error } = await supabase
      .from('payment_methods')
      .insert([{
        user_id: user.id,
        type: method.type,
        name: method.name,
        last4: method.last4,
        is_default: method.is_default,
      }]);

    if (error) {
      console.error('Error saving payment method:', error);
      throw error;
    }

    console.log('Payment method saved successfully');
  } catch (error) {
    console.error('Error saving payment method:', error);
    throw error;
  }
};

export const updatePaymentMethod = async (id: string, updates: Partial<PaymentMethod>): Promise<void> => {
  try {
    const user = await getCurrentUser();
    if (!user) {
      throw new Error('No user logged in');
    }

    const { error } = await supabase
      .from('payment_methods')
      .update({
        ...updates,
        updated_at: new Date().toISOString(),
      })
      .eq('id', id)
      .eq('user_id', user.id);

    if (error) {
      console.error('Error updating payment method:', error);
      throw error;
    }

    console.log('Payment method updated successfully');
  } catch (error) {
    console.error('Error updating payment method:', error);
    throw error;
  }
};

export const deletePaymentMethod = async (id: string): Promise<void> => {
  try {
    const user = await getCurrentUser();
    if (!user) {
      throw new Error('No user logged in');
    }

    const { error } = await supabase
      .from('payment_methods')
      .delete()
      .eq('id', id)
      .eq('user_id', user.id);

    if (error) {
      console.error('Error deleting payment method:', error);
      throw error;
    }

    console.log('Payment method deleted successfully');
  } catch (error) {
    console.error('Error deleting payment method:', error);
    throw error;
  }
};

export const setDefaultPaymentMethod = async (id: string): Promise<void> => {
  try {
    const user = await getCurrentUser();
    if (!user) {
      throw new Error('No user logged in');
    }

    // First, unset all defaults
    await supabase
      .from('payment_methods')
      .update({ is_default: false })
      .eq('user_id', user.id);

    // Then set the new default
    const { error } = await supabase
      .from('payment_methods')
      .update({ is_default: true })
      .eq('id', id)
      .eq('user_id', user.id);

    if (error) {
      console.error('Error setting default payment method:', error);
      throw error;
    }

    console.log('Default payment method set successfully');
  } catch (error) {
    console.error('Error setting default payment method:', error);
    throw error;
  }
};

// Kost info operations
export const getKostInfo = async (): Promise<KostInfo | null> => {
  try {
    const user = await getCurrentUser();
    if (!user) {
      console.log('No user logged in');
      return null;
    }

    const { data, error } = await supabase
      .from('kost_info')
      .select('*')
      .eq('user_id', user.id)
      .single();

    if (error) {
      if (error.code === 'PGRST116') {
        // No rows returned
        console.log('No kost info found for user');
        return null;
      }
      console.error('Error getting kost info:', error);
      return null;
    }

    if (!data) return null;

    return {
      id: data.id,
      name: data.name,
      address: data.address,
      room_number: data.room_number,
      monthly_rent: data.monthly_rent,
      owner_name: data.owner_name,
      owner_phone: data.owner_phone,
      user_id: data.user_id,
      created_at: data.created_at,
      updated_at: data.updated_at,
    };
  } catch (error) {
    console.error('Error getting kost info:', error);
    return null;
  }
};

export const saveKostInfo = async (info: Omit<KostInfo, 'id'>): Promise<void> => {
  try {
    const user = await getCurrentUser();
    if (!user) {
      throw new Error('No user logged in');
    }

    // Check if kost info already exists
    const existing = await getKostInfo();

    if (existing) {
      // Update existing
      const { error } = await supabase
        .from('kost_info')
        .update({
          name: info.name,
          address: info.address,
          room_number: info.room_number,
          monthly_rent: info.monthly_rent,
          owner_name: info.owner_name,
          owner_phone: info.owner_phone,
          updated_at: new Date().toISOString(),
        })
        .eq('user_id', user.id);

      if (error) {
        console.error('Error updating kost info:', error);
        throw error;
      }
    } else {
      // Insert new
      const { error } = await supabase
        .from('kost_info')
        .insert([{
          user_id: user.id,
          name: info.name,
          address: info.address,
          room_number: info.room_number,
          monthly_rent: info.monthly_rent,
          owner_name: info.owner_name,
          owner_phone: info.owner_phone,
        }]);

      if (error) {
        console.error('Error saving kost info:', error);
        throw error;
      }
    }

    console.log('Kost info saved successfully');
  } catch (error) {
    console.error('Error saving kost info:', error);
    throw error;
  }
};

// Initialize with sample data
export const initializeSampleData = async (): Promise<void> => {
  try {
    const user = await getCurrentUser();
    if (!user) {
      console.log('No user logged in, skipping sample data initialization');
      return;
    }

    const existingPayments = await getPayments();
    const existingMethods = await getPaymentMethods();
    const existingKostInfo = await getKostInfo();

    // Initialize sample payments if none exist
    if (existingPayments.length === 0) {
      const samplePayments = [
        {
          date: '2024-02-01',
          amount: 1500000,
          status: 'lunas' as const,
          method: 'BCA •••• 4242',
          confirmation_number: 'PAY-2024-02-001',
          month: 'Februari',
          year: '2024',
        },
        {
          date: '2024-01-01',
          amount: 1500000,
          status: 'lunas' as const,
          method: 'BCA •••• 4242',
          confirmation_number: 'PAY-2024-01-001',
          month: 'Januari',
          year: '2024',
        },
        {
          date: '2023-12-01',
          amount: 1500000,
          status: 'lunas' as const,
          method: 'Mandiri •••• 1234',
          confirmation_number: 'PAY-2023-12-001',
          month: 'Desember',
          year: '2023',
        },
      ];

      for (const payment of samplePayments) {
        await savePayment(payment);
      }
      console.log('Sample payments initialized');
    }

    // Initialize sample payment methods if none exist
    if (existingMethods.length === 0) {
      const sampleMethods = [
        { type: 'bank' as const, name: 'BCA', last4: '4242', is_default: true },
        { type: 'bank' as const, name: 'Mandiri', last4: '1234', is_default: false },
        { type: 'ewallet' as const, name: 'GoPay', last4: '0856', is_default: false },
      ];

      for (const method of sampleMethods) {
        await savePaymentMethod(method);
      }
      console.log('Sample payment methods initialized');
    }

    // Initialize sample kost info if none exists
    if (!existingKostInfo) {
      const sampleKostInfo = {
        name: 'Kost Melati',
        address: 'Jl. Sudirman No. 123, Jakarta Selatan',
        room_number: 'A-205',
        monthly_rent: 1500000,
        owner_name: 'Ibu Siti',
        owner_phone: '+62 812-3456-7890',
      };
      await saveKostInfo(sampleKostInfo);
      console.log('Sample kost info initialized');
    }
  } catch (error) {
    console.error('Error initializing sample data:', error);
  }
};

// Clear all data (for testing)
export const clearAllData = async (): Promise<void> => {
  try {
    const user = await getCurrentUser();
    if (!user) {
      throw new Error('No user logged in');
    }

    // Delete all user data
    await supabase.from('payments').delete().eq('user_id', user.id);
    await supabase.from('payment_methods').delete().eq('user_id', user.id);
    await supabase.from('kost_info').delete().eq('user_id', user.id);

    console.log('All data cleared');
  } catch (error) {
    console.error('Error clearing data:', error);
    throw error;
  }
};
