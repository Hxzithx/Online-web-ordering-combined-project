import React, { useState, useEffect } from 'react';
import api from '../services/api';
import { Link } from 'react-router-dom';
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
  description?: string;
  real_price: number | null;
}

export const MenuBoard: React.FC = () => {
  const [groupedItems, setGroupedItems] = useState<Record<string, MenuItem[]>>({});
  const [categories, setCategories] = useState<string[]>([]);
  const [activeCategory, setActiveCategory] = useState<string>('');
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    const fetchMenu = async () => {
      try {
        setLoading(true);
        const response = await api.get('api/menu');
        const items = response.data.items || [];

        // Fetch prices in parallel for all items to speed up
        const itemsWithPrices = await Promise.all(
          items.map(async (item: any) => {
            try {
              const priceRes = await api.get(`api/menu/item-prices/${item.id}`);
              item.real_price = priceRes.data.item_price?.price ?? null;
            } catch (err) {
              console.error(`Error fetching price for item ${item.id}:`, err);
              item.real_price = null;
            }
            return item;
          })
        );

        // Group by category name
        const groups: Record<string, MenuItem[]> = {};
        const catSet = new Set<string>();

        itemsWithPrices.forEach((item: MenuItem) => {
          const catName = item.category?.name || 'General';
          catSet.add(catName);
          if (!groups[catName]) {
            groups[catName] = [];
          }
          groups[catName].push(item);
        });

        const sortedCategories = Array.from(catSet);
        setGroupedItems(groups);
        setCategories(sortedCategories);
        if (sortedCategories.length > 0) {
          setActiveCategory(sortedCategories[0]);
        }
      } catch (err: any) {
        console.error('Error fetching menu details:', err);
        setError('Unable to load menu board. Please try again later.');
      } finally {
        setLoading(false);
      }
    };

    fetchMenu();
  }, []);

  return (
    <div className="menuboard-wrapper">
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
                  <div className="title--subtitle">Our Menu</div>
                  <div className="title--heading">
                    <h1>Menu Board</h1>
                  </div>
                </div>
                <ol className="breadcrumb">
                  <li><Link to="/home">Home</Link></li>
                  <li className="active">Menu</li>
                </ol>
                <div className="divider--shape-1down divider--shape-gray"></div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Menu Board Sections */}
      <section id="menuBoard" className="menu-board-section bg-white" style={{ padding: '80px 0' }}>
        <div className="container">
          {loading && (
            <Loader text="Loading our premium menu board..." skeletonType="list" count={4} />
          )}

          {error && (
            <div className="alert alert-danger text-center animate-fade-in-scale" style={{ margin: '30px auto', maxWidth: '600px' }}>
              {error}
            </div>
          )}

          {!loading && !error && (
            <div className="animate-fade-in-up">
              {/* Category tabs */}
              <ul className="nav nav-tabs" style={{ display: 'flex', justifyContent: 'center', flexWrap: 'wrap', borderBottom: '2px solid #f0f0f0', marginBottom: '40px' }}>
                {categories.map((cat) => (
                  <li key={cat} className={cat === activeCategory ? 'active' : ''} style={{ listStyle: 'none' }}>
                    <a
                      href="#"
                      onClick={(e) => {
                        e.preventDefault();
                        setActiveCategory(cat);
                      }}
                      style={{
                        fontFamily: 'Raleway',
                        fontWeight: 700,
                        letterSpacing: '3px',
                        textTransform: 'uppercase',
                        fontSize: '11px',
                        color: cat === activeCategory ? '#c59d5f' : '#a5a5a5',
                        borderBottom: cat === activeCategory ? '2px solid #c59d5f' : '2px solid transparent',
                        padding: '15px 25px',
                        display: 'block',
                        textDecoration: 'none',
                        transition: 'all 0.3s ease',
                      }}
                    >
                      {cat}
                    </a>
                  </li>
                ))}
              </ul>

              {/* Items List */}
              <div className="row animate-fade-in-up">
                {activeCategory && groupedItems[activeCategory]?.map((item, idx) => (
                  <div key={item.id} className={`col-xs-12 col-sm-6 col-md-6 mb-40 animate-fade-in-up stagger-${(idx % 6) + 1}`}>
                    <div className="dish-panel-inner" style={{ display: 'flex', gap: '20px', padding: '15px', backgroundColor: '#f9f9f9', border: '1px solid #f0f0f0', borderRadius: '4px' }}>
                      <div className="dish--img" style={{ flexShrink: 0, width: '120px', height: '120px', overflow: 'hidden', borderRadius: '4px' }}>
                        <img
                          src={item.image_url || '/assets/images/shop/grid/15.jpg'}
                          alt={item.base_name}
                          style={{ width: '100%', height: '100%', objectFit: 'cover' }}
                        />
                      </div>
                      
                      <div className="dish--content" style={{ flexGrow: 1, display: 'flex', flexDirection: 'column', justifyContent: 'space-between' }}>
                        <div>
                          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                            <h3 className="dish--title" style={{ fontFamily: 'Kaushan Script', fontSize: '20px', color: '#222', margin: 0 }}>
                              {item.base_name}
                            </h3>
                             <span className="dish--price" style={{ color: '#c59d5f', fontSize: '18px', fontWeight: 'bold' }}>
                               {item.real_price !== null ? `Rs. ${Number(item.real_price).toFixed(2)}` : 'N/A'}
                             </span>
                          </div>
                          
                          <div className="dish-divider" style={{ width: '30px', height: '1px', backgroundColor: '#c59d5f', margin: '8px 0' }}></div>
                          
                          <p className="dish--desc" style={{ color: '#a5a5a5', fontSize: '13px', lineHeight: '20px', margin: 0 }}>
                            {item.description || 'Our signature recipe cooked using fresh and handpicked ingredients.'}
                          </p>
                        </div>
                        
                        <div style={{ alignSelf: 'flex-end', marginTop: '10px' }}>
                          <Link to={`/menu/${item.id}`} className="dish-view-btn" style={{ textDecoration: 'none' }}>
                            Configure & Add
                          </Link>
                        </div>
                      </div>
                    </div>
                  </div>
                ))}
              </div>
            </div>
          )}
        </div>
      </section>
    </div>
  );
};
export default MenuBoard;
