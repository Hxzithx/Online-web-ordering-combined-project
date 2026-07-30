import React, { useState, useEffect } from 'react';
import { useParams, Link, useNavigate } from 'react-router-dom';
import api from '../services/api';
import { useAuth } from '../context/AuthContext';
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
  description?: string;
  real_price: number;
}

interface Portion {
  id: number;
  name: string;
  additional_price: number | null;
  multiplier: number | null;
}

interface AddOn {
  id: number;
  name: string;
  price: number;
  quantity?: number; // local selection count
}

export const ShopSingle: React.FC = () => {
  const { id } = useParams<{ id: string }>();
  const { user } = useAuth();
  const navigate = useNavigate();

  const [item, setItem] = useState<MenuItem | null>(null);
  const [portions, setPortions] = useState<Portion[]>([]);
  const [addons, setAddons] = useState<AddOn[]>([]);
  const [categories, setCategories] = useState<{ name: string; items_count: number }[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  // Configuration selection state
  const [selectedPortion, setSelectedPortion] = useState<Portion | null>(null);
  const [selectedAddons, setSelectedAddons] = useState<Record<number, { addon: AddOn; qty: number }>>({});
  const [quantity, setQuantity] = useState(1);
  const [note, setNote] = useState('');

  // Total price computed locally
  const [totalPrice, setTotalPrice] = useState(0);

  useEffect(() => {
    const fetchData = async () => {
      try {
        setLoading(true);
        // 1. Fetch item
        const itemRes = await api.get(`api/menu/${id}`);
        const rawItem = itemRes.data.item || itemRes.data;

        // Fetch real price
        try {
          const priceRes = await api.get(`api/menu/item-prices/${id}`);
          rawItem.real_price = priceRes.data.item_price?.price ?? 0;
        } catch {
          rawItem.real_price = 0;
        }
        setItem(rawItem);

        // 2. Fetch portions
        try {
          const portionsRes = await api.get('api/portions');
          const portionsData = portionsRes.data.portions || [];
          setPortions(portionsData);
          if (portionsData.length > 0) {
            setSelectedPortion(portionsData[0]);
          }
        } catch (err) {
          console.error('Error fetching portions:', err);
        }

        // 3. Fetch add-ons
        try {
          const addonsRes = await api.get('api/add-ons');
          setAddons(addonsRes.data.AddOn || []);
        } catch (err) {
          console.error('Error fetching add-ons:', err);
        }

        // 4. Fetch all items for related items & sidebar categories
        const allRes = await api.get('api/menu');
        const allItems = allRes.data.items || [];

        // Calculate category counts
        const catMap: Record<string, number> = {};
        allItems.forEach((i: any) => {
          const name = i.category?.name || 'General';
          catMap[name] = (catMap[name] || 0) + 1;
        });
        const catList = Object.entries(catMap).map(([name, count]) => ({
          name,
          items_count: count,
        }));
        setCategories(catList);

      } catch (err: any) {
        console.error('Error loading shop single item:', err);
        setError('Product not found or failed to load details.');
      } finally {
        setLoading(false);
      }
    };

    fetchData();
  }, [id]);

  // Recalculate price
  useEffect(() => {
    if (!item) return;

    let base = Number(item.real_price);
    
    // Portion logic
    if (selectedPortion) {
      if (selectedPortion.multiplier) {
        base = base * selectedPortion.multiplier;
      }
      if (selectedPortion.additional_price) {
        base = base + Number(selectedPortion.additional_price);
      }
    }

    // Addons logic
    let addonsTotal = 0;
    Object.values(selectedAddons).forEach((val) => {
      addonsTotal += val.addon.price * val.qty;
    });

    setTotalPrice((base * quantity) + addonsTotal);
  }, [item, selectedPortion, selectedAddons, quantity]);

  const handleAddonClick = (addon: AddOn) => {
    setSelectedAddons((prev) => {
      const next = { ...prev };
      if (next[addon.id]) {
        delete next[addon.id];
      } else {
        next[addon.id] = { addon, qty: 1 };
      }
      return next;
    });
  };

  const handleAddonQtyChange = (addonId: number, inc: boolean) => {
    setSelectedAddons((prev) => {
      const next = { ...prev };
      if (next[addonId]) {
        const newQty = inc ? next[addonId].qty + 1 : Math.max(1, next[addonId].qty - 1);
        next[addonId] = { ...next[addonId], qty: newQty };
      }
      return next;
    });
  };

  const handleAddToCart = () => {
    if (!item || !selectedPortion) return;

    const cartKey = `cart_${user?.email || 'guest'}`;
    const cart = JSON.parse(localStorage.getItem(cartKey) || '[]');

    // Construct unique cart item ID
    const addonDetails = Object.values(selectedAddons)
      .map((val) => `${val.addon.id}x${val.qty}`)
      .sort()
      .join('-');
    const cartItemId = `${item.id}_${selectedPortion.id}_${addonDetails || 'none'}`;

    // Calculate final unit price (portion price + addons)
    let unitPrice = item.real_price;
    if (selectedPortion.multiplier) {
      unitPrice = unitPrice * selectedPortion.multiplier;
    }
    if (selectedPortion.additional_price) {
      unitPrice = unitPrice + Number(selectedPortion.additional_price);
    }
    
    // Add addons total to unitPrice or separate?
    // We add portion price to base unitPrice, and add addons cost separately to subtotal or as individual item.
    // Let's store unitPrice as base + portion price, and addons details on the item.
    const cartItem = {
      id: cartItemId,
      itemId: item.id,
      name: item.base_name,
      price: unitPrice,
      quantity,
      portion: selectedPortion.name,
      portionId: selectedPortion.id,
      image: item.image_url,
      note,
      addons: Object.values(selectedAddons).map((val) => ({
        id: val.addon.id,
        name: val.addon.name,
        price: val.addon.price,
        qty: val.qty,
      })),
    };

    // Check if exists
    const existingIndex = cart.findIndex((i: any) => i.id === cartItemId);
    if (existingIndex > -1) {
      cart[existingIndex].quantity += quantity;
    } else {
      cart.push(cartItem);
    }

    localStorage.setItem(cartKey, JSON.stringify(cart));
    
    // Dispatch event to header
    window.dispatchEvent(new Event('cart-updated'));

    Swal.fire({
      icon: 'success',
      title: 'Added to Cart',
      text: `${item.base_name} has been added to your cart successfully!`,
      showCancelButton: true,
      confirmButtonColor: '#c59d5f',
      cancelButtonColor: '#222',
      confirmButtonText: 'View Cart',
      cancelButtonText: 'Continue Shopping',
      background: '#0e0d0b',
      color: '#f0ebe0',
      iconColor: '#c59d5f',
    }).then((result) => {
      if (result.isConfirmed) {
        navigate('/shopping-cart');
      } else {
        navigate('/shop');
      }
    });
  };

  return (
    <div className="shopsingle-wrapper">
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
                  <div className="title--subtitle">Product details</div>
                  <div className="title--heading">
                    <h1>{item?.base_name || 'Customize Product'}</h1>
                  </div>
                </div>
                <ol className="breadcrumb">
                  <li><Link to="/home">Home</Link></li>
                  <li><Link to="/shop">Shop</Link></li>
                  <li className="active">Product</li>
                </ol>
                <div className="divider--shape-1down divider--shape-gray"></div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Shop Single Details */}
      <section className="shop-single" style={{ padding: '80px 0', backgroundColor: '#fff' }}>
        <div className="container">
          {error && (
            <div className="alert alert-danger text-center animate-fade-in-scale" style={{ margin: '20px auto', maxWidth: '600px' }}>
              {error}
            </div>
          )}

          {loading && (
            <Loader text="Loading product configuration..." showSkeletons={false} />
          )}

          {!loading && item && (
            <div className="row animate-fade-in-up">
              {/* Product Info Column */}
              <div className="col-md-9">
                <div className="row">
                  <div className="col-md-6">
                    <div className="product-img" style={{ border: '1px solid #f0f0f0', borderRadius: '4px', overflow: 'hidden' }}>
                      <img
                        src={item.image_url || '/assets/images/shop/grid/15.jpg'}
                        alt={item.base_name}
                        style={{ width: '100%', height: '380px', objectFit: 'cover' }}
                      />
                    </div>
                  </div>

                  <div className="col-md-6">
                    <div className="product-title">
                      <h3 style={{ fontFamily: 'Kaushan Script', fontSize: '32px', color: '#222', marginBottom: '10px' }}>
                        {item.base_name}
                      </h3>
                    </div>

                    <div className="product-meta" style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', padding: '15px 0', borderTop: '1px solid #f0f0f0', borderBottom: '1px solid #f0f0f0', marginBottom: '20px' }}>
                      <div className="product-price">
                        <span style={{ color: '#c59d5f', fontSize: '24px', fontWeight: 'bold' }}>
                          Rs. {totalPrice.toFixed(2)}
                        </span>
                      </div>
                      <div className="product-review" style={{ display: 'flex', gap: '5px', color: '#c59d5f', fontSize: '12px' }}>
                        <i className="fa fa-star"></i>
                        <i className="fa fa-star"></i>
                        <i className="fa fa-star"></i>
                        <i className="fa fa-star"></i>
                        <i className="fa fa-star-half-o"></i>
                        <span style={{ color: '#a5a5a5', marginLeft: '5px' }}>(4.8 Rating)</span>
                      </div>
                    </div>

                    <div className="product-desc">
                      <p style={{ color: '#a5a5a5', fontSize: '13px', lineHeight: '22px', marginBottom: '20px' }}>
                        {item.description || 'Our signature recipe cooked using fresh and handpicked ingredients, customized according to your exact preferences.'}
                      </p>
                    </div>

                    {/* Portion Selectors */}
                    {portions.length > 0 && (
                      <div style={{ marginBottom: '20px' }}>
                        <h5 style={{ fontFamily: 'Raleway', fontWeight: 'bold', fontSize: '11px', textTransform: 'uppercase', color: '#222', letterSpacing: '1px', marginBottom: '10px' }}>
                          Select Portion
                        </h5>
                        <div style={{ display: 'flex', gap: '10px' }}>
                          {portions.map((p) => (
                            <button
                              key={p.id}
                              onClick={() => setSelectedPortion(p)}
                              className={`btn btn--sm ${selectedPortion?.id === p.id ? 'btn--secondary' : ''}`}
                              style={{
                                border: '1px solid #c59d5f',
                                color: selectedPortion?.id === p.id ? '#fff' : '#c59d5f',
                                backgroundColor: selectedPortion?.id === p.id ? '#c59d5f' : 'transparent',
                                textTransform: 'uppercase',
                                padding: '8px 16px',
                                fontSize: '11px',
                                fontWeight: 'bold',
                                borderRadius: '25px',
                              }}
                            >
                              {p.name}
                            </button>
                          ))}
                        </div>
                      </div>
                    )}

                    {/* Add-ons Checklist */}
                    {addons.length > 0 && (
                      <div style={{ marginBottom: '25px' }}>
                        <h5 style={{ fontFamily: 'Raleway', fontWeight: 'bold', fontSize: '11px', textTransform: 'uppercase', color: '#222', letterSpacing: '1px', marginBottom: '10px' }}>
                          Add Extra Toppings
                        </h5>
                        <div style={{ display: 'flex', flexDirection: 'column', gap: '8px' }}>
                          {addons.map((a) => {
                            const isSelected = !!selectedAddons[a.id];
                            return (
                              <div
                                key={a.id}
                                style={{
                                  display: 'flex',
                                  alignItems: 'center',
                                  justifyContent: 'space-between',
                                  padding: '8px 12px',
                                  border: '1px solid #f0f0f0',
                                  borderRadius: '4px',
                                  backgroundColor: isSelected ? 'rgba(197, 157, 95, 0.05)' : '#fff',
                                }}
                              >
                                <label style={{ display: 'flex', alignItems: 'center', gap: '10px', margin: 0, cursor: 'pointer', fontFamily: 'Raleway', fontSize: '13px', color: '#222' }}>
                                  <input
                                    type="checkbox"
                                    checked={isSelected}
                                    onChange={() => handleAddonClick(a)}
                                    style={{ accentColor: '#c59d5f' }}
                                  />
                                  <span>{a.name} (+ Rs. {Number(a.price).toFixed(2)})</span>
                                </label>

                                {isSelected && (
                                  <div style={{ display: 'flex', alignItems: 'center', gap: '5px' }}>
                                    <button
                                      onClick={() => handleAddonQtyChange(a.id, false)}
                                      style={{ width: '24px', height: '24px', borderRadius: '50%', border: '1px solid #c59d5f', background: 'none', color: '#c59d5f', display: 'flex', alignItems: 'center', justifyContent: 'center', cursor: 'pointer', fontWeight: 'bold' }}
                                    >
                                      -
                                    </button>
                                    <span style={{ fontSize: '13px', fontWeight: 'bold', color: '#222', minWidth: '15px', textAlign: 'center' }}>
                                      {selectedAddons[a.id].qty}
                                    </span>
                                    <button
                                      onClick={() => handleAddonQtyChange(a.id, true)}
                                      style={{ width: '24px', height: '24px', borderRadius: '50%', border: '1px solid #c59d5f', background: 'none', color: '#c59d5f', display: 'flex', alignItems: 'center', justifyContent: 'center', cursor: 'pointer', fontWeight: 'bold' }}
                                    >
                                      +
                                    </button>
                                  </div>
                                )}
                              </div>
                            );
                          })}
                        </div>
                      </div>
                    )}

                    {/* Special Notes */}
                    <div style={{ marginBottom: '25px' }}>
                      <h5 style={{ fontFamily: 'Raleway', fontWeight: 'bold', fontSize: '11px', textTransform: 'uppercase', color: '#222', letterSpacing: '1px', marginBottom: '10px' }}>
                        Special Note / Request
                      </h5>
                      <input
                        type="text"
                        className="form-control"
                        placeholder="E.g. No onions, extra spicy, etc."
                        value={note}
                        onChange={(e) => setNote(e.target.value)}
                        style={{ height: '40px', fontSize: '13px' }}
                      />
                    </div>

                    {/* Quantity & CTA */}
                    <div className="product-action" style={{ display: 'flex', gap: '20px', alignItems: 'center' }}>
                      <div className="product-quantity" style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
                        <span className="qua" style={{ fontFamily: 'Raleway', fontWeight: 'bold', fontSize: '11px', textTransform: 'uppercase' }}>Quantity</span>
                        <div style={{ display: 'flex', alignItems: 'center', border: '2px solid #e0e0e0', borderRadius: '25px', overflow: 'hidden' }}>
                          <button
                            onClick={() => setQuantity(q => Math.max(1, q - 1))}
                            style={{ border: 'none', background: 'none', width: '36px', height: '36px', cursor: 'pointer', color: '#c59d5f', fontWeight: 'bold' }}
                          >
                            -
                          </button>
                          <input
                            type="text"
                            value={quantity}
                            readOnly
                            style={{ border: 'none', width: '40px', height: '36px', textAlign: 'center', color: '#222', fontWeight: 'bold', fontSize: '15px' }}
                          />
                          <button
                            onClick={() => setQuantity(q => q + 1)}
                            style={{ border: 'none', background: 'none', width: '36px', height: '36px', cursor: 'pointer', color: '#c59d5f', fontWeight: 'bold' }}
                          >
                            +
                          </button>
                        </div>
                      </div>

                      <button onClick={handleAddToCart} className="btn btn--primary" style={{ height: '40px', padding: '0 30px', backgroundColor: '#c59d5f', border: '2px solid #c59d5f', color: '#fff', borderRadius: '25px', textTransform: 'uppercase', fontWeight: 'bold', letterSpacing: '1px', fontSize: '11px', cursor: 'pointer' }}>
                        Add To Cart
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              {/* Sidebar Category Counts */}
              <div className="col-md-3">
                <div className="widget widget-tags" style={{ border: '1px solid #f0f0f0', padding: '25px', backgroundColor: '#fff' }}>
                  <div className="widget--title" style={{ borderBottom: '2px solid #c59d5f', paddingBottom: '10px', marginBottom: '20px' }}>
                    <h5 style={{ fontFamily: 'Raleway', fontWeight: 'bold', fontSize: '12px', textTransform: 'uppercase', letterSpacing: '2px', color: '#222', margin: 0 }}>Categories</h5>
                  </div>
                  <div className="widget--content">
                    <ul className="list-unstyled" style={{ margin: 0, padding: 0 }}>
                      {categories.map((cat, i) => (
                        <li key={i} style={{ display: 'flex', justifyContent: 'space-between', padding: '8px 0', borderBottom: '1px solid #f0f0f0', fontSize: '13px', fontFamily: 'Raleway', color: '#a5a5a5' }}>
                          <span style={{ color: '#222', fontWeight: '500' }}>{cat.name}</span>
                          <span>({cat.items_count})</span>
                        </li>
                      ))}
                    </ul>
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
export default ShopSingle;
