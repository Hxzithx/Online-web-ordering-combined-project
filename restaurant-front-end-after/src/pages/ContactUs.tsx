import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import Swal from 'sweetalert2';

export const ContactUs: React.FC = () => {
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [msg, setMsg] = useState('');

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    Swal.fire({
      icon: 'success',
      title: 'Message Sent!',
      text: 'Thank you for reaching out. We will get back to you shortly.',
      background: '#0e0d0b',
      color: '#f0ebe0',
      confirmButtonColor: '#c59d5f',
    });
    setName('');
    setEmail('');
    setMsg('');
  };

  return (
    <div className="contact-wrapper">
      {/* Page Title */}
      <section id="page-title" className="page-title bg-overlay bg-parallax bg-overlay-gradient">
        <div className="bg-section">
          <img src="/assets/images/page-title/6.jpg" alt="Background" />
        </div>
        <div className="container">
          <div className="row">
            <div className="col-md-12">
              <div className="title title-4 text-center">
                <div className="title--content">
                  <div className="title--subtitle">Get In Touch</div>
                  <div className="title--heading">
                    <h1>Contact Us</h1>
                  </div>
                </div>
                <ol className="breadcrumb">
                  <li><Link to="/home">Home</Link></li>
                  <li className="active">Contact</li>
                </ol>
                <div className="divider--shape-1down divider--shape-gray"></div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Contact Details and Form */}
      <section className="contact-section bg-white" style={{ padding: '80px 0' }}>
        <div className="container">
          <div className="row">
            {/* Info Cards */}
            <div className="col-xs-12 col-md-5 mb-40">
              <div style={{ paddingRight: '20px' }}>
                <h3 style={{ fontFamily: 'Kaushan Script', color: '#222', fontSize: '32px', marginBottom: '20px' }}>Visit Our Location</h3>
                <p style={{ color: '#666', lineHeight: '24px', marginBottom: '30px' }}>
                  Have questions, custom catering requests, or simply want to share your dining experience? We are here to listen! Feel free to visit us or contact us using details below.
                </p>

                <div style={{ display: 'flex', gap: '15px', marginBottom: '20px' }}>
                  <div style={{ width: '40px', height: '40px', borderRadius: '50%', border: '1px solid #c59d5f', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#c59d5f', flexShrink: 0 }}>
                    <i className="fa fa-map-marker"></i>
                  </div>
                  <div>
                    <strong>Location Address:</strong>
                    <p style={{ color: '#777', margin: 0 }}>123 Gastronomy Boulevard, Suite A, Food Town</p>
                  </div>
                </div>

                <div style={{ display: 'flex', gap: '15px', marginBottom: '20px' }}>
                  <div style={{ width: '40px', height: '40px', borderRadius: '50%', border: '1px solid #c59d5f', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#c59d5f', flexShrink: 0 }}>
                    <i className="fa fa-phone"></i>
                  </div>
                  <div>
                    <strong>Phone Numbers:</strong>
                    <p style={{ color: '#777', margin: 0 }}>+94 11 234 5678 | +94 77 123 4567</p>
                  </div>
                </div>

                <div style={{ display: 'flex', gap: '15px' }}>
                  <div style={{ width: '40px', height: '40px', borderRadius: '50%', border: '1px solid #c59d5f', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#c59d5f', flexShrink: 0 }}>
                    <i className="fa fa-envelope-o"></i>
                  </div>
                  <div>
                    <strong>Email Addresses:</strong>
                    <p style={{ color: '#777', margin: 0 }}>info@grannyrestaurant.com | support@granny.com</p>
                  </div>
                </div>
              </div>
            </div>

            {/* Form */}
            <div className="col-xs-12 col-md-7">
              <div style={{ backgroundColor: '#f9f9f9', border: '1px solid #f0f0f0', padding: '40px', borderRadius: '4px' }}>
                <h4 style={{ fontFamily: 'Raleway', fontWeight: 'bold', textTransform: 'uppercase', color: '#222', fontSize: '16px', borderBottom: '2px solid #c59d5f', paddingBottom: '10px', marginBottom: '25px', letterSpacing: '2px' }}>
                  Send A Message
                </h4>
                <form onSubmit={handleSubmit}>
                  <div className="form-group mb-20">
                    <input 
                      type="text" 
                      className="form-control" 
                      placeholder="Your Full Name"
                      value={name}
                      onChange={(e) => setName(e.target.value)}
                      required
                      style={{ border: '1px solid #e0e0e0', padding: '12px' }}
                    />
                  </div>
                  <div className="form-group mb-20">
                    <input 
                      type="email" 
                      className="form-control" 
                      placeholder="Email Address"
                      value={email}
                      onChange={(e) => setEmail(e.target.value)}
                      required
                      style={{ border: '1px solid #e0e0e0', padding: '12px' }}
                    />
                  </div>
                  <div className="form-group mb-25">
                    <textarea 
                      className="form-control" 
                      placeholder="Your Message..." 
                      rows={5}
                      value={msg}
                      onChange={(e) => setMsg(e.target.value)}
                      required
                      style={{ border: '1px solid #e0e0e0', padding: '12px', resize: 'none' }}
                    ></textarea>
                  </div>
                  <button 
                    type="submit" 
                    className="btn btn--secondary"
                    style={{ backgroundColor: '#c59d5f', border: 'none', color: '#fff', padding: '4.5px 30px', textTransform: 'uppercase', letterSpacing: '2px', fontWeight: 'bold', fontSize: '11px', borderRadius: '25px', cursor: 'pointer' }}
                  >
                    Send Message
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
};
export default ContactUs;
