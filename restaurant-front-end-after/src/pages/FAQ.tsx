import React, { useState } from 'react';
import { Link } from 'react-router-dom';

interface FAQItem {
  q: string;
  a: string;
}

export const FAQ: React.FC = () => {
  const [activeIndex, setActiveIndex] = useState<number | null>(0);

  const faqs: FAQItem[] = [
    {
      q: 'Do I need to make a reservation for Dine-in?',
      a: 'We highly recommend making an online table reservation via our Reservation page. This ensures you secure a seat in your preferred zone (Main Hall, VIP Lounge, Garden) during busy dining hours.'
    },
    {
      q: 'What are the restaurant opening hours?',
      a: 'Granny Restaurant is open daily from 11:00 AM to 11:00 PM. Last kitchen orders are accepted up to 10:30 PM.'
    },
    {
      q: 'Do you offer home delivery services?',
      a: 'Yes, we provide online delivery directly to your home address! Simply select the "Online Order" mode when checking out and enter your delivery address in your profile.'
    },
    {
      q: 'Are custom portions and add-ons supported?',
      a: 'Absolutely! When clicking "Configure & Add" on any menu item, you can select custom portion sizes (Double, Family, standard) and choose multiple add-ons (cheese, steak sauces, extra meat) to customize your recipe.'
    },
    {
      q: 'How can I pay for my online orders?',
      a: 'We support Cash on Counter/Delivery, Direct Bank Transfer, Mobile Payments, and Online Card payment simulation.'
    }
  ];

  const toggleAccordion = (index: number) => {
    setActiveIndex(activeIndex === index ? null : index);
  };

  return (
    <div className="faq-wrapper">
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
                  <div className="title--subtitle">Help Center</div>
                  <div className="title--heading">
                    <h1>FAQs</h1>
                  </div>
                </div>
                <ol className="breadcrumb">
                  <li><Link to="/home">Home</Link></li>
                  <li className="active">FAQs</li>
                </ol>
                <div className="divider--shape-1down divider--shape-gray"></div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Accordion List */}
      <section className="faq-list bg-white" style={{ padding: '80px 0' }}>
        <div className="container">
          <div className="row">
            <div className="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
              <div style={{ border: '1px solid #f0f0f0', borderRadius: '4px', overflow: 'hidden' }}>
                {faqs.map((faq, index) => {
                  const isOpen = activeIndex === index;
                  return (
                    <div key={index} style={{ borderBottom: index < faqs.length - 1 ? '1px solid #f0f0f0' : 'none' }}>
                      {/* Question Header */}
                      <div 
                        onClick={() => toggleAccordion(index)}
                        style={{
                          display: 'flex',
                          justifyContent: 'space-between',
                          alignItems: 'center',
                          padding: '20px 25px',
                          cursor: 'pointer',
                          backgroundColor: isOpen ? '#fafafa' : '#fff',
                          transition: 'background-color 0.2s',
                          userSelect: 'none'
                        }}
                      >
                        <h4 style={{ margin: 0, fontSize: '15px', fontWeight: 'bold', color: isOpen ? '#c59d5f' : '#222', fontFamily: 'Raleway' }}>
                          {faq.q}
                        </h4>
                        <span style={{ fontSize: '18px', color: '#c59d5f', fontWeight: 'bold' }}>
                          {isOpen ? '−' : '+'}
                        </span>
                      </div>
                      
                      {/* Answer Content */}
                      {isOpen && (
                        <div style={{ padding: '20px 25px', backgroundColor: '#fafafa', color: '#666', fontSize: '14px', lineHeight: '24px', borderTop: '1px solid #f0f0f0' }}>
                          {faq.a}
                        </div>
                      )}
                    </div>
                  );
                })}
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
};
export default FAQ;
