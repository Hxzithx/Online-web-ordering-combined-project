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
            <ul className="nav navbar-nav nav-pos-right navbar-left">
              <li className={location.pathname === '/home' ? 'active' : ''}>
                <Link to="/home" className="menu-item">home</Link>
              </li>

              <li className={`has-dropdown ${activeDropdown === 'about' ? 'open' : ''}`}>
                <a href="#" onClick={(e) => toggleDropdown('about', e)} className="dropdown-toggle menu-item">
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

              <li className={`has-dropdown ${activeDropdown === 'menu' ? 'open' : ''}`}>
                <a href="#" onClick={(e) => toggleDropdown('menu', e)} className="dropdown-toggle menu-item">
                  Menu
                </a>
                <ul className="dropdown-menu">
                  <li><Link to="/menu">menu board</Link></li>
                </ul>
              </li>

              <li className={`has-dropdown ${activeDropdown === 'blog' ? 'open' : ''}`}>
                <a href="#" onClick={(e) => toggleDropdown('blog', e)} className="dropdown-toggle menu-item">
                  Blog
                </a>
                <ul className="dropdown-menu">
                  <li><Link to="/blog">blog carousel</Link></li>
                </ul>
              </li>

              <li className={`has-dropdown ${activeDropdown === 'shop' ? 'open' : ''}`}>
                <a href="#" onClick={(e) => toggleDropdown('shop', e)} className="dropdown-toggle menu-item">
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

            <div className="module module-reservation" style={{ marginLeft: '20px' }}>
              <Link to="/reservation" className="btn btn-popup" style={{ display: 'flex', alignItems: 'center', height: '35px', padding: '10px 35px', lineHeight: '20px', borderRadius: '15px' }}>
                <i className="lnr lnr-users" style={{ marginRight: '8px' }}></i>Reservation
              </Link>
            </div>

            {/* Profile Dropdown */}
            {isAuthenticated && user && (
              <div className={`module module-profile ${profileDropdownOpen ? 'open' : ''}`} style={{ marginLeft: '15px', position: 'relative' }}>
                <div className="dropdown profile-dropdown">
                  <a
                    href="#"
                    className="dropdown-toggle profile-toggle"
                    onClick={(e) => {
                      e.preventDefault();
                      setProfileDropdownOpen(!profileDropdownOpen);
                    }}
                  >
                    <div className="profile-avatar" style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', backgroundColor: '#c59d5f', color: '#fff', fontWeight: 'bold', overflow: 'hidden' }}>
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
                    <ul className="dropdown-menu dropdown-menu-right profile-menu" style={{ display: 'block', position: 'absolute', top: '100%', right: 0, zIndex: 1000, minWidth: '220px', backgroundColor: '#0e0d0b', border: '1px solid rgba(197, 157, 95, 0.3)' }}>
                      <li className="profile-info" style={{ padding: '15px', display: 'flex', alignItems: 'center', borderBottom: '1px solid rgba(197, 157, 95, 0.15)' }}>
                        <div className="profile-info-avatar" style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', width: '40px', height: '40px', borderRadius: '50%', backgroundColor: '#c59d5f', color: '#fff', fontWeight: 'bold', marginRight: '10px', overflow: 'hidden' }}>
                          {user.avatar && !headerImgError ? (
                            <img
                              src={getAvatarUrl(user.avatar) || ''}
                              alt=""
                              style={{ width: '100%', height: '100%', borderRadius: '50%', objectFit: 'cover' }}
                            />
                          ) : (
                            getInitials(`${user.first_name} ${user.last_name}`)
                          )}
                        </div>
                        <div className="profile-info-details" style={{ display: 'flex', flexDirection: 'column', color: '#f0ebe0' }}>
                          <strong style={{ fontSize: '14px' }}>{`${user.first_name} ${user.last_name}`}</strong>
                          <span style={{ fontSize: '11px', color: 'rgba(255, 255, 255, 0.5)' }}>{user.email}</span>
                        </div>
                      </li>

                      <li className="divider" style={{ height: '1px', margin: '5px 0', overflow: 'hidden', backgroundColor: 'rgba(197, 157, 95, 0.15)' }}></li>

                      <li>
                        <Link to="/profile" style={{ color: '#f0ebe0', padding: '8px 20px', display: 'block' }}>
                          <i className="fa fa-user" style={{ marginRight: '10px', color: '#c59d5f' }}></i> My Profile
                        </Link>
                      </li>

                      <li>
                        <Link to="/my-orders" style={{ color: '#f0ebe0', padding: '8px 20px', display: 'block' }}>
                          <i className="fa fa-list" style={{ marginRight: '10px', color: '#c59d5f' }}></i> My Orders
                        </Link>
                      </li>

                      <li className="divider" style={{ height: '1px', margin: '5px 0', overflow: 'hidden', backgroundColor: 'rgba(197, 157, 95, 0.15)' }}></li>

                      <li>
                        <form onSubmit={handleLogout}>
                          <button type="submit" className="profile-logout-btn" style={{ width: '100%', textAlign: 'left', background: 'none', border: 'none', color: '#ff6b6b', padding: '8px 20px', display: 'block', cursor: 'pointer' }}>
                            <i className="fa fa-sign-out" style={{ marginRight: '10px' }}></i> Logout
                          </button>
                        </form>
                      </li>
                    </ul>
                  )}
                </div>
              </div>
            )}

            {!isAuthenticated && (
              <div className="module" style={{ marginLeft: '15px' }}>
                <Link to="/login" className="btn btn--white btn--bordered btn--sm" style={{ height: '40px', padding: '10px 20px', border: '2px solid #c59d5f', color: '#c59d5f', textTransform: 'uppercase', fontSize: '11px', fontWeight: 'bold' }}>
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
