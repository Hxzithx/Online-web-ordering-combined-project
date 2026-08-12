import React, { createContext, useContext, useState, useEffect } from 'react';

interface User {
  id: number;
  first_name: string;
  last_name: string;
  email: string;
  role: string;
  avatar: string | null;
  phone?: string;
  address?: string;
}

interface AuthContextType {
  token: string | null;
  user: User | null;
  isAuthenticated: boolean;
  loading: boolean;
  login: (token: string, user: User) => void;
  logout: () => Promise<void>;
  updateUser: (user: Partial<User>) => void;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export const AuthProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [token, setToken] = useState<string | null>(null);
  const [user, setUser] = useState<User | null>(null);
  const [loading, setLoading] = useState<boolean>(true);

  useEffect(() => {
    // Load auth details from localStorage on mount
    const savedToken = localStorage.getItem('api_token');
    const savedUser = localStorage.getItem('user_details');

    if (savedToken && savedUser) {
      setToken(savedToken);
      try {
        setUser(JSON.parse(savedUser));
      } catch (e) {
        console.error('Error parsing saved user details:', e);
        localStorage.removeItem('api_token');
        localStorage.removeItem('user_details');
      }
    }
    setLoading(false);
  }, []);

  const login = (newToken: string, newUser: User) => {
    setToken(newToken);
    setUser(newUser);
    localStorage.setItem('api_token', newToken);
    localStorage.setItem('user_details', JSON.stringify(newUser));
  };

  const logout = async () => {
    const apiBaseUrl = import.meta.env.VITE_API_BASE_URL || 'http://127.0.0.1:8000/';
    try {
      if (token) {
        await fetch(`${apiBaseUrl}api/logout`, {
          method: 'POST',
          headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json',
          }
        });
      }
    } catch (e) {
      console.error('Logout API call failed:', e);
    } finally {
      setToken(null);
      setUser(null);
      localStorage.removeItem('api_token');
      localStorage.removeItem('user_details');
      localStorage.removeItem('orderType');
    }
  };

  const updateUser = (updatedFields: Partial<User>) => {
    if (user) {
      const updatedUser = { ...user, ...updatedFields };
      setUser(updatedUser);
      localStorage.setItem('user_details', JSON.stringify(updatedUser));
    }
  };

  const isAuthenticated = !!token;

  return (
    <AuthContext.Provider value={{ token, user, isAuthenticated, loading, login, logout, updateUser }}>
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (context === undefined) {
    throw new Error('useAuth must be used within an AuthProvider');
  }
  return context;
};
