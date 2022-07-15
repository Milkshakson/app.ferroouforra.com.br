import React from 'react'
import {
  BrowserRouter,
  Routes,
  Route
} from 'react-router-dom'
import ProtectedRoute from 'main/protectedRoute'
import HomePage from 'pages/HomePage'
import NotFoundPage from 'pages/NotFoundPage';
import LoginPage from 'pages/LoginPage';
import LogoutPage from 'pages/LogoutPage';
import { ToastContainer } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import SessionPage from '../pages/SessionPage';
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
        <Route path="/login/logout" element={<LogoutPage />} />
        <Route path="/login" element={<LoginPage />} />
        <Route path="/session/current" element={<SessionPage />} />
        <Route path="" element={<ProtectedRoute> <HomePage /></ProtectedRoute>} />
        <Route path="/" element={<ProtectedRoute> <HomePage /></ProtectedRoute>} />
        <Route path="*" element={<NotFoundPage />} />
      </Routes>
    </BrowserRouter>
  </>
)