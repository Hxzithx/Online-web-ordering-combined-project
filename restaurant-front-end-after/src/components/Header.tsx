import React, { useState, useEffect } from 'react';
import { Link, useNavigate, useLocation } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { getAvatarUrl } from '../pages/Profile';

export const Header: React.FC = () => {
  const { user, logout, isAuthenticated } = useAuth();
  const navigate = useNavigate();
  const location = useLocation();

  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  const [activeDropdown, setActiveDropdown] = useState<string | null>(null);
  const [profileDropdownOpen, setProfileDropdownOpen] = useState(false);
  const [cartCount, setCartCount] = useState(0);
  const [headerImgError, setHeaderImgError] = useState(false);

  // Function to load cart count
  const updateCartCount = () => {
    const cartKey = `cart_${user?.email || 'guest'}`;
    const cart = JSON.parse(localStorage.getItem(cartKey) || '[]');
    const count = cart.reduce((acc: number, item: any) => acc + (item.quantity || 1), 0);
    setCartCount(count);
  };

  useEffect(() => {
    updateCartCount();

    // Listen for custom cart-updated events
    window.addEventListener('cart-updated', updateCartCount);
    return () => {
      window.removeEventListener('cart-updated', updateCartCount);
    };
  }, [user]);

  // Close dropdowns on route change
  useEffect(() => {
    setMobileMenuOpen(false);
    setActiveDropdown(null);
    setProfileDropdownOpen(false);
  }, [location.pathname]);

  const handleLogout = async (e: React.FormEvent) => {
    e.preventDefault();
    await logout();
    navigate('/login');
  };

  const toggleDropdown = (name: string, e: React.MouseEvent) => {
    e.preventDefault();
    if (activeDropdown === name) {
      setActiveDropdown(null);
    } else {
      setActiveDropdown(name);
    }
  };

  const getInitials = (name: string) => {
    return name ? name.charAt(0).toUpperCase() : 'U';
  };

  return (
    <header id="navbar-spy" className="header header-2 header-bordered header-fixed header-dark">
      <nav id="primary-menu" className="navbar navbar-fixed-top sticky-navbar affix">
        <div className="container">
          <div className="navbar-header">
            <button
              type="button"
              className="navbar-toggle"
              onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
              aria-expanded={mobileMenuOpen}
            >
              <span className="sr-only">Toggle navigation</span>
              <span className="icon-bar"></span>
              <span className="icon-bar"></span>
              <span className="icon-bar"></span>
            </button>
            <Link className="logo" to="/home">
              <img className="logo-light" src="/assets/images/logo/logo-light.png" alt="logo" />
              <img className="logo-dark" src="/assets/images/logo/logo-dark.png" alt="logo" />
            </Link>
          </div>

          <div
            className={`collapse navbar-collapse pull-right ${mobileMenuOpen ? 'in' : ''}`}
            style={{ display: mobileMenuOpen ? 'block' : undefined }}
          >
            <ul className="nav navbar-nav" style={{ display: 'flex', alignItems: 'center', gap: '16px', margin: 0, padding: 0 }}>
              <li className={location.pathname === '/home' ? 'active' : ''} style={{ margin: 0, padding: 0 }}>
                <Link to="/home" className="menu-item" style={{ padding: '0 8px', margin: 0 }}>home</Link>
              </li>

              <li className={`has-dropdown ${activeDropdown === 'about' ? 'open' : ''}`} style={{ margin: 0, padding: 0 }}>
                <a href="#" onClick={(e) => toggleDropdown('about', e)} className="dropdown-toggle menu-item" style={{ padding: '0 8px', margin: 0 }}>
                  About
                </a>
                <ul className="dropdown-menu">
                  <li><Link to="/about">our story</Link></li>
                  <li><Link to="/chefs">our chefs</Link></li>
                  <li><Link to="/contact-us">contact us</Link></li>
                  <li><Link to="/faq">FAQs</Link></li>
                  <li><Link to="/guest-book">guestbook</Link></li>
                  <li><Link to="/reservation">online reservation</Link></li>
                </ul>
              </li>

              <li className={location.pathname === '/menu' ? 'active' : ''} style={{ margin: 0, padding: 0 }}>
                <Link to="/menu" className="menu-item" style={{ padding: '0 8px', margin: 0 }}>menu</Link>
              </li>

              <li className={location.pathname === '/blog' ? 'active' : ''} style={{ margin: 0, padding: 0 }}>
                <Link to="/blog" className="menu-item" style={{ padding: '0 8px', margin: 0 }}>blog</Link>
              </li>

              <li className={`has-dropdown ${activeDropdown === 'shop' ? 'open' : ''}`} style={{ margin: 0, padding: 0 }}>
                <a href="#" onClick={(e) => toggleDropdown('shop', e)} className="dropdown-toggle menu-item" style={{ padding: '0 8px', margin: 0 }}>
                  shop
                </a>
                <ul className="dropdown-menu">
                  <li><Link to="/shop">shop products</Link></li>
                  <li>
                    <Link to="/shopping-cart" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                      shop cart
                      {cartCount > 0 && <span className="label label-primary" style={{ backgroundColor: '#c59d5f' }}>{cartCount}</span>}
                    </Link>
                  </li>
                </ul>
              </li>
            </ul>

            <div className="module module-social pull-left">
              <a href="https://facebook.com" target="_blank" rel="noreferrer"><i className="fa fa-facebook"></i></a>
              <a href="https://twitter.com" target="_blank" rel="noreferrer"><i className="fa fa-twitter"></i></a>
              <a href="https://instagram.com" target="_blank" rel="noreferrer"><i className="fa fa-instagram"></i></a>
              <a href="https://tripadvisor.com" target="_blank" rel="noreferrer"><i className="fa fa-tripadvisor"></i></a>
            </div>

            <div className="module module-reservation" style={{ display: 'flex', alignItems: 'center', height: '90px', margin: 0, padding: 0 }}>
              <Link to="/reservation" className="btn btn-popup" style={{ display: 'inline-flex', alignItems: 'center', justifyContent: 'center', height: '40px', padding: '0 24px', lineHeight: '40px', borderRadius: '30px', margin: 0 }}>
                <i className="lnr lnr-users" style={{ marginRight: '8px', fontSize: '14px' }}></i>Reservation
              </Link>
            </div>

            {/* Profile Dropdown */}
            {isAuthenticated && user && (
              <div className="module module-profile" style={{ margin: 0, position: 'relative', display: 'flex', alignItems: 'center' }}>
                <div className="dropdown profile-dropdown">
                  <a
                    href="#"
                    className="dropdown-toggle profile-toggle"
                    onClick={(e) => {
                      e.preventDefault();
                      setProfileDropdownOpen(!profileDropdownOpen);
                    }}
                    style={{ display: 'flex', alignItems: 'center' }}
                  >
                    <div className="profile-avatar" style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', width: '42px', height: '42px', borderRadius: '50%', backgroundColor: '#c59d5f', color: '#111', fontWeight: 'bold', overflow: 'hidden', border: '2px solid #c59d5f', cursor: 'pointer' }}>
                      {user.avatar && !headerImgError ? (
                        <img
                          src={getAvatarUrl(user.avatar) || ''}
                          alt=""
                          onError={() => setHeaderImgError(true)}
                          style={{ width: '100%', height: '100%', borderRadius: '50%', objectFit: 'cover', display: 'block' }}
                        />
                      ) : (
                        getInitials(`${user.first_name} ${user.last_name}`)
                      )}
                    </div>
                  </a>

                  {profileDropdownOpen && (
                    <ul style={{
                      position: 'absolute', top: 'calc(100% + 12px)', right: 0, zIndex: 10000,
                      width: '260px', margin: 0,
                      background: '#141210',
                      border: '1px solid rgba(200,169,110,0.25)',
                      borderRadius: '16px',
                      overflow: 'hidden',
                      boxShadow: '0 20px 60px rgba(0,0,0,0.6)',
                      listStyle: 'none', padding: 0,
                    }}>

                      {/* Header */}
                      <li style={{ padding: '18px 18px 14px', borderBottom: '1px solid rgba(255,255,255,0.06)', display: 'flex', alignItems: 'center', gap: '12px' }}>
                        <div style={{ width: '44px', height: '44px', borderRadius: '50%', background: 'linear-gradient(135deg, #7a5a20, #c8a96e)', display: 'flex', alignItems: 'center', justifyContent: 'center', fontFamily: 'Georgia, serif', fontSize: '17px', fontWeight: 700, color: '#111', flexShrink: 0, border: '2px solid rgba(200,169,110,0.4)', overflow: 'hidden' }}>
                          {user.avatar && !headerImgError ? (
                            <img src={getAvatarUrl(user.avatar) || ''} alt="" onError={() => setHeaderImgError(true)} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                          ) : (
                            getInitials(`${user.first_name} ${user.last_name}`)
                          )}
                        </div>
                        <div style={{ overflow: 'hidden' }}>
                          <div style={{ fontSize: '14px', fontWeight: 600, color: '#f2ede3', whiteSpace: 'nowrap', overflow: 'hidden', textOverflow: 'ellipsis' }}>{user.first_name} {user.last_name}</div>
                          <div style={{ fontSize: '11px', color: 'rgba(242,237,227,0.42)', marginTop: '2px', whiteSpace: 'nowrap', overflow: 'hidden', textOverflow: 'ellipsis' }}>{user.email}</div>
                          <div style={{ display: 'inline-flex', alignItems: 'center', gap: '4px', marginTop: '5px', background: 'rgba(200,169,110,0.12)', border: '1px solid rgba(200,169,110,0.22)', padding: '1px 8px', borderRadius: '20px' }}>
                            <span style={{ width: '5px', height: '5px', borderRadius: '50%', background: '#c8a96e', display: 'inline-block' }}></span>
                            <span style={{ fontSize: '10px', fontWeight: 600, color: '#c8a96e', letterSpacing: '1px', textTransform: 'uppercase' }}>User</span>
                          </div>
                        </div>
                      </li>

                      {/* Menu Items */}
                      <li style={{ padding: '8px' }}>
                        {[
                          { to: '/profile', icon: 'fa-user', label: 'My Profile' },
                          { to: '/my-orders', icon: 'fa-list', label: 'My Orders' },
                        ].map(({ to, icon, label }) => (
                          <Link key={to} to={to} style={{ display: 'flex', alignItems: 'center', gap: '12px', padding: '10px 12px', borderRadius: '10px', textDecoration: 'none', color: 'rgba(242,237,227,0.80)', fontSize: '13px', fontWeight: 500 }}
                            onMouseOver={e => { e.currentTarget.style.background = 'rgba(200,169,110,0.08)'; e.currentTarget.style.color = '#f2ede3'; }}
                            onMouseOut={e => { e.currentTarget.style.background = 'transparent'; e.currentTarget.style.color = 'rgba(242,237,227,0.80)'; }}>
                            <span style={{ width: '32px', height: '32px', borderRadius: '8px', background: 'rgba(200,169,110,0.10)', border: '1px solid rgba(200,169,110,0.18)', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                              <i className={`fa ${icon}`} style={{ fontSize: '14px', color: '#c8a96e' }}></i>
                            </span>
                            {label}
                          </Link>
                        ))}
                      </li>

                      {/* Divider */}
                      <li style={{ height: '1px', background: 'rgba(255,255,255,0.06)', margin: '0 8px' }}></li>

                      {/* Logout */}
                      <li style={{ padding: '8px' }}>
                        <form onSubmit={handleLogout}>
                          <button type="submit" style={{ width: '100%', display: 'flex', alignItems: 'center', gap: '12px', padding: '10px 12px', borderRadius: '10px', background: 'transparent', border: 'none', color: 'rgba(255,100,100,0.75)', fontSize: '13px', fontWeight: 500, cursor: 'pointer', textAlign: 'left' }}
                            onMouseOver={e => { e.currentTarget.style.background = 'rgba(255,80,80,0.08)'; e.currentTarget.style.color = '#ff6b6b'; }}
                            onMouseOut={e => { e.currentTarget.style.background = 'transparent'; e.currentTarget.style.color = 'rgba(255,100,100,0.75)'; }}>
                            <span style={{ width: '32px', height: '32px', borderRadius: '8px', background: 'rgba(255,80,80,0.08)', border: '1px solid rgba(255,80,80,0.15)', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                              <i className="fa fa-sign-out" style={{ fontSize: '14px', color: '#ff6b6b' }}></i>
                            </span>
                            Logout
                          </button>
                        </form>
                      </li>

                    </ul>
                  )}
                </div>
              </div>
            )}

            {!isAuthenticated && (
              <div className="module" style={{ marginLeft: '15px', display: 'flex', alignItems: 'center' }}>
                <Link
                  to="/login"
                  className="btn btn--white btn--bordered btn--sm"
                  style={{
                    display: 'inline-flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    height: '40px',
                    lineHeight: '1',
                    padding: '0 20px',
                    width: 'auto',
                    border: '2px solid #c59d5f',
                    color: '#c59d5f',
                    textTransform: 'uppercase',
                    fontSize: '11px',
                    fontWeight: 'bold',
                    borderRadius: '4px',
                    boxSizing: 'border-box'
                  }}
                >
                  Sign In
                </Link>
              </div>
            )}
          </div>
        </div>
      </nav>
    </header>
  );
};
export default Header;
