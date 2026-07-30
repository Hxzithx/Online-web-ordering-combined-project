import React, { useState, useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import api from '../services/api';
import Swal from 'sweetalert2';
import Loader from '../components/Loader';

interface Category {
  id: number;
  name: string;
}

interface MenuItem {
  id: number;
  base_name: string;
  image_url: string | null;
  category: Category;
  real_price: number;
}

export const ShopProducts: React.FC = () => {
  const navigate = useNavigate();
  
  const [items, setItems] = useState<MenuItem[]>([]);
  const [filteredItems, setFilteredItems] = useState<MenuItem[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  // Filters
  const [selectedCategory, setSelectedCategory] = useState<string>('all');
  const [priceRange, setPriceRange] = useState<[number, number]>([0, 1500]);
  const [categoriesList, setCategoriesList] = useState<string[]>(['all']);

  // Modal
  const [showOrderTypeModal, setShowOrderTypeModal] = useState(false);

  useEffect(() => {
    // Check if orderType is in localStorage
    const savedType = localStorage.getItem('orderType');
    if (!savedType) {
      setShowOrderTypeModal(true);
    }

    const fetchShopItems = async () => {
      try {
        setLoading(true);
        const [menuRes, pricesRes] = await Promise.all([
          api.get('api/menu'),
          api.get('api/menu/item-prices').catch(() => ({ data: { item_prices: [] } }))
        ]);

        const itemsData = menuRes.data.items || [];
        const allPrices = pricesRes.data.item_prices || [];

        // Build price lookup map
        const priceMap: Record<number, number> = {};
        allPrices.forEach((p: any) => {
          if (p.item_id && (priceMap[p.item_id] === undefined || Number(p.price) < priceMap[p.item_id])) {
            priceMap[p.item_id] = Number(p.price);
          }
        });

        const itemsWithPrices = itemsData.map((item: any) => ({
          ...item,
          real_price: priceMap[item.id] !== undefined ? priceMap[item.id] : 0
        }));

        setItems(itemsWithPrices);
        setFilteredItems(itemsWithPrices);

        // Extract unique categories
        const cats = new Set<string>();
        itemsWithPrices.forEach((item: MenuItem) => {
          if (item.category?.name) {
            cats.add(item.category.name.toLowerCase());
          }
        });
        setCategoriesList(['all', ...Array.from(cats)]);
      } catch (err) {
        console.error('Error fetching shop products:', err);
        setError('Failed to fetch products. Please try again.');
      } finally {
        setLoading(false);
      }
    };

    fetchShopItems();
  }, []);

  // Filter items when filters change
  useEffect(() => {
    let result = items;

    // Filter by category
    if (selectedCategory !== 'all') {
      result = result.filter(item => item.category?.name?.toLowerCase() === selectedCategory);
    }

    // Filter by price
    result = result.filter(item => item.real_price >= priceRange[0] && item.real_price <= priceRange[1]);

    setFilteredItems(result);
  }, [selectedCategory, priceRange, items]);

  const handleOrderTypeSelection = (type: string) => {
    localStorage.setItem('orderType', type);
    setShowOrderTypeModal(false);
    
    // Dispatch cart-updated custom event to refresh header
    window.dispatchEvent(new Event('cart-updated'));

    if (type === 'dine-in') {
      Swal.fire({
        icon: 'info',
        title: 'Table Reservation Required',
        text: 'Since you selected Dine-In, let us redirect you to the table booking page first.',
        confirmButtonColor: '#c59d5f',
        background: '#0e0d0b',
        color: '#f0ebe0',
      }).then(() => {
        navigate('/reservation');
      });
    } else {
      Swal.fire({
        icon: 'success',
        title: `Order Mode: ${type === 'takeaway' ? 'Takeaway' : 'Online Delivery'}`,
        text: 'You can now browse products and place your order!',
        timer: 1500,
        showConfirmButton: false,
        background: '#0e0d0b',
        color: '#f0ebe0',
        iconColor: '#c59d5f',
      });
    }
  };

  return (
    <div className="shopproducts-wrapper">
      {/* Order Type Selection Modal */}
      {showOrderTypeModal && (
        <div id="orderTypeModal" className="order-modal" style={{ display: 'flex', position: 'fixed', top: 0, left: 0, width: '100%', height: '100%', backgroundColor: 'rgba(0,0,0,0.85)', zIndex: 9999, alignItems: 'center', justifyContent: 'center' }}>
          <div className="order-modal-content" style={{ backgroundColor: '#0e0d0b', border: '1px solid rgba(197, 157, 95, 0.4)', padding: '40px', borderRadius: '4px', textAlign: 'center', maxWidth: '450px', width: '90%' }}>
            <h3 style={{ fontFamily: 'Kaushan Script', color: '#fff', fontSize: '28px', marginBottom: '30px' }}>Select Your Order Type</h3>
            
            <button className="order-btn btn btn--secondary btn--block" onClick={() => handleOrderTypeSelection('dine-in')} style={{ display: 'block', width: '100%', margin: '10px 0', padding: '14px', fontFamily: 'Raleway', fontWeight: 'bold', fontSize: '12px', textTransform: 'uppercase', letterSpacing: '2px' }}>
              Dine In
            </button>
            <button className="order-btn btn btn--secondary btn--block" onClick={() => handleOrderTypeSelection('takeaway')} style={{ display: 'block', width: '100%', margin: '10px 0', padding: '14px', fontFamily: 'Raleway', fontWeight: 'bold', fontSize: '12px', textTransform: 'uppercase', letterSpacing: '2px' }}>
              Take Away
            </button>
            <button className="order-btn btn btn--secondary btn--block" onClick={() => handleOrderTypeSelection('online')} style={{ display: 'block', width: '100%', margin: '10px 0', padding: '14px', fontFamily: 'Raleway', fontWeight: 'bold', fontSize: '12px', textTransform: 'uppercase', letterSpacing: '2px' }}>
              Online Order
            </button>
          </div>
        </div>
      )}

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
                  <div className="title--subtitle">Menu</div>
                  <div className="title--heading">
                    <h1>Make Your Order</h1>
                  </div>
                </div>
                <ol className="breadcrumb">
                  <li><Link to="/home">Home</Link></li>
                  <li className="active">Shop</li>
                </ol>
                <div className="divider--shape-1down divider--shape-gray"></div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Shop Content */}
      <section id="shop" className="shop shop-3 bg-gray pb-90" style={{ padding: '80px 0' }}>
        <div className="container">
          {error && (
            <div className="alert alert-danger text-center animate-fade-in-scale" style={{ margin: '20px auto', maxWidth: '600px' }}>
              {error}
            </div>
          )}

          {loading && (
            <Loader text="Loading shop catalog..." skeletonType="grid" count={6} />
          )}

          {!loading && !error && (
            <div className="row animate-fade-in-up">
              {/* Products Grid */}
              <div className="col-md-9">
                <div className="shop-options" style={{ marginBottom: '30px', display: 'flex', justifyContent: 'space-between', alignItems: 'center', borderBottom: '1px solid #e0e0e0', paddingBottom: '15px' }}>
                  <div className="product-options2">
                    <span style={{ fontSize: '13px', color: '#222', fontWeight: 'bold', textTransform: 'uppercase', letterSpacing: '1px' }}>
                      Products ({filteredItems.length} found)
                    </span>
                  </div>
                </div>

                <div className="row">
                  {filteredItems.length === 0 ? (
                    <div className="col-xs-12 text-center animate-fade-in-scale" style={{ padding: '40px 0', color: '#a5a5a5' }}>
                      No items match your active filters.
                    </div>
                  ) : (
                    filteredItems.map((item, idx) => (
                      <div key={item.id} className={`col-xs-12 col-sm-6 col-md-4 mb-30 animate-fade-in-up stagger-${(idx % 6) + 1}`}>
                        <div className="product-item" style={{ border: '1px solid #f0f0f0', backgroundColor: '#fff', height: '100%', transition: 'transform 0.3s ease, box-shadow 0.3s ease' }}>
                          <div className="product--img" style={{ position: 'relative', overflow: 'hidden' }}>
                            <img
                              src={item.image_url || '/assets/images/shop/grid/15.jpg'}
                              alt={item.base_name}
                              style={{ width: '100%', height: '240px', objectFit: 'cover' }}
                            />
                            <div className="product--hover" style={{ position: 'absolute', top: 0, left: 0, right: 0, bottom: 0, backgroundColor: 'rgba(197, 157, 95, 0.85)', display: 'flex', alignItems: 'center', justifyContent: 'center', opacity: 0, transition: 'opacity 0.3s' }}>
                              <div className="product--action">
                                <Link to={`/menu/${item.id}`} style={{ color: '#fff', fontFamily: 'Raleway', fontWeight: 'bold', fontSize: '11px', textTransform: 'uppercase', letterSpacing: '2px', border: '2px solid #fff', padding: '10px 20px', textDecoration: 'none' }}>
                                  View Details
                                </Link>
                              </div>
                            </div>
                            <div className="divider--shape-1down"></div>
                          </div>
                          
                          <div className="product--content text-center" style={{ padding: '20px 15px' }}>
                            <div className="product--type" style={{ fontSize: '10px', textTransform: 'uppercase', color: '#a5a5a5', letterSpacing: '1px' }}>
                              <span>{item.category?.name || 'Food'}</span>
                            </div>
                            <div className="product--title" style={{ margin: '10px 0' }}>
                              <h3 style={{ fontFamily: 'Kaushan Script', fontSize: '18px', color: '#222', margin: 0 }}>
                                <Link to={`/menu/${item.id}`} style={{ color: '#222', textDecoration: 'none' }}>
                                  {item.base_name}
                                </Link>
                              </h3>
                            </div>
                            <div className="product--price" style={{ color: '#c59d5f', fontSize: '18px', fontWeight: 'bold' }}>
                              Rs. {Number(item.real_price).toFixed(2)}
                            </div>
                          </div>
                        </div>
                      </div>
                    ))
                  )}
                </div>
              </div>

              {/* Sidebar Filters */}
              <div className="col-md-3">
                {/* Category Selection */}
                <div className="widget widget-tags" style={{ border: '1px solid #f0f0f0', padding: '25px', backgroundColor: '#fff', marginBottom: '30px' }}>
                  <div className="widget--title" style={{ borderBottom: '2px solid #c59d5f', paddingBottom: '10px', marginBottom: '20px' }}>
                    <h5 style={{ fontFamily: 'Raleway', fontWeight: 'bold', fontSize: '12px', textTransform: 'uppercase', letterSpacing: '2px', color: '#222', margin: 0 }}>Categories</h5>
                  </div>
                  <div className="widget--content" style={{ display: 'flex', flexDirection: 'column', gap: '8px' }}>
                    {categoriesList.map((cat) => (
                      <a
                        key={cat}
                        href="#"
                        onClick={(e) => {
                          e.preventDefault();
                          setSelectedCategory(cat);
                        }}
                        className={`tag-btn ${cat === selectedCategory ? 'active' : ''}`}
                        style={{
                          display: 'block',
                          padding: '8px 12px',
                          border: cat === selectedCategory ? '1px solid #c59d5f' : '1px solid #f0f0f0',
                          backgroundColor: cat === selectedCategory ? '#c59d5f' : '#f9f9f9',
                          color: cat === selectedCategory ? '#fff' : '#222',
                          fontFamily: 'Raleway',
                          fontWeight: 'bold',
                          fontSize: '11px',
                          textTransform: 'uppercase',
                          letterSpacing: '1px',
                          textAlign: 'center',
                          textDecoration: 'none',
                          borderRadius: '2px',
                          transition: 'all 0.2s',
                        }}
                      >
                        {cat}
                      </a>
                    ))}
                  </div>
                </div>

                {/* Price range filter */}
                <div className="widget widget-filter" style={{ border: '1px solid #f0f0f0', padding: '25px', backgroundColor: '#fff' }}>
                  <div className="widget--title" style={{ borderBottom: '2px solid #c59d5f', paddingBottom: '10px', marginBottom: '20px' }}>
                    <h5 style={{ fontFamily: 'Raleway', fontWeight: 'bold', fontSize: '12px', textTransform: 'uppercase', letterSpacing: '2px', color: '#222', margin: 0 }}>Pricing Filter</h5>
                  </div>
                  <div className="widget--content">
                    <input
                      type="range"
                      min="0"
                      max="2000"
                      step="50"
                      value={priceRange[1]}
                      onChange={(e) => setPriceRange([priceRange[0], parseInt(e.target.value)])}
                      style={{ width: '100%', accentColor: '#c59d5f', cursor: 'pointer' }}
                    />
                    <p style={{ marginTop: '15px', color: '#a5a5a5', fontSize: '13px' }}>
                      Price: <strong style={{ color: '#222' }}>Rs. {priceRange[0]} - Rs. {priceRange[1]}</strong>
                    </p>
                  </div>
                </div>
              </div>
            </div>
          )}
        </div>
      </section>
    </div>
  );
};
export default ShopProducts;
