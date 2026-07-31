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

  // Popup Modal state
  const [showModal, setShowModal] = useState(false);
  const [customerProfile, setCustomerProfile] = useState<any>(null);
  const [loadingProfile, setLoadingProfile] = useState(false);
  
  // Order notes & submit state inside modal
  const [notes, setNotes] = useState('');
  const [placingOrder, setPlacingOrder] = useState(false);

  // Reservation details for Dine-in
  const [tableId, setTableId] = useState<string | null>(null);
  const [tableNumber, setTableNumber] = useState<string | null>(null);
  const [reservedSeats, setReservedSeats] = useState<string | null>(null);

  const cartKey = `cart_${user?.email || 'guest'}`;
  const orderType = localStorage.getItem('orderType') || 'dine-in';

  const loadCart = () => {
    const items = JSON.parse(localStorage.getItem(cartKey) || '[]');
    setCartItems(items);
  };

  useEffect(() => {
    loadCart();

    if (orderType === 'dine-in') {
      setTableId(localStorage.getItem('selectedTableId'));
      setTableNumber(localStorage.getItem('selectedTableNumber'));
      setReservedSeats(localStorage.getItem('reservedSeats'));
    }
  }, [user]);

  // Recalculate subtotal when items change
  useEffect(() => {
    let sub = 0;
    cartItems.forEach((item) => {
      let itemPriceTotal = item.price * item.quantity;
      item.addons.forEach((addon) => {
        itemPriceTotal += addon.price * addon.qty;
      });
      sub += itemPriceTotal;
    });
    setSubTotal(sub);

    // Auto-close modal if all items are removed while modal is open
    if (cartItems.length === 0 && showModal) {
      setShowModal(false);
    }
  }, [cartItems, showModal]);

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
      text: 'Product removed from your order.',
      timer: 1000,
      showConfirmButton: false,
      background: '#0e0d0b',
      color: '#f0ebe0',
      iconColor: '#c59d5f',
    });
  };

  // Click "Proceed Order" -> verify profile then open popup modal
  const handleProceedOrder = async () => {
    if (cartItems.length === 0) {
      Swal.fire({
        icon: 'warning',
        title: 'Cart is Empty',
        text: 'Please add items to your cart before proceeding!',
        background: '#0e0d0b',
        color: '#f0ebe0',
        confirmButtonColor: '#c59d5f',
      });
      return;
    }

    if (orderType === 'dine-in' && !tableId) {
      Swal.fire({
        icon: 'warning',
        title: 'Table Selection Required',
        text: 'You selected Dine-in mode but have not selected a table yet. Please reserve a table first.',
        showCancelButton: true,
        confirmButtonText: 'Select Table Now',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#c59d5f',
        cancelButtonColor: '#222',
        background: '#0e0d0b',
        color: '#f0ebe0',
      }).then((result) => {
        if (result.isConfirmed) {
          navigate('/reservation');
        }
      });
      return;
    }

    try {
      setLoadingProfile(true);
      const response = await api.get('api/customer/profile');
      const customer = response.data.customer || response.data;
      setCustomerProfile(customer);

      const phone = customer.phone || '';
      const address = customer.address || '';

      if (!phone.trim() || !address.trim()) {
        Swal.fire({
          icon: 'warning',
          title: 'Profile Incomplete',
          text: 'Please update your phone number and address before confirming your order!',
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

      // Profile is valid -> open modal window with order info
      setShowModal(true);
    } catch (err) {
      console.error(err);
      Swal.fire({
        icon: 'error',
        title: 'Authentication Required',
        text: 'Please sign in to proceed with your order.',
        confirmButtonText: 'Sign In Now',
        confirmButtonColor: '#c59d5f',
        background: '#0e0d0b',
        color: '#f0ebe0',
      }).then(() => {
        navigate('/login');
      });
    } finally {
      setLoadingProfile(false);
    }
  };

  // Submit confirmed order from modal to backend database
  const handleConfirmOrder = async () => {
    if (cartItems.length === 0) {
      Swal.fire({
        icon: 'error',
        title: 'Empty Cart',
        text: 'Your cart is empty.',
        confirmButtonColor: '#c59d5f',
        background: '#0e0d0b',
        color: '#f0ebe0',
      });
      return;
    }

    setPlacingOrder(true);

    try {
      // Map order type string to backend ID (Dine-in => 1, Takeaway => 2, Delivery/Online => 3)
      let typeId = 2; // takeaway default
      if (orderType === 'dine-in') typeId = 1;
      if (orderType === 'online' || orderType === 'delivery') typeId = 3;

      // Unique Order Number
      const orderNumber = `ORD-${Date.now()}-${Math.floor(Math.random() * 1000)}`;

      // Prep items for backend validation schema
      const itemsPayload = cartItems.map((cartItem) => ({
        item_id: cartItem.itemId,
        quantity: cartItem.quantity,
        price: cartItem.price,
        portion_id: cartItem.portionId,
        notes: cartItem.note || null,
      }));

      const customerName = customerProfile
        ? `${customerProfile.first_name || ''} ${customerProfile.last_name || ''}`.trim()
        : `${user?.first_name || ''} ${user?.last_name || ''}`.trim() || 'Valued Customer';

      // Assemble Order payload
      const orderPayload: any = {
        order_number: orderNumber,
        order_type_id: typeId,
        customer_name: customerName,
        customer_id: user?.id?.toString() || null,
        notes: notes.trim() || null,
        payment_status: 'pending',
        items: itemsPayload,
      };

      if (orderType === 'dine-in') {
        orderPayload.table_id = parseInt(tableId!);
        orderPayload.reserved_seats = parseInt(reservedSeats || '2');
      }

      // 1. Post Order to Laravel Orders database table
      const orderResponse = await api.post('api/order', orderPayload);
      const createdOrder = orderResponse.data.order;
      const createdItems = createdOrder.order_items || [];

      // 2. Post Add-ons for each order item if present
      for (const cartItem of cartItems) {
        if (cartItem.addons && cartItem.addons.length > 0) {
          const matchingCreatedItem = createdItems.find(
            (ci: any) => ci.item_id === cartItem.itemId && ci.portion_id === cartItem.portionId
          );

          if (matchingCreatedItem) {
            for (const addon of cartItem.addons) {
              await api.post(`api/order-items/${matchingCreatedItem.id}/add-ons`, {
                order_id: createdOrder.id,
                order_item_id: matchingCreatedItem.id,
                add_on_id: addon.id,
                quantity: addon.qty,
                unit_price: addon.price,
                total_price: addon.price * addon.qty,
                total_amount: addon.price * addon.qty,
              });
            }
          }
        }
      }

      setShowModal(false);
      await Swal.fire({
        icon: 'success',
        title: 'Order Successfully Saved!',
        text: `Your order ${orderNumber} has been saved to the database.`,
        background: '#0e0d0b',
        color: '#f0ebe0',
        confirmButtonColor: '#c59d5f',
      });

      // Clear local storage cart & reservation details
      localStorage.removeItem(cartKey);
      localStorage.removeItem('selectedTableId');
      localStorage.removeItem('selectedTableNumber');
      localStorage.removeItem('reservedSeats');
      localStorage.removeItem('reservationDate');
      localStorage.removeItem('reservationTime');

      window.dispatchEvent(new Event('cart-updated'));
      navigate('/my-orders');
    } catch (err: any) {
      console.error('Error saving order:', err);
      const errorMsg = err.response?.data?.error || err.response?.data?.message || 'Error occurred while creating order.';
      Swal.fire({
        icon: 'error',
        title: 'Order Failed',
        text: typeof errorMsg === 'object' ? JSON.stringify(errorMsg) : errorMsg,
        background: '#0e0d0b',
        color: '#f0ebe0',
        confirmButtonColor: '#c59d5f',
      });
    } finally {
      setPlacingOrder(false);
    }
  };

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
                                title="Remove item"
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

            {/* Totals Section & Proceed Order Button */}
            {cartItems.length > 0 && (
              <div className="col-md-6 col-md-offset-3" style={{ marginTop: '30px' }}>
                <div className="cart-total-amount" style={{ border: '1px solid #f0f0f0', padding: '30px', backgroundColor: '#fff', borderRadius: '6px' }}>
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
                  onClick={handleProceedOrder}
                  disabled={loadingProfile}
                  className="checkout-btn btn btn--secondary btn--block"
                  style={{
                    display: 'block',
                    width: '100%',
                    marginTop: '25px',
                    padding: '5px',
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
                    boxShadow: '0 4px 15px rgba(197, 157, 95, 0.3)',
                    transition: 'all 0.3s ease',
                  }}
                >
                  {loadingProfile ? 'Checking Profile...' : 'Proceed Order'}
                </button>
              </div>
            )}
          </div>
        </div>
      </section>

      {/* POPUP MODAL: FULL ORDER INFORMATION & CONFIRMATION */}
      {showModal && (
        <div
          className="order-confirmation-modal-overlay"
          style={{
            position: 'fixed',
            top: 0,
            left: 0,
            right: 0,
            bottom: 0,
            backgroundColor: 'rgba(0, 0, 0, 0.85)',
            backdropFilter: 'blur(5px)',
            zIndex: 9999,
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            padding: '20px',
            overflowY: 'auto',
          }}
        >
          <div
            className="order-confirmation-modal"
            style={{
              backgroundColor: '#141311',
              color: '#f0ebe0',
              borderRadius: '8px',
              border: '1px solid rgba(197, 157, 95, 0.4)',
              width: '100%',
              maxWidth: '850px',
              maxHeight: '90vh',
              overflowY: 'auto',
              boxShadow: '0 10px 40px rgba(0, 0, 0, 0.8)',
              padding: '30px',
              position: 'relative',
              animation: 'fadeIn 0.3s ease-in-out',
            }}
          >
            {/* Close Button */}
            <button
              onClick={() => setShowModal(false)}
              style={{
                position: 'absolute',
                top: '20px',
                right: '20px',
                background: 'none',
                border: 'none',
                color: '#a5a5a5',
                fontSize: '22px',
                cursor: 'pointer',
              }}
              title="Close Popup"
            >
              &times;
            </button>

            {/* Modal Title */}
            <div style={{ borderBottom: '2px solid #c59d5f', paddingBottom: '15px', marginBottom: '25px' }}>
              <h3 style={{ margin: 0, color: '#c59d5f', fontFamily: 'Raleway', textTransform: 'uppercase', letterSpacing: '2px', fontSize: '20px', fontWeight: 'bold' }}>
                Order Information & Confirmation
              </h3>
              <p style={{ margin: '5px 0 0 0', color: '#a5a5a5', fontSize: '13px' }}>
                Review your items and details below. You can modify quantities or details before confirming.
              </p>
            </div>

            {/* Two-Column Layout */}
            <div className="row">
              {/* Left Column: Customer Details & Item List */}
              <div className="col-md-7">
                {/* Customer & Address Details */}
                <div style={{ backgroundColor: '#1d1c1a', padding: '18px', borderRadius: '6px', marginBottom: '20px', border: '1px solid rgba(255,255,255,0.05)' }}>
                  <h5 style={{ color: '#c59d5f', fontSize: '14px', textTransform: 'uppercase', margin: '0 0 12px 0', letterSpacing: '1px' }}>
                    <i className="fa fa-user" style={{ marginRight: '8px' }}></i> Customer Information
                  </h5>
                  <div style={{ fontSize: '13px', lineHeight: '22px', color: '#ccc' }}>
                    <p style={{ margin: 0 }}><strong>Name:</strong> {customerProfile ? `${customerProfile.first_name || ''} ${customerProfile.last_name || ''}` : `${user?.first_name} ${user?.last_name}`}</p>
                    <p style={{ margin: 0 }}><strong>Phone:</strong> {customerProfile?.phone || user?.phone || 'N/A'}</p>
                    {orderType === 'dine-in' ? (
                      <div style={{ marginTop: '8px', padding: '8px', backgroundColor: 'rgba(197, 157, 95, 0.1)', borderRadius: '4px', border: '1px dashed #c59d5f' }}>
                        <span style={{ color: '#c59d5f', fontWeight: 'bold' }}>Dine-in Table:</span> Table {tableNumber || 'Selected'} ({reservedSeats || '2'} Seats)<br />
                        <span style={{ fontSize: '11px', color: '#aaa' }}>Time: {localStorage.getItem('reservationDate')} @ {localStorage.getItem('reservationTime')}</span>
                      </div>
                    ) : (
                      <p style={{ margin: 0 }}><strong>Delivery Address:</strong> {customerProfile?.address || user?.address || 'N/A'}</p>
                    )}
                  </div>
                </div>

                {/* Items List (Editable) */}
                <div style={{ backgroundColor: '#1d1c1a', padding: '18px', borderRadius: '6px', marginBottom: '20px', border: '1px solid rgba(255,255,255,0.05)' }}>
                  <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '15px' }}>
                    <h5 style={{ color: '#c59d5f', fontSize: '14px', textTransform: 'uppercase', margin: 0, letterSpacing: '1px' }}>
                      <i className="fa fa-shopping-basket" style={{ marginRight: '8px' }}></i> Order Items ({cartItems.length})
                    </h5>
                    <small style={{ color: '#888' }}>Editable below</small>
                  </div>

                  <div style={{ maxHeight: '240px', overflowY: 'auto', paddingRight: '5px' }}>
                    {cartItems.map((item) => {
                      let itemTotalPrice = item.price * item.quantity;
                      item.addons.forEach((addon) => {
                        itemTotalPrice += addon.price * addon.qty;
                      });

                      return (
                        <div key={item.id} style={{ display: 'flex', gap: '12px', padding: '10px 0', borderBottom: '1px solid rgba(255,255,255,0.08)', alignItems: 'center' }}>
                          <img
                            src={item.image || '/assets/images/shop/thumb/7.jpg'}
                            alt={item.name}
                            width="48"
                            height="48"
                            style={{ objectFit: 'cover', borderRadius: '4px', flexShrink: 0 }}
                          />
                          <div style={{ flexGrow: 1, minWidth: 0 }}>
                            <div style={{ fontWeight: 'bold', fontSize: '13px', color: '#fff', whiteSpace: 'nowrap', overflow: 'hidden', textOverflow: 'ellipsis' }}>
                              {item.name}
                            </div>
                            <div style={{ fontSize: '11px', color: '#c59d5f' }}>
                              Portion: {item.portion} (Rs. {item.price.toFixed(2)})
                            </div>
                            {item.addons.length > 0 && (
                              <div style={{ fontSize: '10px', color: '#888' }}>
                                + {item.addons.map(a => `${a.name} (x${a.qty})`).join(', ')}
                              </div>
                            )}
                          </div>

                          {/* Live Quantity Controls inside Modal */}
                          <div style={{ display: 'flex', alignItems: 'center', gap: '5px', background: '#141311', padding: '2px 6px', borderRadius: '15px', border: '1px solid #333' }}>
                            <button
                              onClick={() => updateQuantity(item.id, false)}
                              style={{ background: 'none', border: 'none', color: '#c59d5f', fontWeight: 'bold', width: '20px', cursor: 'pointer' }}
                            >
                              -
                            </button>
                            <span style={{ fontSize: '12px', fontWeight: 'bold', minWidth: '16px', textAlign: 'center' }}>{item.quantity}</span>
                            <button
                              onClick={() => updateQuantity(item.id, true)}
                              style={{ background: 'none', border: 'none', color: '#c59d5f', fontWeight: 'bold', width: '20px', cursor: 'pointer' }}
                            >
                              +
                            </button>
                          </div>

                          <div style={{ fontWeight: 'bold', color: '#c59d5f', fontSize: '13px', minWidth: '70px', textAlign: 'right' }}>
                            Rs. {itemTotalPrice.toFixed(2)}
                          </div>

                          <button
                            onClick={() => removeItem(item.id)}
                            style={{ background: 'none', border: 'none', color: '#ff6b6b', cursor: 'pointer', padding: '0 5px' }}
                            title="Remove product"
                          >
                            <i className="fa fa-trash"></i>
                          </button>
                        </div>
                      );
                    })}
                  </div>
                </div>
              </div>

              {/* Right Column: Notes, Summary & Actions */}
              <div className="col-md-5">
                {/* Custom Notes */}
                <div style={{ backgroundColor: '#1d1c1a', padding: '18px', borderRadius: '6px', marginBottom: '20px', border: '1px solid rgba(255,255,255,0.05)' }}>
                  <label style={{ color: '#c59d5f', fontSize: '12px', textTransform: 'uppercase', letterSpacing: '1px', display: 'block', marginBottom: '8px' }}>
                    <i className="fa fa-pencil" style={{ marginRight: '6px' }}></i> Kitchen Instructions
                  </label>
                  <textarea
                    rows={3}
                    value={notes}
                    onChange={(e) => setNotes(e.target.value)}
                    placeholder="e.g. Extra spicy, no onions..."
                    style={{
                      width: '100%',
                      backgroundColor: '#141311',
                      border: '1px solid #333',
                      color: '#fff',
                      borderRadius: '4px',
                      padding: '8px 12px',
                      fontSize: '12px',
                      resize: 'none',
                    }}
                  ></textarea>
                </div>

                {/* Financial Summary */}
                <div style={{ backgroundColor: '#1d1c1a', padding: '18px', borderRadius: '6px', marginBottom: '20px', border: '1px solid rgba(197, 157, 95, 0.3)' }}>
                  <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '13px', color: '#aaa', marginBottom: '8px' }}>
                    <span>Subtotal:</span>
                    <span style={{ color: '#fff', fontWeight: 'bold' }}>Rs. {subTotal.toFixed(2)}</span>
                  </div>
                  <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '13px', color: '#aaa', marginBottom: '12px' }}>
                    <span>Order Type:</span>
                    <span style={{ color: '#c59d5f', fontWeight: 'bold', textTransform: 'uppercase' }}>{orderType.replace('-', ' ')}</span>
                  </div>
                  <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '16px', color: '#fff', fontWeight: 'bold', borderTop: '1px solid #333', paddingTop: '10px' }}>
                    <span>Total Amount:</span>
                    <span style={{ color: '#c59d5f' }}>Rs. {subTotal.toFixed(2)}</span>
                  </div>
                </div>

                {/* Modal Buttons */}
                <div style={{ display: 'flex', gap: '10px' }}>
                  <button
                    onClick={() => setShowModal(false)}
                    style={{
                      flex: 1,
                      padding: '12px',
                      backgroundColor: '#222',
                      color: '#aaa',
                      border: '1px solid #444',
                      borderRadius: '25px',
                      fontWeight: 'bold',
                      fontSize: '11px',
                      textTransform: 'uppercase',
                      letterSpacing: '1px',
                      cursor: 'pointer',
                    }}
                  >
                    Change Order
                  </button>
                  <button
                    onClick={handleConfirmOrder}
                    disabled={placingOrder}
                    style={{
                      flex: 1.5,
                      padding: '12px',
                      backgroundColor: '#c59d5f',
                      color: '#fff',
                      border: 'none',
                      borderRadius: '25px',
                      fontWeight: 'bold',
                      fontSize: '11px',
                      textTransform: 'uppercase',
                      letterSpacing: '1px',
                      cursor: 'pointer',
                      boxShadow: '0 4px 15px rgba(197, 157, 95, 0.4)',
                    }}
                  >
                    {placingOrder ? 'Saving Order...' : 'Confirm Order'}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export default ShopCart;
