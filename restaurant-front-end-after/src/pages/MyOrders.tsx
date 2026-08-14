import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import api from '../services/api';
import Swal from 'sweetalert2';

interface MenuItem {
  base_name: string;
}

interface OrderItem {
  id: number;
  price: string | number;
  quantity: number;
  portion?: { name: string } | null;
  item: MenuItem;
}

interface Order {
  id: number;
  order_number: string;
  total_amount: string | number;
  status: string;
  payment_status: string;
  payment_method: string | null;
  created_at: string;
  order_type: { name: string };
  order_items: OrderItem[];
  table?: { table_number: string } | null;
}

export const MyOrders: React.FC = () => {
  const [orders, setOrders] = useState<Order[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  const fetchOrders = async () => {
    try {
      setLoading(true);
      const response = await api.get('api/orders');
      // Handle Laravel pagination or raw arrays
      const ordersData = response.data.orders?.data || response.data.orders || [];
      setOrders(ordersData);
    } catch (err: any) {
      console.error('Error fetching orders:', err);
      setError('Unable to load your orders. Make sure you are signed in.');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchOrders();
  }, []);

  const handleCancelOrder = async (orderId: number) => {
    const { value: cancelReason } = await Swal.fire({
      title: 'Cancel Order',
      input: 'text',
      inputLabel: 'Reason for cancellation',
      inputPlaceholder: 'Enter cancellation reason here...',
      showCancelButton: true,
      confirmButtonColor: '#ff6b6b',
      cancelButtonColor: '#222',
      background: '#0e0d0b',
      color: '#f0ebe0',
      confirmButtonText: 'Cancel Order',
      inputValidator: (value) => {
        if (!value) {
          return 'You need to specify a reason!';
        }
        return null;
      }
    });

    if (cancelReason) {
      try {
        await api.patch(`api/orders/${orderId}/cancel`, {
          cancel_reason: cancelReason,
        });

        Swal.fire({
          icon: 'success',
          title: 'Order Cancelled',
          text: 'The order has been cancelled successfully.',
          background: '#0e0d0b',
          color: '#f0ebe0',
          confirmButtonColor: '#c59d5f',
        });

        fetchOrders();
      } catch (err: any) {
        console.error(err);
        const msg = err.response?.data?.message || 'Failed to cancel the order. Please try again.';
        Swal.fire({
          icon: 'error',
          title: 'Cancellation Failed',
          text: msg,
          background: '#0e0d0b',
          color: '#f0ebe0',
          confirmButtonColor: '#c59d5f',
        });
      }
    }
  };

  const getStatusBadge = (status: string) => {
    let backgroundColor = '#e2e8f0';
    let color = '#475569';

    switch (status.toLowerCase()) {
      case 'pending':
        backgroundColor = 'rgba(245, 158, 11, 0.15)';
        color = '#d97706';
        break;
      case 'confirmed':
        backgroundColor = 'rgba(59, 130, 246, 0.15)';
        color = '#2563eb';
        break;
      case 'preparing':
        backgroundColor = 'rgba(168, 85, 247, 0.15)';
        color = '#9333ea';
        break;
      case 'ready':
        backgroundColor = 'rgba(34, 197, 94, 0.15)';
        color = '#16a34a';
        break;
      case 'served':
      case 'completed':
        backgroundColor = 'rgba(100, 116, 139, 0.15)';
        color = '#475569';
        break;
      case 'cancelled':
        backgroundColor = 'rgba(239, 68, 68, 0.15)';
        color = '#dc2626';
        break;
    }

    return (
      <span
        style={{
          display: 'inline-block',
          padding: '4px 10px',
          borderRadius: '12px',
          fontSize: '11px',
          textTransform: 'uppercase',
          fontWeight: 'bold',
          letterSpacing: '1px',
          backgroundColor,
          color
        }}
      >
        {status}
      </span>
    );
  };

  return (
    <div className="myorders-wrapper">
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
                  <div className="title--subtitle">History</div>
                  <div className="title--heading">
                    <h1>My Orders</h1>
                  </div>
                </div>
                <br/>
                <ol className="breadcrumb">
                  <li><Link to="/home">Home</Link></li>
                  <li className="active">My Orders</li>
                </ol>
                <div className="divider--shape-1down divider--shape-gray"></div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Orders List Section */}
      <section id="myOrders" className="my-orders bg-white" style={{ padding: '80px 0' }}>
        <div className="container">
          {loading && (
            <div style={{ textAlign: 'center', padding: '50px 0' }}>
              <div className="spinner" style={{ display: 'inline-block' }}>
                <div className="bounce1" style={{ backgroundColor: '#c59d5f' }}></div>
                <div className="bounce2" style={{ backgroundColor: '#c59d5f' }}></div>
                <div className="bounce3" style={{ backgroundColor: '#c59d5f' }}></div>
              </div>
              <p style={{ color: '#a5a5a5', marginTop: '15px' }}>Loading your order history...</p>
            </div>
          )}

          {error && (
            <div className="alert alert-danger text-center" style={{ margin: '30px auto', maxWidth: '600px' }}>
              {error}
            </div>
          )}

          {!loading && !error && (
            <div className="row">
              <div className="col-md-12">
                {orders.length === 0 ? (
                  <div style={{ textAlign: 'center', padding: '60px 20px', border: '1px dashed #e0e0e0', borderRadius: '4px' }}>
                    <div style={{ fontSize: '48px', color: '#c59d5f', marginBottom: '15px' }}>
                      <i className="fa fa-folder-open-o"></i>
                    </div>
                    <h4 style={{ fontFamily: 'Raleway', fontWeight: 'bold', color: '#222' }}>No Orders Found</h4>
                    <p style={{ color: '#a5a5a5', marginBottom: '25px' }}>You haven't placed any orders yet. Visit our shop and order some meals!</p>
                    <Link to="/shop" className="btn btn--secondary" style={{ backgroundColor: '#c59d5f', color: '#fff', borderRadius: '25px', padding: '12px 30px', textTransform: 'uppercase', fontSize: '11px', letterSpacing: '1px', fontWeight: 'bold' }}>
                      Browse Products
                    </Link>
                  </div>
                ) : (
                  <div className="table-responsive">
                    <table className="table table-bordered" style={{ backgroundColor: '#fff', verticalAlign: 'middle' }}>
                      <thead>
                        <tr style={{ backgroundColor: '#f9f9f9', fontFamily: 'Raleway', textTransform: 'uppercase', fontSize: '12px', fontWeight: 'bold', color: '#222' }}>
                          <th style={{ padding: '15px' }}>Order Info</th>
                          <th style={{ padding: '15px' }}>Items</th>
                          <th style={{ padding: '15px' }}>Type & Details</th>
                          <th style={{ padding: '15px' }}>Total Amount</th>
                          <th style={{ padding: '15px' }}>Status</th>
                          <th style={{ padding: '15px' }}>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        {orders.map((order) => {
                          const isPending = order.status.toLowerCase() === 'pending';
                          return (
                            <tr key={order.id} style={{ color: '#555', fontSize: '13px' }}>
                              <td style={{ padding: '15px', verticalAlign: 'middle' }}>
                                <strong style={{ color: '#222', fontSize: '14px' }}>{order.order_number}</strong>
                                <div style={{ fontSize: '11px', color: '#a5a5a5', marginTop: '4px' }}>
                                  {new Date(order.created_at).toLocaleString()}
                                </div>
                              </td>
                              <td style={{ padding: '15px', verticalAlign: 'middle' }}>
                                <ul style={{ listStyle: 'none', margin: 0, padding: 0 }}>
                                  {order.order_items?.map((item) => (
                                    <li key={item.id} style={{ marginBottom: '4px' }}>
                                      <strong>{item.item?.base_name}</strong> {item.portion ? `(${item.portion.name})` : ''} x {item.quantity}
                                    </li>
                                  ))}
                                </ul>
                              </td>
                              <td style={{ padding: '15px', verticalAlign: 'middle' }}>
                                <span style={{ textTransform: 'uppercase', fontWeight: 'bold', color: '#c59d5f' }}>
                                  {order.order_type?.name || 'Order'}
                                </span>
                                {order.table && (
                                  <div style={{ fontSize: '12px', color: '#222', marginTop: '4px' }}>
                                    Table: <strong>{order.table.table_number}</strong>
                                  </div>
                                )}
                              </td>
                              <td style={{ padding: '15px', verticalAlign: 'middle', fontWeight: 'bold', color: '#222' }}>
                                Rs. {parseFloat(order.total_amount as string).toFixed(2)}
                              </td>
                              <td style={{ padding: '15px', verticalAlign: 'middle' }}>
                                {getStatusBadge(order.status)}
                                <div style={{ fontSize: '11px', color: '#a5a5a5', marginTop: '4px', textTransform: 'uppercase' }}>
                                  Payment: <strong style={{ color: order.payment_status === 'paid' ? '#16a34a' : '#dc2626' }}>{order.payment_status}</strong>
                                </div>
                              </td>
                              <td style={{ padding: '15px', verticalAlign: 'middle' }}>
                                {isPending ? (
                                  <button
                                    onClick={() => handleCancelOrder(order.id)}
                                    className="btn btn-danger btn-xs"
                                    style={{
                                      padding: '6px 12px',
                                      fontSize: '11px',
                                      textTransform: 'uppercase',
                                      fontWeight: 'bold',
                                      borderRadius: '4px',
                                      backgroundColor: '#dc2626',
                                      color: '#fff',
                                      border: 'none',
                                      cursor: 'pointer'
                                    }}
                                  >
                                    Cancel
                                  </button>
                                ) : (
                                  <span style={{ fontSize: '11px', color: '#a5a5a5', fontStyle: 'italic' }}>Locked</span>
                                )}
                              </td>
                            </tr>
                          );
                        })}
                      </tbody>
                    </table>
                  </div>
                )}
              </div>
            </div>
          )}
        </div>
      </section>
    </div>
  );
};

export default MyOrders;

