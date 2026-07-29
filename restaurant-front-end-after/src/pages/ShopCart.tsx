import React, { useState, useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import api from '../services/api';
import Swal from 'sweetalert2';

interface CartAddon {
  id: number;
  name: string;
  price: number;
  qty: number;
}

interface CartItem {
  id: string;
  itemId: number;
  name: string;
  price: number;
  quantity: number;
  portion: string;
  portionId: number;
  image: string | null;
  note?: string;
  addons: CartAddon[];
}

export const ShopCart: React.FC = () => {
  const { user } = useAuth();
  const navigate = useNavigate();

  const [cartItems, setCartItems] = useState<CartItem[]>([]);
  const [subTotal, setSubTotal] = useState(0);

  const cartKey = `cart_${user?.email || 'guest'}`;

  const loadCart = () => {
    const items = JSON.parse(localStorage.getItem(cartKey) || '[]');
    setCartItems(items);
  };

  useEffect(() => {
    loadCart();
  }, [user]);

  // Recalculate subtotal when items change
  useEffect(() => {
    let sub = 0;
    cartItems.forEach((item) => {
      // Base portion price * quantity
      let itemPriceTotal = item.price * item.quantity;
      // Addons price * quantity
      item.addons.forEach((addon) => {
        itemPriceTotal += addon.price * addon.qty;
      });
      sub += itemPriceTotal;
    });
    setSubTotal(sub);
  }, [cartItems]);

  const updateQuantity = (id: string, inc: boolean) => {
    const updated = cartItems.map((item) => {
      if (item.id === id) {
        const newQty = inc ? item.quantity + 1 : Math.max(1, item.quantity - 1);
        return { ...item, quantity: newQty };
      }
      return item;
    });
    setCartItems(updated);
    localStorage.setItem(cartKey, JSON.stringify(updated));
    window.dispatchEvent(new Event('cart-updated'));
  };

  const removeItem = (id: string) => {
    const updated = cartItems.filter((item) => item.id !== id);
    setCartItems(updated);
    localStorage.setItem(cartKey, JSON.stringify(updated));
    window.dispatchEvent(new Event('cart-updated'));

    Swal.fire({
      icon: 'success',
      title: 'Item Removed',
      text: 'Product removed from your cart.',
      timer: 1000,
      showConfirmButton: false,
      background: '#0e0d0b',
      color: '#f0ebe0',
      iconColor: '#c59d5f',
    });
  };

  const handleCheckout = async () => {
    if (cartItems.length === 0) {
      Swal.fire({
        icon: 'warning',
        title: 'Cart is Empty',
        text: 'Please add items to your cart before checking out!',
        background: '#0e0d0b',
        color: '#f0ebe0',
        confirmButtonColor: '#c59d5f',
      });
      return;
    }

    try {
      // Call profile API directly to check completeness
      const response = await api.get('api/customer/profile');
      const customer = response.data.customer || response.data;
      
      const phone = customer.phone || '';
      const address = customer.address || '';

      if (!phone.trim() || !address.trim()) {
        Swal.fire({
          icon: 'warning',
          title: 'Profile Incomplete',
          text: 'Please update your delivery address and phone number to proceed with order checkout!',
          showCancelButton: true,
          confirmButtonText: 'Update Profile',
          cancelButtonText: 'Cancel',
          confirmButtonColor: '#c59d5f',
          cancelButtonColor: '#222',
          background: '#0e0d0b',
          color: '#f0ebe0',
        }).then((result) => {
          if (result.isConfirmed) {
            navigate('/profile');
          }
        });
        return;
      }

      navigate('/order-summary');
    } catch (err) {
      console.error(err);
      Swal.fire({
        icon: 'error',
        title: 'Checkout Failed',
        text: 'You must be signed in to check out your order.',
        confirmButtonText: 'Sign In Now',
        confirmButtonColor: '#c59d5f',
        background: '#0e0d0b',
        color: '#f0ebe0',
      }).then(() => {
        navigate('/login');
      });
    }
  };

  const orderType = localStorage.getItem('orderType') || 'dine-in';

  return (
    <div className="shopcart-wrapper">
      {/* Page Title */}
      <section id="page-title" className="page-title bg-overlay bg-parallax bg-overlay-gradient">
        <div className="bg-section">
          <img src="/assets/images/page-title/6.jpg" alt="Background" />
        </div>
        <div className="container">
          <div className="row">
            <div className="col-md-12">
              <div className="title text-center">
                <h1>Your Order</h1>
              </div>
              <ol className="breadcrumb">
                <li><Link to="/home">Home</Link></li>
                <li><Link to="/shop">Shop</Link></li>
                <li className="active">Cart</li>
              </ol>
            </div>
          </div>
        </div>
      </section>

      {/* Cart Content */}
      <section id="shopcart" className="shop shop-cart bg-gray" style={{ padding: '80px 0' }}>
        <div className="container">
          <div className="row">
            <div className="col-md-12">
              <div className="cart-table table-responsive">
                <table className="table table-bordered" style={{ backgroundColor: '#fff' }}>
                  <thead>
                    <tr>
                      <th>Product</th>
                      <th>Price</th>
                      <th>Quantity</th>
                      <th>Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    {cartItems.length === 0 ? (
                      <tr>
                        <td colSpan={4} className="text-center" style={{ padding: '40px 0', color: '#a5a5a5' }}>
                          Your cart is empty. <Link to="/shop" style={{ color: '#c59d5f' }}>Go shopping</Link>
                        </td>
                      </tr>
                    ) : (
                      cartItems.map((item) => {
                        let itemTotalPrice = item.price * item.quantity;
                        item.addons.forEach((addon) => {
                          itemTotalPrice += addon.price * addon.qty;
                        });

                        return (
                          <tr key={item.id} className="cart-product">
                            <td className="cart-info" style={{ display: 'flex', alignItems: 'center', gap: '15px' }}>
                              <button
                                className="remove-item"
                                onClick={() => removeItem(item.id)}
                                style={{ background: 'none', border: 'none', color: '#ff6b6b', cursor: 'pointer', fontSize: '16px' }}
                              >
                                <i className="fa fa-trash"></i>
                              </button>
                              <img
                                src={item.image || '/assets/images/shop/thumb/7.jpg'}
                                alt={item.name}
                                width="60"
                                className="cart-image"
                                style={{ objectFit: 'cover', borderRadius: '4px' }}
                              />
                              <div className="cart-details" style={{ display: 'flex', flexDirection: 'column', textAlign: 'left' }}>
                                <span className="cart-name" style={{ fontWeight: 'bold', color: '#222' }}>{item.name}</span>
                                <small className="cart-portion" style={{ color: '#c59d5f' }}>Portion: {item.portion}</small>
                                
                                {item.addons.length > 0 && (
                                  <div style={{ marginTop: '5px' }}>
                                    {item.addons.map((add, i) => (
                                      <small key={i} style={{ display: 'block', color: '#a5a5a5', fontSize: '11px' }}>
                                        + {add.name} (x{add.qty})
                                      </small>
                                    ))}
                                  </div>
                                )}
                                
                                {item.note && (
                                  <small style={{ color: '#888', fontStyle: 'italic', marginTop: '3px' }}>
                                    Note: "{item.note}"
                                  </small>
                                )}
                              </div>
                            </td>

                            <td className="cart-product-price" style={{ verticalAlign: 'middle', fontWeight: 'bold' }}>
                              Rs. {item.price.toFixed(2)}
                            </td>

                            <td style={{ verticalAlign: 'middle' }}>
                              <div className="qty-box" style={{ display: 'inline-flex', alignItems: 'center', border: '1px solid #e0e0e0', borderRadius: '25px', overflow: 'hidden' }}>
                                <button
                                  className="qty-down"
                                  onClick={() => updateQuantity(item.id, false)}
                                  style={{ border: 'none', background: 'none', width: '28px', height: '28px', cursor: 'pointer', color: '#c59d5f', fontWeight: 'bold' }}
                                >
                                  -
                                </button>
                                <input
                                  className="qty-input"
                                  value={item.quantity}
                                  readOnly
                                  style={{ border: 'none', width: '30px', height: '28px', textAlign: 'center', color: '#222', fontWeight: 'bold' }}
                                />
                                <button
                                  className="qty-up"
                                  onClick={() => updateQuantity(item.id, true)}
                                  style={{ border: 'none', background: 'none', width: '28px', height: '28px', cursor: 'pointer', color: '#c59d5f', fontWeight: 'bold' }}
                                >
                                  +
                                </button>
                              </div>
                            </td>

                            <td style={{ verticalAlign: 'middle', fontWeight: 'bold', color: '#c59d5f' }}>
                              <span className="item-total">
                                Rs. {itemTotalPrice.toFixed(2)}
                              </span>
                            </td>
                          </tr>
                        );
                      })
                    )}
                  </tbody>
                </table>
              </div>
            </div>

            {/* Totals Section */}
            {cartItems.length > 0 && (
              <div className="col-md-6 col-md-offset-3" style={{ marginTop: '30px' }}>
                <div className="cart-total-amount" style={{ border: '1px solid #f0f0f0', padding: '30px', backgroundColor: '#fff' }}>
                  <h6 style={{ fontFamily: 'Raleway', fontSize: '13px', textTransform: 'uppercase', color: '#222', fontWeight: 'bold', borderBottom: '2px solid #c59d5f', paddingBottom: '10px', marginBottom: '20px' }}>
                    Cart Totals
                  </h6>
                  <ul className="list-unstyled" style={{ margin: 0, padding: 0 }}>
                    <li style={{ display: 'flex', justifyContent: 'space-between', padding: '10px 0', borderBottom: '1px solid #f0f0f0', fontSize: '14px', color: '#a5a5a5' }}>
                      Cart Subtotal:
                      <span id="cart-subtotal" style={{ color: '#222', fontWeight: 'bold' }}>Rs. {subTotal.toFixed(2)}</span>
                    </li>
                    <li style={{ display: 'flex', justifyContent: 'space-between', padding: '10px 0', borderBottom: '1px solid #f0f0f0', fontSize: '14px', color: '#a5a5a5' }}>
                      Order Type:
                      <span style={{ color: '#c59d5f', fontWeight: 'bold', textTransform: 'uppercase' }}>{orderType.replace('-', ' ')}</span>
                    </li>
                    <li style={{ display: 'flex', justifyContent: 'space-between', padding: '15px 0 0', fontSize: '16px', color: '#222', fontWeight: 'bold' }}>
                      Order Total:
                      <span id="cart-total" style={{ color: '#c59d5f', fontSize: '18px' }}>Rs. {subTotal.toFixed(2)}</span>
                    </li>
                  </ul>
                </div>
                
                <button
                  onClick={handleCheckout}
                  className="checkout-btn btn btn--secondary btn--block"
                  style={{
                    display: 'block',
                    width: '100%',
                    marginTop: '25px',
                    padding: '16px',
                    fontFamily: 'Raleway',
                    fontWeight: 'bold',
                    fontSize: '12px',
                    textTransform: 'uppercase',
                    letterSpacing: '2px',
                    backgroundColor: '#c59d5f',
                    border: 'none',
                    color: '#fff',
                    cursor: 'pointer',
                    textAlign: 'center',
                    borderRadius: '25px',
                  }}
                >
                  Check Out Order
                </button>
              </div>
            )}
          </div>
        </div>
      </section>
    </div>
  );
};
export default ShopCart;
