import React from 'react';
import { Link } from 'react-router-dom';

export const Chefs: React.FC = () => {
  const chefs = [
    {
      name: 'Chef Anthony Bourdain',
      role: 'Head Culinary Officer',
      image: '/assets/images/sliders/slide-bg/1.jpg',
      bio: 'Over 20 years of experience in French cuisine and steak preparation techniques.'
    },
    {
      name: 'Chef Gordon Ramsay',
      role: 'Senior Sous Chef',
      image: '/assets/images/sliders/slide-bg/2.jpg',
      bio: 'Master of pastas and signature wood-fired recipes.'
    },
    {
      name: 'Chef Alice Waters',
      role: 'Executive Pastry Artist',
      image: '/assets/images/sliders/slide-bg/3.jpg',
      bio: 'Creates our molten lava melts and gourmet seasonal desserts.'
    }
  ];

  return (
    <div className="chefs-wrapper">
      {/* Page Title */}
      <section id="page-title" className="page-title bg-overlay bg-parallax bg-overlay-gradient">
        <div className="bg-section">
          <img src="/assets/images/sliders/slide-bg/2.jpg" alt="Background" />
        </div>
        <div className="container">
          <div className="row">
            <div className="col-md-12">
              <div className="title title-4 text-center">
                <div className="title--content">
                  <div className="title--subtitle">Experts</div>
                  <div className="title--heading">
                    <h1>Our Chefs</h1>
                  </div>
                </div>
                <ol className="breadcrumb">
                  <li><Link to="/home">Home</Link></li>
                  <li className="active">Chefs</li>
                </ol>
                <div className="divider--shape-1down divider--shape-gray"></div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Chefs Section */}
      <section className="chefs-list bg-white" style={{ padding: '80px 0' }}>
        <div className="container">
          <div className="row">
            {chefs.map((chef, index) => (
              <div key={index} className="col-xs-12 col-sm-4 mb-30">
                <div style={{ border: '1px solid #f0f0f0', borderRadius: '4px', overflow: 'hidden', textAlign: 'center', backgroundColor: '#f9f9f9', paddingBottom: '20px' }}>
                  <img src={chef.image} alt={chef.name} style={{ width: '100%', height: '260px', objectFit: 'cover' }} />
                  <h4 style={{ fontFamily: 'Kaushan Script', fontSize: '22px', color: '#222', marginTop: '20px', marginBottom: '5px' }}>{chef.name}</h4>
                  <span style={{ fontSize: '12px', textTransform: 'uppercase', color: '#c59d5f', fontWeight: 'bold', letterSpacing: '1px', display: 'block', marginBottom: '10px' }}>{chef.role}</span>
                  <p style={{ fontSize: '13px', color: '#a5a5a5', padding: '0 20px', lineHeight: '20px', margin: 0 }}>{chef.bio}</p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>
    </div>
  );
};
export default Chefs;
