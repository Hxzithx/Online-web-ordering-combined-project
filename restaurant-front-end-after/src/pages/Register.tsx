import React, { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import api from '../services/api';
import Swal from 'sweetalert2';

export const Register: React.FC = () => {
  const navigate = useNavigate();

  const [firstName, setFirstName] = useState('');
  const [lastName, setLastName] = useState('');
  const [phone, setPhone] = useState('');
  const [address, setAddress] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [passwordConfirmation, setPasswordConfirmation] = useState('');
  
  const [errors, setErrors] = useState<Record<string, string[]>>({});
  const [errorMsg, setErrorMsg] = useState<string | null>(null);
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setErrors({});
    setErrorMsg(null);

    if (password !== passwordConfirmation) {
      setErrorMsg('Passwords do not match.');
      return;
    }

    setLoading(true);

    try {
      await api.post('api/user/register', {
        first_name: firstName,
        last_name: lastName,
        phone,
        address,
        email,
        role: 'user',
        password,
        password_confirmation: passwordConfirmation,
      });

      Swal.fire({
        icon: 'success',
        title: 'Account Created!',
        text: 'Your registration was successful. Please sign in.',
        background: '#0e0d0b',
        color: '#f0ebe0',
        confirmButtonColor: '#c59d5f',
      });

      navigate('/login');
    } catch (err: any) {
      console.error(err);
      if (err.response?.data?.errors) {
        setErrors(err.response.data.errors);
      } else {
        setErrorMsg(err.response?.data?.message || 'Registration failed. Please check your inputs and try again.');
      }

      Swal.fire({
        icon: 'error',
        title: 'Registration Failed',
        text: err.response?.data?.message || 'Please check the validation errors.',
        background: '#0e0d0b',
        color: '#f0ebe0',
        confirmButtonColor: '#c59d5f',
      });
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="register-section">
      <div className="register-box">
        <div className="logo">
          <img src="/assets/images/logo/logo-light.png" alt="Granny Logo" />
        </div>

        <div className="register-subtitle">Join us today</div>
        <h2>Create Account</h2>
        <div className="register-divider"></div>

        {errorMsg && (
          <div className="alert-danger" style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
            <i className="fa fa-exclamation-circle"></i>
            <span>{errorMsg}</span>
          </div>
        )}

        <form onSubmit={handleSubmit}>
          <div className="form-row" style={{ display: 'flex', gap: '15px', marginBottom: '15px' }}>
            <div className="form-col" style={{ flex: 1, textAlign: 'left' }}>
              <div className="input-icon">
                <input
                  type="text"
                  className={`form-control ${errors.first_name ? 'input-error' : ''}`}
                  placeholder="First Name"
                  value={firstName}
                  onChange={(e) => setFirstName(e.target.value)}
                  required
                />
                <i className="fa fa-user"></i>
              </div>
              {errors.first_name && <span style={{ color: '#ff6b6b', fontSize: '11px' }}>{errors.first_name[0]}</span>}
            </div>

            <div className="form-col" style={{ flex: 1, textAlign: 'left' }}>
              <div className="input-icon">
                <input
                  type="text"
                  className={`form-control ${errors.last_name ? 'input-error' : ''}`}
                  placeholder="Last Name"
                  value={lastName}
                  onChange={(e) => setLastName(e.target.value)}
                  required
                />
                <i className="fa fa-user"></i>
              </div>
              {errors.last_name && <span style={{ color: '#ff6b6b', fontSize: '11px' }}>{errors.last_name[0]}</span>}
            </div>
          </div>

          <div className="form-row" style={{ display: 'flex', gap: '15px', marginBottom: '15px' }}>
            <div className="form-col" style={{ flex: 1, textAlign: 'left' }}>
              <div className="input-icon">
                <input
                  type="text"
                  className={`form-control ${errors.phone ? 'input-error' : ''}`}
                  placeholder="Phone Number"
                  value={phone}
                  onChange={(e) => setPhone(e.target.value)}
                  required
                />
                <i className="fa fa-phone"></i>
              </div>
              {errors.phone && <span style={{ color: '#ff6b6b', fontSize: '11px' }}>{errors.phone[0]}</span>}
            </div>

            <div className="form-col" style={{ flex: 1, textAlign: 'left' }}>
              <div className="input-icon">
                <input
                  type="email"
                  className={`form-control ${errors.email ? 'input-error' : ''}`}
                  placeholder="Email Address"
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  required
                />
                <i className="fa fa-envelope"></i>
              </div>
              {errors.email && <span style={{ color: '#ff6b6b', fontSize: '11px' }}>{errors.email[0]}</span>}
            </div>
          </div>

          <div className="form-group" style={{ textAlign: 'left', marginBottom: '15px' }}>
            <div className="input-icon">
              <textarea
                className={`form-control form-textarea ${errors.address ? 'input-error' : ''}`}
                placeholder="Delivery Address"
                value={address}
                onChange={(e) => setAddress(e.target.value)}
                required
                rows={2}
                style={{ height: '70px', paddingTop: '10px' }}
              />
              <i className="fa fa-map-marker" style={{ top: '20px' }}></i>
            </div>
            {errors.address && <span style={{ color: '#ff6b6b', fontSize: '11px' }}>{errors.address[0]}</span>}
          </div>

          <div className="form-row" style={{ display: 'flex', gap: '15px', marginBottom: '15px' }}>
            <div className="form-col" style={{ flex: 1, textAlign: 'left' }}>
              <div className="input-icon">
                <input
                  type="password"
                  className={`form-control ${errors.password ? 'input-error' : ''}`}
                  placeholder="Password"
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  required
                />
                <i className="fa fa-lock"></i>
              </div>
              {errors.password && <span style={{ color: '#ff6b6b', fontSize: '11px' }}>{errors.password[0]}</span>}
            </div>

            <div className="form-col" style={{ flex: 1, textAlign: 'left' }}>
              <div className="input-icon">
                <input
                  type="password"
                  className="form-control"
                  placeholder="Confirm Password"
                  value={passwordConfirmation}
                  onChange={(e) => setPasswordConfirmation(e.target.value)}
                  required
                />
                <i className="fa fa-lock"></i>
              </div>
            </div>
          </div>

          <button type="submit" className="btn-login" disabled={loading} style={{ marginTop: '10px' }}>
            {loading ? 'Creating Account...' : 'Register Account'}
          </button>
        </form>

        <div className="login-register-link" style={{ marginTop: '20px' }}>
          Already have an account? <Link to="/login">Sign In</Link>
        </div>

        <div className="login-footer" style={{ marginTop: '25px', paddingTop: '20px', borderTop: '1px solid rgba(197, 157, 95, 0.15)' }}>
          &copy; {new Date().getFullYear()} Granny Restaurant. All Rights Reserved.
        </div>
      </div>
    </div>
  );
};
export default Register;
