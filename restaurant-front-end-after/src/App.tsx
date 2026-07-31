import React from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate, Outlet } from 'react-router-dom';
import { AuthProvider, useAuth } from './context/AuthContext';
import Loader from './components/Loader';

// Layout components
import Header from './components/Header';
import Footer from './components/Footer';

// Dynamic pages
import Login from './pages/Login';
import Register from './pages/Register';
import Home from './pages/Home';
import MenuBoard from './pages/MenuBoard';
import ShopProducts from './pages/ShopProducts';
import ShopSingle from './pages/ShopSingle';
import ShopCart from './pages/ShopCart';
import Profile from './pages/Profile';
import Reservation from './pages/Reservation';
// import OrderSummary from './pages/OrderSummary';
import MyOrders from './pages/MyOrders';

// Helper static pages
import About from './pages/About';
import Chefs from './pages/Chefs';
import ContactUs from './pages/ContactUs';
import FAQ from './pages/FAQ';
import GuestBook from './pages/GuestBook';
import Blog from './pages/Blog';

// CSS Styling
import './App.css';

// Private Route Guard
const PrivateRoute: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const { isAuthenticated, loading } = useAuth();
  
  if (loading) {
    return (
      <div style={{ display: 'flex', height: '100vh', alignItems: 'center', justifyContent: 'center', backgroundColor: '#0e0d0b', color: '#c59d5f' }}>
        <Loader text="Verifying authentication..." showSkeletons={false} />
      </div>
    );
  }

  return isAuthenticated ? <>{children}</> : <Navigate to="/login" replace />;
};

// Layout Component
const AppLayout = () => {
  return (
    <div id="wrapper" className="wrapper clearfix" style={{ minHeight: '100vh', display: 'flex', flexDirection: 'column' }}>
      <Header />
      <div style={{ flex: '1 0 auto' }}>
        <Outlet />
      </div>
      <Footer />
    </div>
  );
};

export const App: React.FC = () => {
  return (
    <AuthProvider>
      <Router>
        <Routes>
          {/* Auth pages without Layout */}
          <Route path="/login" element={<Login />} />
          <Route path="/register" element={<Register />} />

          {/* Main App Routes with Header & Footer */}
          <Route element={<AppLayout />}>
            <Route path="/" element={<Navigate to="/home" replace />} />
            <Route path="/home" element={<Home />} />
            
            {/* Menu and Shop Board (requires auth to view items) */}
            <Route path="/menu" element={
              <PrivateRoute>
                <MenuBoard />
              </PrivateRoute>
            } />
            <Route path="/shop" element={
              <PrivateRoute>
                <ShopProducts />
              </PrivateRoute>
            } />
            
            {/* Parameterized routes (support both menu and shop-single paths) */}
            <Route path="/menu/:id" element={
              <PrivateRoute>
                <ShopSingle />
              </PrivateRoute>
            } />
            <Route path="/shop-single/:id" element={
              <PrivateRoute>
                <ShopSingle />
              </PrivateRoute>
            } />

            {/* Private Checkout / Cart Routes */}
            <Route path="/shopping-cart" element={
              <PrivateRoute>
                <ShopCart />
              </PrivateRoute>
            } />
            <Route path="/profile" element={
              <PrivateRoute>
                <Profile />
              </PrivateRoute>
            } />
            <Route path="/reservation" element={
              <PrivateRoute>
                <Reservation />
              </PrivateRoute>
            } />

            <Route path="/my-orders" element={
              <PrivateRoute>
                <MyOrders />
              </PrivateRoute>
            } />

            {/* Static pages */}
            <Route path="/about" element={<About />} />
            <Route path="/chefs" element={<Chefs />} />
            <Route path="/contact-us" element={<ContactUs />} />
            <Route path="/faq" element={<FAQ />} />
            <Route path="/guest-book" element={<GuestBook />} />
            <Route path="/blog" element={<Blog />} />
          </Route>

          {/* Fallback */}
          <Route path="*" element={<Navigate to="/home" replace />} />
        </Routes>
      </Router>
    </AuthProvider>
  );
};

export default App;
