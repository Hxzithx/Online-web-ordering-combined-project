import React from 'react';
import { Link } from 'react-router-dom';

export const Footer: React.FC = () => {
  return (
    <footer id="footer" className="footer footer-1 text-center" style={{ backgroundColor: '#0e0d0b', borderTop: '1px solid rgba(197, 157, 95, 0.2)', padding: '60px 0 30px' }}>
      <div className="container">
        <div className="row">
          <div className="col-xs-12 col-sm-12 col-md-12">
            <div className="footer--logo mb-30">
              <img src="/assets/images/logo/logo-light.png" alt="logo" style={{ maxHeight: '60px' }} />
            </div>
            
            <div className="footer--social mb-30" style={{ display: 'flex', justifyContent: 'center', gap: '20px' }}>
              <a href="https://facebook.com" target="_blank" rel="noreferrer" style={{ width: '40px', height: '40px', borderRadius: '50%', border: '1px solid rgba(197, 157, 95, 0.3)', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#c59d5f', transition: 'all 0.3s' }} className="footer-social-link">
                <i className="fa fa-facebook"></i>
              </a>
              <a href="https://twitter.com" target="_blank" rel="noreferrer" style={{ width: '40px', height: '40px', borderRadius: '50%', border: '1px solid rgba(197, 157, 95, 0.3)', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#c59d5f', transition: 'all 0.3s' }} className="footer-social-link">
                <i className="fa fa-twitter"></i>
              </a>
              <a href="https://instagram.com" target="_blank" rel="noreferrer" style={{ width: '40px', height: '40px', borderRadius: '50%', border: '1px solid rgba(197, 157, 95, 0.3)', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#c59d5f', transition: 'all 0.3s' }} className="footer-social-link">
                <i className="fa fa-instagram"></i>
              </a>
              <a href="https://tripadvisor.com" target="_blank" rel="noreferrer" style={{ width: '40px', height: '40px', borderRadius: '50%', border: '1px solid rgba(197, 157, 95, 0.3)', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#c59d5f', transition: 'all 0.3s' }} className="footer-social-link">
                <i className="fa fa-tripadvisor"></i>
              </a>
            </div>
          </div>
        </div>

        <div className="row" style={{ marginTop: '30px' }}>
          <div className="col-xs-12 col-sm-4 col-md-4" style={{ marginBottom: '25px' }}>
            <div className="footer-widget" style={{ color: '#f0ebe0' }}>
              <h5 style={{ color: '#c59d5f', fontFamily: 'Raleway', fontWeight: 700, textTransform: 'uppercase', fontSize: '12px', letterSpacing: '2px', marginBottom: '15px' }}>Contact Us</h5>
              <p style={{ color: 'rgba(255, 255, 255, 0.5)', fontSize: '13px', lineHeight: '22px' }}>
                123 Restaurant Street, Cairo, Egypt<br />
                Phone: +20 123 456 789<br />
                Email: hello@grannyrestaurant.com
              </p>
            </div>
          </div>
          
          <div className="col-xs-12 col-sm-4 col-md-4" style={{ marginBottom: '25px' }}>
            <div className="footer-widget" style={{ color: '#f0ebe0' }}>
              <h5 style={{ color: '#c59d5f', fontFamily: 'Raleway', fontWeight: 700, textTransform: 'uppercase', fontSize: '12px', letterSpacing: '2px', marginBottom: '15px' }}>Opening Hours</h5>
              <p style={{ color: 'rgba(255, 255, 255, 0.5)', fontSize: '13px', lineHeight: '22px' }}>
                Monday - Friday: 10:00 AM - 11:00 PM<br />
                Saturday - Sunday: 09:00 AM - 12:00 AM<br />
                Happy Hours: Every Day 05:00 PM - 07:00 PM
              </p>
            </div>
          </div>
          
          <div className="col-xs-12 col-sm-4 col-md-4" style={{ marginBottom: '25px' }}>
            <div className="footer-widget" style={{ color: '#f0ebe0' }}>
              <h5 style={{ color: '#c59d5f', fontFamily: 'Raleway', fontWeight: 700, textTransform: 'uppercase', fontSize: '12px', letterSpacing: '2px', marginBottom: '15px' }}>Quick Links</h5>
              <ul className="list-unstyled" style={{ color: 'rgba(255, 255, 255, 0.5)', fontSize: '13px', lineHeight: '24px' }}>
                <li><Link to="/about" style={{ color: 'rgba(255, 255, 255, 0.5)' }}>Our Story</Link></li>
                <li><Link to="/menu" style={{ color: 'rgba(255, 255, 255, 0.5)' }}>Menu Board</Link></li>
                <li><Link to="/shop" style={{ color: 'rgba(255, 255, 255, 0.5)' }}>Shop Products</Link></li>
                <li><Link to="/reservation" style={{ color: 'rgba(255, 255, 255, 0.5)' }}>Online Reservation</Link></li>
              </ul>
            </div>
          </div>
        </div>

        <div className="row" style={{ marginTop: '40px', paddingTop: '20px', borderTop: '1px solid rgba(255, 255, 255, 0.05)' }}>
          <div className="col-xs-12 col-sm-12 col-md-12">
            <p className="copyright" style={{ color: 'rgba(255, 255, 255, 0.3)', fontSize: '11px', textTransform: 'uppercase', letterSpacing: '1px' }}>
              &copy; {new Date().getFullYear()} SOFTO Solutions. All Rights Reserved.
            </p>
          </div>
        </div>
      </div>
    </footer>
  );
};
export default Footer;
