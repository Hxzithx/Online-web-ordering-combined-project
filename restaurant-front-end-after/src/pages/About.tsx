import React from 'react';
import { Link } from 'react-router-dom';

export const About: React.FC = () => {
  return (
    <div className="about-wrapper">
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
                  <div className="title--subtitle">Our Story</div>
                  <div className="title--heading">
                    <h1>About Us</h1>
                  </div>
                </div>
                <ol className="breadcrumb">
                  <li><Link to="/home">Home</Link></li>
                  <li className="active">About</li>
                </ol>
                <div className="divider--shape-1down divider--shape-gray"></div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* About Main Section */}
      <section className="about-details bg-white" style={{ padding: '80px 0' }}>
        <div className="container">
          <div className="row">
            <div className="col-xs-12 col-md-6 mb-30">
              <img src="/assets/images/sliders/slide-bg/3.jpg" alt="Granny Kitchen" style={{ width: '100%', borderRadius: '4px', boxShadow: '0 4px 15px rgba(0,0,0,0.1)' }} />
            </div>
            <div className="col-xs-12 col-md-6" style={{ paddingLeft: '30px' }}>
              <span style={{ fontFamily: 'Great Vibes', color: '#c59d5f', fontSize: '24px' }}>Welcome to Granny</span>
              <h2 style={{ fontFamily: 'Kaushan Script', fontSize: '36px', color: '#222', marginTop: '10px', marginBottom: '20px' }}>Where Taste Meets Tradition</h2>
              <div style={{ width: '60px', height: '2px', backgroundColor: '#c59d5f', marginBottom: '20px' }}></div>
              <p style={{ color: '#666', fontSize: '15px', lineHeight: '26px', marginBottom: '20px' }}>
                Established in 1995, Granny Restaurant began as a small family-owned kitchen with a single purpose: to cook and serve recipes handed down through generations. Today, we stand as one of the town's premium culinary landmarks, combining traditional cooking arts with a high-end atmospheric layout.
              </p>
              <p style={{ color: '#666', fontSize: '15px', lineHeight: '26px' }}>
                Every steaks cut, fresh pasta noodles, and dessert sauce is prepared in-house by our master chefs using organic, local ingredients. We welcome you to experience our hospitality and dine in comfort.
              </p>
              <div style={{ marginTop: '30px' }}>
                <Link to="/menu" className="btn btn--secondary" style={{ backgroundColor: '#c59d5f', color: '#fff', borderRadius: '25px', padding: '12px 30px', textTransform: 'uppercase', fontSize: '11px', letterSpacing: '1px', fontWeight: 'bold', textDecoration: 'none' }}>
                  Explore Menu
                </Link>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
};
export default About;
