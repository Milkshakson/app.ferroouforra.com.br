import React from 'react'
import {
    BrowserRouter, 
    Routes,
    Route
} from 'react-router-dom'

import App from './app'
import NotFound from '../pages/NotFound';
// import Dashboard from '../dashboard/dashboard'
// import BillingCycle from '../billingCycle/billingCycle'
export default props => (
    <BrowserRouter>
        <Routes>
            <Route path='/' component={App} />
            <Route path='/react' component={NotFound} />
            <Route path='react/' component={NotFound} />
            <Route path='react' component={NotFound} />
            {/* <Redirect from='*' to='/' /> */}
            <Route path="*" element={<NotFound />} />
        </Routes>
    </BrowserRouter>
)