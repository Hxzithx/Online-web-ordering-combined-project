import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import api from '../services/api';
import Swal from 'sweetalert2';

export const getAvatarUrl = (avatarPath?: string | null): string | null => {
  if (!avatarPath) return null;
  if (
    avatarPath.startsWith('blob:') ||
    avatarPath.startsWith('data:') ||
    avatarPath.startsWith('http://') ||
    avatarPath.startsWith('https://')
  ) {
    return avatarPath;
  }
  const apiBaseUrl = import.meta.env.VITE_API_BASE_URL || 'http://127.0.0.1:8001/';
  const cleanBase = apiBaseUrl.endsWith('/') ? apiBaseUrl : `${apiBaseUrl}/`;
  const cleanPath = avatarPath.startsWith('/') ? avatarPath.substring(1) : avatarPath;

  if (cleanPath.startsWith('storage/')) {
    return `${cleanBase}${cleanPath}`;
  }
  return `${cleanBase}storage/${cleanPath}`;
};

export const Profile: React.FC = () => {
  const { user, updateUser } = useAuth();

  const [name, setName] = useState('');
  const [phone, setPhone] = useState('');
  const [address, setAddress] = useState('');
  const [avatarFile, setAvatarFile] = useState<File | null>(null);
  const [avatarPreview, setAvatarPreview] = useState<string | null>(null);
  const [imgError, setImgError] = useState(false);
  
  const [loading, setLoading] = useState(false);
  const [fetching, setFetching] = useState(true);

  useEffect(() => {
    const fetchProfile = async () => {
      try {
        setFetching(true);
        const response = await api.get('api/customer/profile');
        const customer = response.data.customer || response.data;
        
        const fullName = `${customer.first_name || ''} ${customer.last_name || ''}`.trim();
        setName(fullName || (user ? `${user.first_name} ${user.last_name}` : ''));
        setPhone(customer.phone || '');
        setAddress(customer.address || '');
        
        const avatar = customer.avatar || user?.avatar;
        if (avatar) {
          setAvatarPreview(getAvatarUrl(avatar));
          setImgError(false);
        }
      } catch (err) {
        console.error('Error fetching profile:', err);
      } finally {
        setFetching(false);
      }
    };

    fetchProfile();
  }, [user]);

  const handleAvatarChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (file) {
      setAvatarFile(file);
      setAvatarPreview(URL.createObjectURL(file));
      setImgError(false);
    }
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);

    const nameParts = name.trim().split(' ');
    const firstName = nameParts[0] || '';
    const lastName = nameParts.slice(1).join(' ') || '';

    const formData = new FormData();
    formData.append('first_name', firstName);
    formData.append('last_name', lastName);
    formData.append('phone', phone);
    formData.append('address', address);
    
    if (avatarFile) {
      formData.append('avatar', avatarFile);
    }

    try {
      const response = await api.post('api/customer/profile', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      });

      const updatedCustomer = response.data.customer;
      
      const newAvatarUrl = getAvatarUrl(updatedCustomer.avatar);
      if (newAvatarUrl) {
        setAvatarPreview(newAvatarUrl);
        setImgError(false);
      }

      // Update local auth context
      updateUser({
        first_name: updatedCustomer.first_name,
        last_name: updatedCustomer.last_name,
        phone: updatedCustomer.phone,
        address: updatedCustomer.address,
        avatar: updatedCustomer.avatar,
      });

      Swal.fire({
        icon: 'success',
        title: 'Profile Updated!',
        text: 'Your profile changes have been saved successfully.',
        timer: 2000,
        showConfirmButton: false,
        background: '#0e0d0b',
        color: '#f0ebe0',
        iconColor: '#c59d5f',
      });
    } catch (err: any) {
      console.error(err);
      Swal.fire({
        icon: 'error',
        title: 'Update Failed',
        text: err.response?.data?.message || 'An error occurred while updating your profile.',
        background: '#0e0d0b',
        color: '#f0ebe0',
        confirmButtonColor: '#c59d5f',
      });
    } finally {
      setLoading(false);
    }
  };

  const getInitials = () => {
    return name ? name.charAt(0).toUpperCase() : 'U';
  };

  return (
    <div className="profile-wrapper">
      <div className="orb orb-1"></div>
      <div className="orb orb-2"></div>
      <div className="orb orb-3"></div>

      <div className="profile-container">
        <div className="side-accent"></div>

        <div className="profile-header">
          <div className="header-label">ACCOUNT</div>
          <h2>My Profile</h2>
          <p className="header-sub">Manage your personal details & preferences</p>
        </div>

        {fetching ? (
          <div style={{ textAlign: 'center', padding: '40px 0', color: '#c59d5f' }}>
            <i className="fa fa-spinner fa-spin fa-2x"></i>
            <p style={{ marginTop: '10px', color: '#a5a5a5' }}>Loading account details...</p>
          </div>
        ) : (
          <form id="profile-form" onSubmit={handleSubmit}>
            {/* Avatar Section */}
            <div className="avatar-section">
              <div className="avatar-ring">
                <div className="profile-avatar-large">
                  {avatarPreview && !imgError ? (
                    <img
                      id="avatarPreview"
                      src={avatarPreview}
                      alt=""
                      onError={() => setImgError(true)}
                      style={{ width: '100%', height: '100%', borderRadius: '50%', objectFit: 'cover', display: 'block' }}
                    />
                  ) : (
                    <span id="avatarLetter">{getInitials()}</span>
                  )}
                </div>
                <label htmlFor="avatarInput" className="avatar-edit-btn" title="Change photo">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                  </svg>
                </label>
              </div>

              <input
                type="file"
                id="avatarInput"
                className="avatar-input-hidden"
                accept="image/*"
                onChange={handleAvatarChange}
                style={{ display: 'none' }}
              />

              <div className="avatar-info">
                <span className="avatar-name">{name || `${user?.first_name || ''} ${user?.last_name || ''}`}</span>
                <span className="avatar-role">{user?.role ? user.role.toUpperCase() : 'CUSTOMER'}</span>
              </div>
            </div>

            <div className="section-divider">
              <span>Personal Information</span>
            </div>

            {/* Fields Grid */}
            <div className="fields-grid">
              <div className="field-group full-width">
                <label htmlFor="name">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                  </svg>
                  Full Name
                </label>
                <input
                  type="text"
                  id="name"
                  value={name}
                  onChange={(e) => setName(e.target.value)}
                  placeholder="Your full name"
                  required
                />
              </div>

              <div className="field-group full-width">
                <label>
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                    <polyline points="22,6 12,13 2,6" />
                  </svg>
                  Email Address
                  <span className="field-badge">Verified</span>
                </label>
                <input type="email" value={user?.email || ''} disabled />
              </div>

              <div className="field-group">
                <label htmlFor="phone">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.58 3.42 2 2 0 0 1 3.55 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.77a16 16 0 0 0 6.29 6.29l1.57-1.57a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z" />
                  </svg>
                  Phone Number
                </label>
                <input
                  type="text"
                  id="phone"
                  value={phone}
                  onChange={(e) => setPhone(e.target.value)}
                  placeholder="+94 XX XXX XXXX"
                  required
                />
              </div>

              <div className="field-group">
                <label>
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2" />
                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
                  </svg>
                  Account Role
                </label>
                <input type="text" value={user?.role ? user.role.toUpperCase() : 'CUSTOMER'} disabled />
              </div>

              <div className="field-group full-width">
                <label htmlFor="address">
                  <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                    <circle cx="12" cy="10" r="3" />
                  </svg>
                  Delivery Address
                </label>
                <textarea
                  id="address"
                  value={address}
                  onChange={(e) => setAddress(e.target.value)}
                  placeholder="Your delivery address..."
                  required
                  style={{ height: '80px', padding: '10px' }}
                />
              </div>
            </div>

            {/* Actions */}
            <div className="form-actions" style={{ display: 'flex', gap: '15px', marginTop: '30px' }}>
              <button type="submit" className="updateProfile-btn" id="submit-btn" disabled={loading}>
                <span className="btn-text">{loading ? 'Saving...' : 'Save Changes'}</span>
                {loading && (
                  <span className="btn-loader" style={{ marginLeft: '10px' }}>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" className="spin">
                      <path d="M21 12a9 9 0 1 1-6.219-8.56" />
                    </svg>
                  </span>
                )}
                {!loading && (
                  <svg className="btn-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" style={{ marginLeft: '10px' }}>
                    <line x1="5" y1="12" x2="19" y2="12" />
                    <polyline points="12 5 19 12 12 19" />
                  </svg>
                )}
              </button>

              <Link to="/home" className="back-home-btn" style={{ textDecoration: 'none', display: 'flex', alignItems: 'center' }}>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" style={{ marginRight: '8px' }}>
                  <line x1="19" y1="12" x2="5" y2="12" />
                  <polyline points="12 19 5 12 12 5" />
                </svg>
                Back to Home
              </Link>
            </div>
          </form>
        )}
      </div>
    </div>
  );
};
export default Profile;
