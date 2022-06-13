import React from 'react';
import ReactDOM from 'react-dom';
import { Provider } from 'react-redux'

import Routes from './main/routes'
import configStore from './store/storeConfig';
import App from './main/app'
const store = configStore()

ReactDOM.render(
  <Provider store={store}>
    <Routes />
  </Provider>,
  document.querySelector('#app')
)