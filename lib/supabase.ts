
import 'react-native-url-polyfill/auto';
import { createClient } from '@supabase/supabase-js';
import AsyncStorage from '@react-native-async-storage/async-storage';

const supabaseUrl = 'https://nrjioqyxoijlpehpolfy.supabase.co';
const supabaseAnonKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Im5yamlvcXl4b2lqbHBlaHBvbGZ5Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NjExMzY4MDUsImV4cCI6MjA3NjcxMjgwNX0.eUBMemJbt-4ZqCow3l5Jw8zCRzpv-3iQ3WxGFMAIPk4';

export const supabase = createClient(supabaseUrl, supabaseAnonKey, {
  auth: {
    storage: AsyncStorage,
    autoRefreshToken: true,
    persistSession: true,
    detectSessionInUrl: false,
  },
});
