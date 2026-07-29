import React, { useState, useEffect, useRef } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import api from '../services/api';
import Swal from 'sweetalert2';
import flatpickr from 'flatpickr';

interface Table {
  id: number;
  table_number: string;
  capacity: number;
  zone: string;
  status: string;
  location?: string;
  table_type: string;
}

export const Reservation: React.FC = () => {
  const navigate = useNavigate();
  const dateInputRef = useRef<HTMLInputElement>(null);
  const timeInputRef = useRef<HTMLInputElement>(null);

  const [tables, setTables] = useState<Table[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  // Form State
  const [selectedTable, setSelectedTable] = useState<Table | null>(null);
  const [guests, setGuests] = useState<number>(2);
  const [date, setDate] = useState<string>('');
  const [time, setTime] = useState<string>('');

  useEffect(() => {
    // Initialize Flatpickr for Date
    if (dateInputRef.current) {
      flatpickr(dateInputRef.current, {
        minDate: 'today',
        dateFormat: 'Y-m-d',
        onChange: (_, dateStr) => {
          setDate(dateStr);
        },
      });
    }

    // Initialize Flatpickr for Time
    if (timeInputRef.current) {
      flatpickr(timeInputRef.current, {
        enableTime: true,
        noCalendar: true,
        dateFormat: 'H:i',
        time_24hr: true,
        onChange: (_, timeStr) => {
          setTime(timeStr);
        },
      });
    }

    // Fetch available tables
    const fetchAvailableTables = async () => {
      try {
        setLoading(true);
        const response = await api.get('api/tables/available');
        setTables(response.data.tables || []);
      } catch (err: any) {
        console.error('Error fetching tables:', err);
        setError('Unable to load available tables. Please make sure you are logged in.');
      } finally {
        setLoading(false);
      }
    };

    fetchAvailableTables();
  }, []);

  const handleTableSelect = (table: Table) => {
    if (guests > table.capacity) {
      Swal.fire({
        icon: 'warning',
        title: 'Capacity Warning',
        text: `This table only seats ${table.capacity} people, but you requested ${guests} seats. Are you sure you want to select it?`,
        showCancelButton: true,
        confirmButtonColor: '#c59d5f',
        cancelButtonColor: '#222',
        background: '#0e0d0b',
        color: '#f0ebe0',
      }).then((result) => {
        if (result.isConfirmed) {
          setSelectedTable(table);
        }
      });
    } else {
      setSelectedTable(table);
    }
  };

  const handleReservationSubmit = (e: React.FormEvent) => {
    e.preventDefault();

    if (!date) {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Please select a reservation date.',
        confirmButtonColor: '#c59d5f',
        background: '#0e0d0b',
        color: '#f0ebe0',
      });
      return;
    }

    if (!time) {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Please select a reservation time.',
        confirmButtonColor: '#c59d5f',
        background: '#0e0d0b',
        color: '#f0ebe0',
      });
      return;
    }

    if (!selectedTable) {
      Swal.fire({
        icon: 'error',
        title: 'Table Required',
        text: 'Please select a table to complete your reservation.',
        confirmButtonColor: '#c59d5f',
        background: '#0e0d0b',
        color: '#f0ebe0',
      });
      return;
    }

    // Save details to LocalStorage
    localStorage.setItem('orderType', 'dine-in');
    localStorage.setItem('selectedTableId', selectedTable.id.toString());
    localStorage.setItem('selectedTableNumber', selectedTable.table_number);
    localStorage.setItem('reservedSeats', guests.toString());
    localStorage.setItem('reservationDate', date);
    localStorage.setItem('reservationTime', time);

    // Dispatch cart-updated custom event to refresh header
    window.dispatchEvent(new Event('cart-updated'));

    Swal.fire({
      icon: 'success',
      title: 'Table Selected!',
      text: `Table ${selectedTable.table_number} reserved for ${guests} guests on ${date} at ${time}. Let's browse the menu now!`,
      confirmButtonColor: '#c59d5f',
      background: '#0e0d0b',
      color: '#f0ebe0',
    }).then(() => {
      navigate('/shop');
    });
  };

  return (
    <div className="reservation-wrapper">
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
                  <div className="title--subtitle">Book A Table</div>
                  <div className="title--heading">
                    <h1>Online Reservation</h1>
                  </div>
                </div>
                <ol className="breadcrumb">
                  <li><Link to="/home">Home</Link></li>
                  <li className="active">Reservation</li>
                </ol>
                <div className="divider--shape-1down divider--shape-gray"></div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Booking Form and Available Tables */}
      <section id="reservation" className="reservation-section bg-white" style={{ padding: '80px 0' }}>
        <div className="container">
          <div className="row">
            <div className="col-xs-12 col-sm-12 col-md-10 col-md-offset-1">
              <div className="heading heading-1 text-center mb-50">
                <p className="heading--subtitle" style={{ fontFamily: 'Great Vibes', color: '#c59d5f', fontSize: '24px' }}>Reservation</p>
                <h2 className="heading--title" style={{ fontFamily: 'Kaushan Script', fontSize: '36px', color: '#222' }}>Select Date, Seats & Table</h2>
                <div className="divider--shape-4" style={{ width: '80px', height: '2px', backgroundColor: '#c59d5f', margin: '15px auto' }}></div>
              </div>
            </div>
          </div>

          <div className="row">
            {/* Form Column */}
            <div className="col-xs-12 col-sm-12 col-md-4">
              <div style={{ backgroundColor: '#0e0d0b', padding: '30px', borderRadius: '4px', border: '1px solid rgba(197, 157, 95, 0.3)', color: '#fff' }}>
                <h4 style={{ fontFamily: 'Kaushan Script', color: '#c59d5f', marginBottom: '25px', fontSize: '22px' }}>Reservation Details</h4>
                
                <form onSubmit={handleReservationSubmit}>
                  <div className="form-group mb-20">
                    <label style={{ fontSize: '12px', textTransform: 'uppercase', letterSpacing: '1px', color: 'rgba(255,255,255,0.7)', display: 'block', marginBottom: '8px' }}>Number of Guests</label>
                    <input 
                      type="number" 
                      className="form-control" 
                      min="1" 
                      max="20"
                      value={guests}
                      onChange={(e) => setGuests(parseInt(e.target.value) || 1)}
                      style={{ backgroundColor: '#1a1917', border: '1px solid rgba(255,255,255,0.1)', color: '#fff' }}
                      required
                    />
                  </div>

                  <div className="form-group mb-20">
                    <label style={{ fontSize: '12px', textTransform: 'uppercase', letterSpacing: '1px', color: 'rgba(255,255,255,0.7)', display: 'block', marginBottom: '8px' }}>Select Date</label>
                    <input 
                      type="text" 
                      ref={dateInputRef}
                      className="form-control" 
                      placeholder="Choose Date"
                      style={{ backgroundColor: '#1a1917', border: '1px solid rgba(255,255,255,0.1)', color: '#fff' }}
                      readOnly
                      required
                    />
                  </div>

                  <div className="form-group mb-25">
                    <label style={{ fontSize: '12px', textTransform: 'uppercase', letterSpacing: '1px', color: 'rgba(255,255,255,0.7)', display: 'block', marginBottom: '8px' }}>Select Time</label>
                    <input 
                      type="text" 
                      ref={timeInputRef}
                      className="form-control" 
                      placeholder="Choose Time"
                      style={{ backgroundColor: '#1a1917', border: '1px solid rgba(255,255,255,0.1)', color: '#fff' }}
                      readOnly
                      required
                    />
                  </div>

                  <div className="selected-table-box mb-25" style={{ padding: '15px', border: '1px dashed #c59d5f', borderRadius: '4px', textAlign: 'center', backgroundColor: 'rgba(197, 157, 95, 0.05)' }}>
                    {selectedTable ? (
                      <div>
                        <span style={{ fontSize: '12px', color: '#c59d5f', textTransform: 'uppercase' }}>Selected Table</span>
                        <h3 style={{ margin: '5px 0 0', fontFamily: 'Kaushan Script', color: '#fff', fontSize: '24px' }}>Table {selectedTable.table_number}</h3>
                        <span style={{ fontSize: '11px', color: 'rgba(255,255,255,0.5)' }}>Capacity: {selectedTable.capacity} | {selectedTable.zone}</span>
                      </div>
                    ) : (
                      <span style={{ fontSize: '13px', color: 'rgba(255,255,255,0.5)' }}>Please choose a table from the grid</span>
                    )}
                  </div>

                  <button 
                    type="submit" 
                    className="btn btn--secondary btn--block"
                    style={{ backgroundColor: '#c59d5f', border: 'none', color: '#fff', padding: '14px', textTransform: 'uppercase', letterSpacing: '2px', fontWeight: 'bold', fontSize: '11px', borderRadius: '25px', cursor: 'pointer' }}
                  >
                    Confirm Table & Browse Menu
                  </button>
                </form>
              </div>
            </div>

            {/* Tables Grid Column */}
            <div className="col-xs-12 col-sm-12 col-md-8">
              <div style={{ padding: '0 15px' }}>
                <h4 style={{ fontFamily: 'Raleway', color: '#222', marginBottom: '25px', fontWeight: 'bold', fontSize: '18px', textTransform: 'uppercase', letterSpacing: '2px' }}>
                  Available Tables
                </h4>

                {loading && (
                  <div style={{ textAlign: 'center', padding: '50px 0' }}>
                    <div className="spinner" style={{ display: 'inline-block' }}>
                      <div className="bounce1" style={{ backgroundColor: '#c59d5f' }}></div>
                      <div className="bounce2" style={{ backgroundColor: '#c59d5f' }}></div>
                      <div className="bounce3" style={{ backgroundColor: '#c59d5f' }}></div>
                    </div>
                    <p style={{ color: '#a5a5a5', marginTop: '15px' }}>Finding free tables...</p>
                  </div>
                )}

                {error && (
                  <div className="alert alert-danger text-center">
                    {error}
                  </div>
                )}

                {!loading && !error && tables.length === 0 && (
                  <div className="alert alert-warning text-center" style={{ padding: '30px' }}>
                    No tables are currently available for reservation. Please contact our team directly.
                  </div>
                )}

                {!loading && !error && tables.length > 0 && (
                  <div className="row">
                    {tables.map((table) => {
                      const isSelected = selectedTable?.id === table.id;
                      return (
                        <div key={table.id} className="col-xs-6 col-sm-4 col-md-4 mb-20">
                          <div 
                            onClick={() => handleTableSelect(table)}
                            style={{
                              border: isSelected ? '2px solid #c59d5f' : '1px solid #f0f0f0',
                              backgroundColor: isSelected ? 'rgba(197, 157, 95, 0.05)' : '#f9f9f9',
                              padding: '25px 15px',
                              borderRadius: '4px',
                              textAlign: 'center',
                              cursor: 'pointer',
                              transition: 'all 0.2s ease-in-out',
                            }}
                            className="table-card"
                          >
                            <div style={{ fontSize: '32px', color: isSelected ? '#c59d5f' : '#a5a5a5', marginBottom: '10px' }}>
                              <i className="fa fa-cutlery"></i>
                            </div>
                            <h5 style={{ margin: '0 0 5px 0', fontFamily: 'Raleway', fontWeight: 'bold', color: '#222' }}>
                              Table {table.table_number}
                            </h5>
                            <div style={{ fontSize: '11px', color: '#c59d5f', textTransform: 'uppercase', fontWeight: 'bold', marginBottom: '5px' }}>
                              {table.table_type}
                            </div>
                            <div style={{ fontSize: '12px', color: '#777' }}>
                              Capacity: <strong>{table.capacity} Seats</strong>
                            </div>
                            <div style={{ fontSize: '11px', color: '#a5a5a5', marginTop: '5px' }}>
                              {table.zone} ({table.location || 'Indoor'})
                            </div>
                          </div>
                        </div>
                      );
                    })}
                  </div>
                )}
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
};
export default Reservation;
