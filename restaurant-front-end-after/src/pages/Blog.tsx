import React from 'react';
import { Link } from 'react-router-dom';

interface BlogPost {
  title: string;
  excerpt: string;
  image: string;
  date: string;
  category: string;
}

export const Blog: React.FC = () => {
  const posts: BlogPost[] = [
    {
      title: 'Secrets to Cooking the Perfect Steaks',
      excerpt: 'Learn how to get a restaurant-quality sear on your ribeye steaks using cast iron pan, rosemary, garlic and butter basting.',
      image: '/assets/images/shop/grid/1.jpg',
      date: '2026-07-25',
      category: 'Steak & Meat'
    },
    {
      title: 'Our Signature Tomato Dill Creme Sauce Recipe',
      excerpt: 'We reveal the secret ingredient ratios in our fresh dill-creme fraiche salmon pasta sauce cooked daily at Granny.',
      image: '/assets/images/shop/grid/2.jpg',
      date: '2026-07-20',
      category: 'Pastas & Sauces'
    },
    {
      title: 'Why Real Molten Lava Center is all about timing',
      excerpt: 'Our dessert chef breaks down the exact minutes and temperatures required to keep the chocolate centers perfectly molten.',
      image: '/assets/images/shop/grid/3.jpg',
      date: '2026-07-15',
      category: 'Desserts & Sweets'
    }
  ];

  return (
    <div className="blog-wrapper">
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
                  <div className="title--subtitle">Stories & Recipes</div>
                  <div className="title--heading">
                    <h1>Blog Carousel</h1>
                  </div>
                </div>
                <ol className="breadcrumb">
                  <li><Link to="/home">Home</Link></li>
                  <li className="active">Blog</li>
                </ol>
                <div className="divider--shape-1down divider--shape-gray"></div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Blog Cards Grid */}
      <section className="blog-section bg-white" style={{ padding: '80px 0' }}>
        <div className="container">
          <div className="row">
            {posts.map((post, index) => (
              <div key={index} className="col-xs-12 col-sm-4 mb-30">
                <div style={{ border: '1px solid #f0f0f0', borderRadius: '4px', overflow: 'hidden', backgroundColor: '#fff', boxShadow: '0 2px 10px rgba(0,0,0,0.05)' }}>
                  <div style={{ position: 'relative' }}>
                    <img src={post.image} alt={post.title} style={{ width: '100%', height: '200px', objectFit: 'cover' }} />
                    <span style={{ position: 'absolute', top: '15px', left: '15px', backgroundColor: '#c59d5f', color: '#fff', fontSize: '10px', textTransform: 'uppercase', padding: '4px 10px', fontWeight: 'bold', letterSpacing: '1px', borderRadius: '2px' }}>
                      {post.category}
                    </span>
                  </div>
                  
                  <div style={{ padding: '25px' }}>
                    <span style={{ fontSize: '11px', color: '#a5a5a5', display: 'block', marginBottom: '8px' }}>
                      <i className="fa fa-calendar-o" style={{ marginRight: '5px' }}></i> {post.date}
                    </span>
                    <h4 style={{ fontFamily: 'Raleway', fontWeight: 'bold', fontSize: '16px', color: '#222', margin: '0 0 10px 0', lineHeight: '22px' }}>
                      {post.title}
                    </h4>
                    <p style={{ fontSize: '13px', color: '#666', lineHeight: '20px', margin: '0 0 20px 0' }}>
                      {post.excerpt}
                    </p>
                    <a href="#" onClick={(e) => e.preventDefault()} style={{ color: '#c59d5f', fontWeight: 'bold', fontSize: '11px', textTransform: 'uppercase', letterSpacing: '1px', textDecoration: 'none' }}>
                      Read More &rarr;
                    </a>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>
    </div>
  );
};
export default Blog;
