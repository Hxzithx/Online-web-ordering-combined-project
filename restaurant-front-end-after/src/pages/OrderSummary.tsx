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

export const OrderSummary: React.FC = () => {
  const { user } = useAuth();
  const navigate = useNavigate();

  const [cartItems, setCartItems] = useState<CartItem[]>([]);
  const [subTotal, setSubTotal] = useState(0);
  const [orderType, setOrderType] = useState('dine-in');
  
  // Profile Info
  const [customerProfile, setCustomerProfile] = useState<any>(null);
  const [loadingProfile, setLoadingProfile] = useState(true);

  // Reservation details
  const [tableId, setTableId] = useState<string | null>(null);
  const [tableNumber, setTableNumber] = useState<string | null>(null);
  const [reservedSeats, setReservedSeats] = useState<string | null>(null);

  // Form selections
  const [paymentMethod, setPaymentMethod] = useState<'cash' | 'card' | 'mobile' | 'bank_transfer'>('cash');
  const [notes, setNotes] = useState('');
  const [placingOrder, setPlacingOrder] = useState(false);

  const cartKey = `cart_${user?.email || 'guest'}`;

  useEffect(() => {
    // Load Cart
    const items = JSON.parse(localStorage.getItem(cartKey) || '[]');
    setCartItems(items);

    // Load Order Type and Reservation Info
    const type = localStorage.getItem('orderType') || 'dine-in';
    setOrderType(type);

    if (type === 'dine-in') {
      setTableId(localStorage.getItem('selectedTableId'));
      setTableNumber(localStorage.getItem('selectedTableNumber'));
      setReservedSeats(localStorage.getItem('reservedSeats'));
    }

    // Fetch Profile details
    const fetchProfile = async () => {
      try {
        setLoadingProfile(true);
        const response = await api.get('api/customer/profile');
        setCustomerProfile(response.data.customer || response.data);
      } catch (err) {
        console.error('Error fetching profile for checkout:', err);
      } finally {
        setLoadingProfile(false);
      }
    };

    if (user) {
      fetchProfile();
    }
  }, [user]);

  // Recalculate Subtotal
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
  }, [cartItems]);

  const handlePlaceOrder = async () => {
    if (cartItems.length === 0) {
      Swal.fire({
        icon: 'error',
        title: 'Empty Cart',
        text: 'Your cart is empty. Please add some delicious meals first.',
        confirmButtonColor: '#c59d5f',
        background: '#0e0d0b',
        color: '#f0ebe0',
      });
      return;
    }

    if (orderType === 'dine-in' && !tableId) {
      Swal.fire({
        icon: 'error',
        title: 'Table Selection Missing',
        text: 'You selected Dine-in but did not pick a table. Please book a table first.',
        confirmButtonColor: '#c59d5f',
        background: '#0e0d0b',
        color: '#f0ebe0',
      }).then(() => {
        navigate('/reservation');
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

      // Prep items
      const itemsPayload = cartItems.map((cartItem) => ({
        item_id: cartItem.itemId,
        quantity: cartItem.quantity,
        price: cartItem.price, // base portion price
        portion_id: cartItem.portionId,
        notes: cartItem.note || null,
      }));

      // Assemble Order payload
      const orderPayload: any = {
        order_number: orderNumber,
        order_type_id: typeId,
        customer_name: customerProfile ? `${customerProfile.first_name} ${customerProfile.last_name}` : `${user?.first_name || ''} ${user?.last_name || ''}`.trim() || 'Guest Customer',
        customer_id: user?.id?.toString() || null,
        notes: notes.trim() || null,
        payment_method: paymentMethod,
        payment_status: 'pending',
        items: itemsPayload,
      };

      if (orderType === 'dine-in') {
        orderPayload.table_id = parseInt(tableId!);
        orderPayload.reserved_seats = parseInt(reservedSeats || '2');
      }

      // 1. Post Order
      const orderResponse = await api.post('api/order', orderPayload);
      const createdOrder = orderResponse.data.order;
      const createdItems = createdOrder.order_items || [];

      // 2. Post Add-ons for each order item
      for (const cartItem of cartItems) {
        if (cartItem.addons && cartItem.addons.length > 0) {
          // Find the matching created order item in the response
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

      // 3. Card Payment Flow Simulation
      if (paymentMethod === 'card') {
        setPlacingOrder(false);
        const { value: cardConfirmed } = await Swal.fire({
          title: 'Credit Card Payment',
          html: `
            <div style="text-align: left; margin-top: 10px;">
              <label style="color: #a5a5a5; font-size: 12px; text-transform: uppercase;">Card Number</label>
              <input type="text" id="cardNumber" class="swal2-input" placeholder="4111 2222 3333 4444" style="margin: 5px 0 15px 0; width: 90%; background: #1a1917; color: #fff; border: 1px solid rgba(255,255,255,0.1)">
              
              <div style="display: flex; gap: 15px;">
                <div style="flex: 1;">
                  <label style="color: #a5a5a5; font-size: 12px; text-transform: uppercase;">Expiry Date</label>
                  <input type="text" id="cardExpiry" class="swal2-input" placeholder="MM/YY" style="margin: 5px 0; width: 85%; background: #1a1917; color: #fff; border: 1px solid rgba(255,255,255,0.1)">
                </div>
                <div style="flex: 1;">
                  <label style="color: #a5a5a5; font-size: 12px; text-transform: uppercase;">CVV</label>
                  <input type="password" id="cardCvv" class="swal2-input" placeholder="123" style="margin: 5px 0; width: 85%; background: #1a1917; color: #fff; border: 1px solid rgba(255,255,255,0.1)">
                </div>
              </div>
            </div>
          `,
          background: '#0e0d0b',
          color: '#f0ebe0',
          confirmButtonColor: '#c59d5f',
          confirmButtonText: 'Pay Rs. ' + subTotal.toFixed(2),
          showCancelButton: true,
          cancelButtonColor: '#222',
          preConfirm: () => {
            const num = (document.getElementById('cardNumber') as HTMLInputElement).value;
            const exp = (document.getElementById('cardExpiry') as HTMLInputElement).value;
            const cvv = (document.getElementById('cardCvv') as HTMLInputElement).value;
            if (!num || !exp || !cvv) {
              Swal.showValidationMessage('Please fill in all credit card details.');
            }
            return true;
          }
        });

        if (cardConfirmed) {
          try {
            // Call payment confirmation endpoint
            await api.patch(`api/orders/${createdOrder.id}/confirm-payment`);
            Swal.fire({
              icon: 'success',
              title: 'Payment Successful!',
              text: 'Your card transaction was approved. Order is now confirmed.',
              background: '#0e0d0b',
              color: '#f0ebe0',
              confirmButtonColor: '#c59d5f',
            });
          } catch (payErr) {
            console.error('Error confirming payment:', payErr);
            Swal.fire({
              icon: 'warning',
              title: 'Order Placed, Payment Unpaid',
              text: 'Order was placed but card authorization failed. Please pay at counter.',
              background: '#0e0d0b',
              color: '#f0ebe0',
              confirmButtonColor: '#c59d5f',
            });
          }
        } else {
          Swal.fire({
            icon: 'info',
            title: 'Payment Skipped',
            text: 'Your order was submitted, but remains unpaid. You can settle the payment at the counter.',
            background: '#0e0d0b',
            color: '#f0ebe0',
            confirmButtonColor: '#c59d5f',
          });
        }
      } else {
        Swal.fire({
          icon: 'success',
          title: 'Order Placed!',
          text: `Successfully created order ${orderNumber}.`,
          background: '#0e0d0b',
          color: '#f0ebe0',
          confirmButtonColor: '#c59d5f',
        });
      }

      // Clear local storage and cart
      localStorage.removeItem(cartKey);
      localStorage.removeItem('selectedTableId');
      localStorage.removeItem('selectedTableNumber');
      localStorage.removeItem('reservedSeats');
      localStorage.removeItem('reservationDate');
      localStorage.removeItem('reservationTime');

      // Dispatch event to refresh Header cart count
      window.dispatchEvent(new Event('cart-updated'));

      navigate('/my-orders');
    } catch (err: any) {
      console.error(err);
      const errors = err.response?.data?.error || err.response?.data?.message || 'Error occurred while submitting order.';
      Swal.fire({
        icon: 'error',
        title: 'Order Failed',
        text: typeof errors === 'object' ? JSON.stringify(errors) : errors,
        background: '#0e0d0b',
        color: '#f0ebe0',
        confirmButtonColor: '#c59d5f',
      });
    } finally {
      setPlacingOrder(false);
    }
  };

  return (
    <div className="ordersummary-wrapper">
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
                  <div className="title--subtitle">Summary</div>
                  <div className="title--heading">
                    <h1>Order Checkout</h1>
                  </div>
                </div>
                <ol className="breadcrumb">
                  <li><Link to="/home">Home</Link></li>
                  <li><Link to="/shopping-cart">Cart</Link></li>
                  <li className="active">Summary</li>
                </ol>
                <div className="divider--shape-1down divider--shape-gray"></div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Summary Content */}
      <section id="summary" className="checkout-summary bg-gray" style={{ padding: '80px 0' }}>
        <div className="container">
          <div className="row">
            {/* Left Column: Details */}
            <div className="col-xs-12 col-sm-12 col-md-7 mb-40">
              <div style={{ backgroundColor: '#fff', border: '1px solid #f0f0f0', padding: '30px', borderRadius: '4px' }}>
                <h4 style={{ fontFamily: 'Raleway', fontWeight: 'bold', textTransform: 'uppercase', color: '#222', fontSize: '16px', borderBottom: '2px solid #c59d5f', paddingBottom: '10px', marginBottom: '25px', letterSpacing: '2px' }}>
                  Delivery & Guest Info
                </h4>

                {loadingProfile ? (
                  <p>Loading profile information...</p>
                ) : (
                  <div className="row" style={{ color: '#555', fontSize: '14px', lineHeight: '24px' }}>
                    <div className="col-xs-12 col-sm-6 mb-20">
                      <strong>Customer Name:</strong>
                      <p>{customerProfile ? `${customerProfile.first_name} ${customerProfile.last_name}` : `${user?.first_name} ${user?.last_name}`}</p>
                    </div>

                    <div className="col-xs-12 col-sm-6 mb-20">
                      <strong>Phone Number:</strong>
                      <p>{customerProfile?.phone || user?.phone || 'Not provided (Update in Profile)'}</p>
                    </div>

                    {orderType === 'dine-in' ? (
                      <div className="col-xs-12 mb-20" style={{ backgroundColor: 'rgba(197, 157, 95, 0.05)', padding: '15px', borderRadius: '4px', border: '1px dashed #c59d5f' }}>
                        <h5 style={{ fontFamily: 'Kaushan Script', color: '#c59d5f', margin: '0 0 10px 0', fontSize: '20px' }}>Table Reservation details</h5>
                        <p style={{ margin: 0 }}>
                          Selected Table: <strong>Table {tableNumber || 'N/A'}</strong><br />
                          Guests Capacity: <strong>{reservedSeats || '2'} Seats</strong><br />
                          Time: <strong>{localStorage.getItem('reservationDate')} @ {localStorage.getItem('reservationTime')}</strong>
                        </p>
                      </div>
                    ) : (
                      <div className="col-xs-12 mb-20">
                        <strong>Delivery Address:</strong>
                        <p>{customerProfile?.address || user?.address || 'Not provided (Update in Profile)'}</p>
                      </div>
                    )}
                  </div>
                )}

                <div style={{ marginTop: '20px' }}>
                  <h4 style={{ fontFamily: 'Raleway', fontWeight: 'bold', textTransform: 'uppercase', color: '#222', fontSize: '16px', borderBottom: '2px solid #c59d5f', paddingBottom: '10px', marginBottom: '25px', letterSpacing: '2px' }}>
                    Payment Method
                  </h4>
                  <div style={{ display: 'flex', flexWrap: 'wrap', gap: '15px' }}>
                    {[
                      { id: 'cash', label: 'Cash on Counter / Delivery', icon: 'fa-money' },
                      { id: 'card', label: 'Credit Card (Online Simulation)', icon: 'fa-credit-card' },
                      { id: 'mobile', label: 'Mobile Transfer (E-Z / M-Pesa)', icon: 'fa-mobile' },
                      { id: 'bank_transfer', label: 'Bank Direct Transfer', icon: 'fa-bank' }
                    ].map((method) => (
                      <label 
                        key={method.id} 
                        style={{
                          flex: '1 1 calc(50% - 10px)',
                          display: 'flex',
                          alignItems: 'center',
                          gap: '12px',
                          border: paymentMethod === method.id ? '2px solid #c59d5f' : '1px solid #e0e0e0',
                          backgroundColor: paymentMethod === method.id ? 'rgba(197, 157, 95, 0.05)' : '#fafafa',
                          padding: '15px',
                          borderRadius: '4px',
                          cursor: 'pointer',
                          margin: 0,
                          fontSize: '13px',
                          color: '#222',
                          fontWeight: paymentMethod === method.id ? 'bold' : 'normal'
                        }}
                      >
                        <input 
                          type="radio" 
                          name="payment" 
                          value={method.id}
                          checked={paymentMethod === method.id}
                          onChange={() => setPaymentMethod(method.id as any)}
                          style={{ accentColor: '#c59d5f' }}
                        />
                        <i className={`fa ${method.icon}`} style={{ color: '#c59d5f', fontSize: '18px' }}></i>
                        {method.label}
                      </label>
                    ))}
                  </div>
                </div>

                <div className="form-group mb-0" style={{ marginTop: '30px' }}>
                  <label style={{ fontSize: '12px', textTransform: 'uppercase', letterSpacing: '1px', color: '#777', fontWeight: 'bold' }}>Order Custom Notes</label>
                  <textarea 
                    className="form-control" 
                    placeholder="Any special instructions for the chef..."
                    rows={4}
                    value={notes}
                    onChange={(e) => setNotes(e.target.value)}
                    style={{ border: '1px solid #f0f0f0', borderRadius: '4px', resize: 'none', padding: '15px' }}
                  ></textarea>
                </div>
              </div>
            </div>

            {/* Right Column: Cart items list */}
            <div className="col-xs-12 col-sm-12 col-md-5">
              <div style={{ backgroundColor: '#fff', border: '1px solid #f0f0f0', padding: '30px', borderRadius: '4px' }}>
                <h4 style={{ fontFamily: 'Raleway', fontWeight: 'bold', textTransform: 'uppercase', color: '#222', fontSize: '16px', borderBottom: '2px solid #c59d5f', paddingBottom: '10px', marginBottom: '25px', letterSpacing: '2px' }}>
                  Your Order Items
                </h4>

                <div style={{ maxHeight: '350px', overflowY: 'auto', paddingRight: '5px', marginBottom: '20px' }}>
                  {cartItems.map((item, index) => {
                    let itemTotalPrice = item.price * item.quantity;
                    item.addons.forEach((addon) => {
                      itemTotalPrice += addon.price * addon.qty;
                    });

                    return (
                      <div key={index} style={{ display: 'flex', gap: '15px', padding: '15px 0', borderBottom: '1px solid #f0f0f0' }}>
                        <div style={{ width: '60px', height: '60px', borderRadius: '4px', overflow: 'hidden', flexShrink: 0 }}>
                          <img src={item.image || '/assets/images/shop/grid/15.jpg'} alt={item.name} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                        </div>
                        <div style={{ flexGrow: 1 }}>
                          <h5 style={{ margin: '0 0 5px 0', fontSize: '14px', fontWeight: 'bold', color: '#222' }}>{item.name}</h5>
                          <span style={{ fontSize: '12px', color: '#a5a5a5' }}>
                            Portion: {item.portion} x {item.quantity}
                          </span>
                          
                          {item.addons.length > 0 && (
                            <div style={{ marginTop: '5px', paddingLeft: '8px', borderLeft: '2px solid #c59d5f' }}>
                              {item.addons.map((add, ai) => (
                                <div key={ai} style={{ fontSize: '11px', color: '#888' }}>
                                  + {add.name} (x{add.qty}) - Rs. {(add.price * add.qty).toFixed(2)}
                                </div>
                              ))}
                            </div>
                          )}
                          
                          {item.note && (
                            <div style={{ fontSize: '11px', color: '#ff6b6b', fontStyle: 'italic', marginTop: '3px' }}>
                              Note: {item.note}
                            </div>
                          )}
                        </div>
                        <div style={{ fontWeight: 'bold', color: '#c59d5f', fontSize: '14px', alignSelf: 'center' }}>
                          Rs. {itemTotalPrice.toFixed(2)}
                        </div>
                      </div>
                    );
                  })}
                </div>

                <div style={{ borderTop: '2px solid #f0f0f0', paddingTop: '20px' }}>
                  <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '14px', color: '#a5a5a5', marginBottom: '10px' }}>
                    <span>Order Type:</span>
                    <span style={{ fontWeight: 'bold', color: '#222', textTransform: 'uppercase' }}>{orderType.replace('-', ' ')}</span>
                  </div>
                  <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: '18px', fontWeight: 'bold', color: '#222' }}>
                    <span>Total Amount:</span>
                    <span style={{ color: '#c59d5f' }}>Rs. {subTotal.toFixed(2)}</span>
                  </div>
                </div>

                <button 
                  onClick={handlePlaceOrder}
                  disabled={placingOrder}
                  className="btn btn--secondary btn--block"
                  style={{
                    display: 'block',
                    width: '100%',
                    marginTop: '30px',
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
                  {placingOrder ? 'Placing Order...' : 'Place Order Now'}
                </button>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
};
export default OrderSummary;
