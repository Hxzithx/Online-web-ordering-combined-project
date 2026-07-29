import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';

interface Slide {
  image: string;
  subheadline: string;
  headline: string;
  bio?: string;
  actions: React.ReactNode;
}

export const Home: React.FC = () => {
  const [currentSlide, setCurrentSlide] = useState(0);

  const slides: Slide[] = [
    {
      image: '/assets/images/sliders/slide-bg/2.jpg',
      subheadline: 'Granny Treat You Like A King',
      headline: 'Amazing Taste, Beautiful Place',
      actions: (
        <Link className="btn btn--white btn--bordered btn--lg" to="/reservation">
          Get A Reservation Now
        </Link>
      ),
    },
    {
      image: '/assets/images/sliders/slide-bg/1.jpg',
      subheadline: 'Made With Love',
      headline: 'We Serve Quality Food',
      actions: (
        <Link className="btn btn--white btn--bordered btn--lg" to="/shop">
          Purchase Now
        </Link>
      ),
    },
    {
      image: '/assets/images/sliders/slide-bg/3.jpg',
      subheadline: 'Made With Love',
      headline: 'Delicious Food',
      bio: 'Granny is a restaurant, bar and coffee roastery located in Egypt. We have awesome recipes and the most talented chefs in town!',
      actions: (
        <div style={{ display: 'flex', gap: '15px', justifyContent: 'center' }}>
          <Link className="btn btn--secondary btn--white" to="/about">
            Read More
          </Link>
          <Link className="btn btn--white btn--bordered" to="/shop">
            Purchase Now
          </Link>
        </div>
      ),
    },
  ];

  useEffect(() => {
    const timer = setInterval(() => {
      setCurrentSlide((prev) => (prev + 1) % slides.length);
    }, 6000);
    return () => clearInterval(timer);
  }, [slides.length]);

  return (
    <div className="homepage-2-wrapper">
      {/* Slider Section */}
      <section id="slider" className="slider slide-overlay-black" style={{ position: 'relative', height: '650px', overflow: 'hidden' }}>
        {slides.map((slide, index) => (
          <div
            key={index}
            className={`slide-item ${index === currentSlide ? 'active' : ''}`}
            style={{
              position: 'absolute',
              top: 0,
              left: 0,
              width: '100%',
              height: '100%',
              backgroundImage: `url(${slide.image})`,
              backgroundSize: 'cover',
              backgroundPosition: 'center',
              opacity: index === currentSlide ? 1 : 0,
              transition: 'opacity 1s ease-in-out',
              zIndex: index === currentSlide ? 1 : 0,
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
            }}
          >
            {/* Overlay */}
            <div style={{ position: 'absolute', top: 0, left: 0, right: 0, bottom: 0, background: 'rgba(0, 0, 0, 0.5)' }}></div>
            
            <div className="container" style={{ position: 'relative', zIndex: 2, textAlign: 'center', color: '#fff' }}>
              <div className="slide--subheadline" style={{ fontFamily: 'Great Vibes', color: '#c59d5f', fontSize: '32px', marginBottom: '15px' }}>
                {slide.subheadline}
              </div>
              <h1 className="slide--headline" style={{ fontFamily: 'Kaushan Script', fontSize: '56px', fontWeight: 400, color: '#fff', marginBottom: '25px', lineHeight: '1.2' }}>
                {slide.headline}
              </h1>
              {slide.bio && (
                <p className="slide--bio" style={{ fontSize: '15px', color: 'rgba(255, 255, 255, 0.7)', maxWidth: '600px', margin: '0 auto 30px', lineHeight: '24px' }}>
                  {slide.bio}
                </p>
              )}
              <div className="slide-action" style={{ marginTop: '20px' }}>
                {slide.actions}
              </div>
            </div>
          </div>
        ))}
      </section>

      {/* Banner/Intro Section */}
      <section id="banner5" className="banner banner-5 text-center" style={{ padding: '80px 0', backgroundColor: '#f9f9f9' }}>
        <div className="container">
          <div className="row">
            <div className="col-xs-12 col-sm-12 col-md-6 col-md-offset-3">
              <div className="heading heading-1 text-center mb-40">
                <p className="heading--subtitle" style={{ fontFamily: 'Great Vibes', color: '#c59d5f', fontSize: '24px' }}>Hello Dear</p>
                <h2 className="heading--title" style={{ fontFamily: 'Kaushan Script', fontSize: '36px', color: '#222' }}>Welcome To Granny</h2>
                <div className="divider--shape-4" style={{ width: '80px', height: '2px', backgroundColor: '#c59d5f', margin: '15px auto' }}></div>
              </div>
              <p style={{ color: '#a5a5a5', fontSize: '14px', lineHeight: '26px' }}>
                Granny restaurant has been serving gourmet delicacies with premium ingredients and unmatched service since 1995. We invite you to experience our signature steak cuts, wood-fired recipes, and craft beverages prepared by expert chefs in an atmospheric setting.
              </p>
              <Link to="/about" className="btn btn--secondary btn--link" style={{ borderBottom: '1px solid #c59d5f', color: '#c59d5f', textTransform: 'uppercase', fontSize: '11px', letterSpacing: '2px', fontWeight: 'bold', marginTop: '20px', display: 'inline-block', textDecoration: 'none' }}>
                Our Story
              </Link>
            </div>
          </div>
        </div>
      </section>

      {/* Signature Dishes Grid */}
      <section id="banner3" className="banner banner-3 pb-80" style={{ padding: '80px 0', backgroundColor: '#fff' }}>
        <div className="container">
          <div className="row">
            <div className="col-xs-12 col-sm-12 col-md-12 text-center">
              <div className="heading heading-1 text-center mb-50">
                <p className="heading--subtitle" style={{ fontFamily: 'Great Vibes', color: '#c59d5f', fontSize: '24px' }}>Chef Recommends</p>
                <h2 className="heading--title" style={{ fontFamily: 'Kaushan Script', fontSize: '36px', color: '#222' }}>Signature Dishes</h2>
                <div className="divider--shape-4" style={{ width: '80px', height: '2px', backgroundColor: '#c59d5f', margin: '15px auto' }}></div>
              </div>
            </div>
          </div>

          <div className="row">
            {/* Dish 1 */}
            <div className="col-xs-12 col-sm-4 col-md-4">
              <div className="dish-panel-inner" style={{ textAlign: 'center', padding: '20px', border: '1px solid #f0f0f0', borderRadius: '4px' }}>
                <div className="dish--img" style={{ overflow: 'hidden', borderRadius: '4px' }}>
                  <img src="/assets/images/shop/grid/1.jpg" alt="Dish 1" style={{ width: '100%', height: '220px', objectFit: 'cover', transition: 'all 0.3s' }} />
                </div>
                <div className="dish--content" style={{ marginTop: '20px' }}>
                  <span className="dish--price" style={{ color: '#c59d5f', fontSize: '20px', fontWeight: 'bold' }}>Rs. 1,250.00</span>
                  <h3 className="dish--title" style={{ fontFamily: 'Kaushan Script', fontSize: '20px', color: '#222', marginTop: '10px' }}>Grilled Ribeye Steak</h3>
                  <p className="dish--desc" style={{ color: '#a5a5a5', fontSize: '13px', lineHeight: '20px', marginTop: '10px' }}>
                    Premium Angus beef ribeye served with roasted baby potatoes and signature garlic butter sauce.
                  </p>
                  <Link to="/shop" className="dish-view-btn" style={{ textDecoration: 'none' }}>Order Now</Link>
                </div>
              </div>
            </div>

            {/* Dish 2 */}
            <div className="col-xs-12 col-sm-4 col-md-4">
              <div className="dish-panel-inner" style={{ textAlign: 'center', padding: '20px', border: '1px solid #f0f0f0', borderRadius: '4px' }}>
                <div className="dish--img" style={{ overflow: 'hidden', borderRadius: '4px' }}>
                  <img src="/assets/images/shop/grid/2.jpg" alt="Dish 2" style={{ width: '100%', height: '220px', objectFit: 'cover', transition: 'all 0.3s' }} />
                </div>
                <div className="dish--content" style={{ marginTop: '20px' }}>
                  <span className="dish--price" style={{ color: '#c59d5f', fontSize: '20px', fontWeight: 'bold' }}>Rs. 890.00</span>
                  <h3 className="dish--title" style={{ fontFamily: 'Kaushan Script', fontSize: '20px', color: '#222', marginTop: '10px' }}>Creamy Salmon Pasta</h3>
                  <p className="dish--desc" style={{ color: '#a5a5a5', fontSize: '13px', lineHeight: '20px', marginTop: '10px' }}>
                    Fresh tagliatelle tossed with pan-seared salmon fillet in a rich dill-creme fraiche sauce.
                  </p>
                  <Link to="/shop" className="dish-view-btn" style={{ textDecoration: 'none' }}>Order Now</Link>
                </div>
              </div>
            </div>

            {/* Dish 3 */}
            <div className="col-xs-12 col-sm-4 col-md-4">
              <div className="dish-panel-inner" style={{ textAlign: 'center', padding: '20px', border: '1px solid #f0f0f0', borderRadius: '4px' }}>
                <div className="dish--img" style={{ overflow: 'hidden', borderRadius: '4px' }}>
                  <img src="/assets/images/shop/grid/3.jpg" alt="Dish 3" style={{ width: '100%', height: '220px', objectFit: 'cover', transition: 'all 0.3s' }} />
                </div>
                <div className="dish--content" style={{ marginTop: '20px' }}>
                  <span className="dish--price" style={{ color: '#c59d5f', fontSize: '20px', fontWeight: 'bold' }}>Rs. 450.00</span>
                  <h3 className="dish--title" style={{ fontFamily: 'Kaushan Script', fontSize: '20px', color: '#222', marginTop: '10px' }}>Chocolate Lava Melt</h3>
                  <p className="dish--desc" style={{ color: '#a5a5a5', fontSize: '13px', lineHeight: '20px', marginTop: '10px' }}>
                    Warm dark chocolate cake with a molten lava center, topped with gourmet vanilla bean ice cream.
                  </p>
                  <Link to="/shop" className="dish-view-btn" style={{ textDecoration: 'none' }}>Order Now</Link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
};
export default Home;
