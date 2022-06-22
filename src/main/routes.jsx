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
// import Dashboard from '../dashboard/dashboard'
// import BillingCycle from '../billingCycle/billingCycle'
export default props => (
    <BrowserRouter>
    <Routes>
      <Route path="/" element={<ProtectedRoute> <App /></ProtectedRoute>} />
      <Route path="" element={<App />} />
     <Route path="/session/current" element={<NotFound />} />
     {/*       <Route path="invoices" element={<Invoices />} /> */}
      <Route path="/login" element={<LoginPage />} />
      <Route path="/login/logout" element={<LoginPage />} />
      <Route path="*" element={<NotFound />} />
    </Routes>
  </BrowserRouter>
)