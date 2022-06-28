import React from 'react'
import {
  BrowserRouter,
  Routes,
  Route
} from 'react-router-dom'
import ProtectedRoute from 'main/protectedRoute'
import HomeSistag from 'pages/HomeSistag'
import NotFoundPage from 'pages/NotFoundPage';
import LoginPage from 'pages/LoginPage';
import LogoutPage from 'pages/LogoutPage';
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
        <Route path="/session/current" element={<NotFoundPage />} />
        <Route path="/login/logout" element={<LogoutPage />} />
        <Route path="/login" element={<LoginPage />} />
        <Route path="" element={<ProtectedRoute> <HomeSistag /></ProtectedRoute>} />
        <Route path="/" element={<ProtectedRoute> <HomeSistag /></ProtectedRoute>} />
        <Route path="*" element={<NotFoundPage />} />
      </Routes>
    </BrowserRouter>
  </>
)