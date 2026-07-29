import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import Swal from 'sweetalert2';

interface Review {
  name: string;
  rating: number;
  comment: string;
  date: string;
}

export const GuestBook: React.FC = () => {
  const [reviews, setReviews] = useState<Review[]>([
    {
      name: 'Sarah Connor',
      rating: 5,
      comment: 'Absolutely amazing taste! The Grilled Ribeye Steak was juicy and seasoned to perfection. Wonderful garden atmosphere too.',
      date: '2026-07-28'
    },
    {
      name: 'Michael Scott',
      rating: 5,
      comment: 'Excellent table service. The staff treats you like royalty, and the chocolate lava melt literally melted my heart.',
      date: '2026-07-25'
    },
    {
      name: 'John Doe',
      rating: 4,
      comment: 'Delicious wood-fired recipes. Booking a VIP Lounge table was quick and seamless.',
      date: '2026-07-20'
    }
  ]);

  const [name, setName] = useState('');
  const [rating, setRating] = useState(5);
  const [comment, setComment] = useState('');

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    const newReview: Review = {
      name,
      rating,
      comment,
      date: new Date().toISOString().split('T')[0]
    };
    setReviews([newReview, ...reviews]);
    Swal.fire({
      icon: 'success',
      title: 'Review Added!',
      text: 'Thank you for signing our guestbook.',
      background: '#0e0d0b',
      color: '#f0ebe0',
      confirmButtonColor: '#c59d5f',
    });
    setName('');
    setRating(5);
    setComment('');
  };

  return (
    <div className="guestbook-wrapper">
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
                  <div className="title--subtitle">Feedback</div>
                  <div className="title--heading">
                    <h1>Guestbook</h1>
                  </div>
                </div>
                <ol className="breadcrumb">
                  <li><Link to="/home">Home</Link></li>
                  <li className="active">Guestbook</li>
                </ol>
                <div className="divider--shape-1down divider--shape-gray"></div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Guestbook Section */}
      <section className="guestbook bg-white" style={{ padding: '80px 0' }}>
        <div className="container">
          <div className="row">
            {/* Reviews List */}
            <div className="col-xs-12 col-md-7 mb-40">
              <h3 style={{ fontFamily: 'Kaushan Script', color: '#222', fontSize: '32px', marginBottom: '30px' }}>What Guests Say</h3>
              
              <div style={{ display: 'flex', flexDirection: 'column', gap: '20px' }}>
                {reviews.map((rev, index) => (
                  <div key={index} style={{ border: '1px solid #f0f0f0', padding: '25px', borderRadius: '4px', backgroundColor: '#f9f9f9' }}>
                    <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '10px' }}>
                      <strong style={{ fontSize: '15px', color: '#222', fontFamily: 'Raleway' }}>{rev.name}</strong>
                      <span style={{ fontSize: '11px', color: '#a5a5a5' }}>{rev.date}</span>
                    </div>

                    <div style={{ color: '#c59d5f', marginBottom: '12px' }}>
                      {Array.from({ length: rev.rating }).map((_, i) => (
                        <i key={i} className="fa fa-star" style={{ marginRight: '3px' }}></i>
                      ))}
                    </div>

                    <p style={{ color: '#666', fontSize: '13px', lineHeight: '22px', margin: 0, fontStyle: 'italic' }}>
                      "{rev.comment}"
                    </p>
                  </div>
                ))}
              </div>
            </div>

            {/* Form */}
            <div className="col-xs-12 col-md-5">
              <div style={{ backgroundColor: '#0e0d0b', border: '1px solid rgba(197, 157, 95, 0.3)', padding: '30px', borderRadius: '4px', color: '#fff' }}>
                <h4 style={{ fontFamily: 'Kaushan Script', color: '#c59d5f', fontSize: '24px', marginBottom: '25px' }}>Sign Guestbook</h4>
                
                <form onSubmit={handleSubmit}>
                  <div className="form-group mb-20">
                    <label style={{ fontSize: '11px', textTransform: 'uppercase', letterSpacing: '1px', color: 'rgba(255,255,255,0.7)', display: 'block', marginBottom: '8px' }}>Your Name</label>
                    <input 
                      type="text" 
                      className="form-control" 
                      placeholder="Enter your name"
                      value={name}
                      onChange={(e) => setName(e.target.value)}
                      required
                      style={{ backgroundColor: '#1a1917', border: '1px solid rgba(255,255,255,0.1)', color: '#fff' }}
                    />
                  </div>

                  <div className="form-group mb-20">
                    <label style={{ fontSize: '11px', textTransform: 'uppercase', letterSpacing: '1px', color: 'rgba(255,255,255,0.7)', display: 'block', marginBottom: '8px' }}>Rating</label>
                    <select
                      className="form-control"
                      value={rating}
                      onChange={(e) => setRating(parseInt(e.target.value))}
                      style={{ backgroundColor: '#1a1917', border: '1px solid rgba(255,255,255,0.1)', color: '#fff' }}
                    >
                      <option value={5}>5 Stars (Excellent)</option>
                      <option value={4}>4 Stars (Good)</option>
                      <option value={3}>3 Stars (Average)</option>
                      <option value={2}>2 Stars (Poor)</option>
                      <option value={1}>1 Star (Terrible)</option>
                    </select>
                  </div>

                  <div className="form-group mb-25">
                    <label style={{ fontSize: '11px', textTransform: 'uppercase', letterSpacing: '1px', color: 'rgba(255,255,255,0.7)', display: 'block', marginBottom: '8px' }}>Your Comment</label>
                    <textarea 
                      className="form-control" 
                      placeholder="Share your dining thoughts..."
                      rows={4}
                      value={comment}
                      onChange={(e) => setComment(e.target.value)}
                      required
                      style={{ backgroundColor: '#1a1917', border: '1px solid rgba(255,255,255,0.1)', color: '#fff', resize: 'none' }}
                    ></textarea>
                  </div>

                  <button 
                    type="submit" 
                    className="btn btn--secondary btn--block"
                    style={{ backgroundColor: '#c59d5f', border: 'none', color: '#fff', padding: '14px', textTransform: 'uppercase', letterSpacing: '2px', fontWeight: 'bold', fontSize: '11px', borderRadius: '25px', cursor: 'pointer' }}
                  >
                    Post Review
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
export default GuestBook;
