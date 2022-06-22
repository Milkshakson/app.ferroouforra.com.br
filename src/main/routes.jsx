import React from 'react'
import {
  BrowserRouter,
  Routes,
  Route
} from 'react-router-dom'
import ProtectedRoute from '../main/protectedRoute'
import App from './app'
import NotFound from '../pages/NotFound';
import LoginPage from '../pages/LoginPage';
import LogoutPage from '../pages/LogoutPage';
import { ToastContainer } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
// import Dashboard from '../dashboard/dashboard'
// import BillingCycle from '../billingCycle/billingCycle'
export default props => (
  <>
    <ToastContainer
      position="top-center"
      autoClose={150000}
      hideProgressBar={false}
      newestOnTop={false}
      closeOnClick={false}
      rtl={false}
      pauseOnFocusLoss
      draggable
      pauseOnHover
      theme= "dark"
    />
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<ProtectedRoute> <App /></ProtectedRoute>} />
        <Route path="" element={<ProtectedRoute> <App /></ProtectedRoute>} />
        <Route path="/session/current" element={<NotFound />} />
        {/*       <Route path="invoices" element={<Invoices />} /> */}
        <Route path="/login/logout" element={<LogoutPage />} />
        <Route path="/login" element={<LoginPage />} />
        <Route path="*" element={<NotFound />} />
      </Routes>
    </BrowserRouter>
  </>
)