import React, { useState, useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import api from '../services/api';
import Swal from 'sweetalert2';

export const Login: React.FC = () => {
  const { login } = useAuth();
  const navigate = useNavigate();

  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState<string | null>(null);
  const [loading, setLoading] = useState(false);

  // Handle Google Socialite OAuth callback return query parameters
  useEffect(() => {
    const params = new URLSearchParams(window.location.search);
    const token = params.get('token');
    const userStr = params.get('user');
    const errStr = params.get('error');

    if (token && userStr) {
      try {
        const userObj = JSON.parse(decodeURIComponent(userStr));
        login(token, userObj);

        Swal.fire({
          icon: 'success',
          title: `Welcome back, ${userObj.first_name || 'Google User'}!`,
          text: 'Successfully logged in with Google.',
          timer: 1500,
          showConfirmButton: false,
          background: '#0e0d0b',
          color: '#f0ebe0',
          iconColor: '#c59d5f',
        });

        window.history.replaceState({}, document.title, window.location.pathname);
        navigate('/home');
      } catch (e) {
        console.error('Error parsing Google user data:', e);
      }
    } else if (errStr) {
      const decodedErr = decodeURIComponent(errStr);
      setError(decodedErr);
      Swal.fire({
        icon: 'error',
        title: 'Google Login Failed',
        text: decodedErr,
        background: '#0e0d0b',
        color: '#f0ebe0',
        confirmButtonColor: '#c59d5f',
      });
      window.history.replaceState({}, document.title, window.location.pathname);
    }
  }, []);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError(null);
    setLoading(true);

    try {
      const response = await api.post('api/user/login', {
        email,
        password,
      });

      const { token, user } = response.data;

      login(token, user);

      Swal.fire({
        icon: 'success',
        title: `Welcome back, ${user.first_name}!`,
        text: 'Successfully logged in.',
        timer: 1500,
        showConfirmButton: false,
        background: '#0e0d0b',
        color: '#f0ebe0',
        iconColor: '#c59d5f',
      });

      navigate('/home');
    } catch (err: any) {
      console.error(err);
      const msg = err.response?.data?.message || 'Invalid email or password. Please try again.';
      setError(msg);

      Swal.fire({
        icon: 'error',
        title: 'Login Failed',
        text: msg,
        background: '#0e0d0b',
        color: '#f0ebe0',
        confirmButtonColor: '#c59d5f',
      });
    } finally {
      setLoading(false);
    }
  };

  const handleGoogleLogin = () => {
    // Redirects to backend Socialite route which opens official Google account chooser window
    const apiBaseUrl = import.meta.env.VITE_API_BASE_URL || 'http://127.0.0.1:8000/';
    const cleanBaseUrl = apiBaseUrl.endsWith('/') ? apiBaseUrl : `${apiBaseUrl}/`;
    window.location.href = `${cleanBaseUrl}api/auth/google`;
  };

  return (
    <div className="login-section">
      <div className="login-box">
        <div className="logo">
          <img src="/assets/images/logo/logo-light.png" alt="Granny Logo" />
        </div>

        <div className="login-subtitle">Welcome back</div>
        <h2>Sign In</h2>
        <div className="login-divider"></div>

        {error && (
          <div className="alert-danger" style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
            <i className="fa fa-exclamation-circle"></i>
            <span>{error}</span>
          </div>
        )}

        <form onSubmit={handleSubmit}>
          <div className="form-group">
            <div className="input-icon">
              <input
                type="email"
                className="form-control"
                placeholder="Email Address"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                required
                autoComplete="email"
              />
              <i className="fa fa-envelope"></i>
            </div>
          </div>

          <div className="form-group">
            <div className="input-icon">
              <input
                type="password"
                className="form-control"
                placeholder="Password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                required
                autoComplete="current-password"
              />
              <i className="fa fa-lock"></i>
            </div>
          </div>

          <button type="submit" className="btn-login" disabled={loading}>
            {loading ? 'Entering...' : 'Enter Restaurant'}
          </button>
        </form>

        <div className="btn-google-divider">
          <span>or</span>
        </div>

        <button onClick={handleGoogleLogin} className="btn-google">
          <svg className="btn-google-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style={{ width: '18px', height: '18px' }}>
            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4" />
            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853" />
            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05" />
            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335" />
          </svg>
          Continue with Google
        </button>

        <div className="login-register-link" style={{ marginTop: '20px' }}>
          Don't have an account? <Link to="/register">Create one</Link>
        </div>

        <div className="login-footer">
          &copy; {new Date().getFullYear()} Granny Restaurant. All Rights Reserved.
        </div>
      </div>
    </div>
  );
};

export default Login;
