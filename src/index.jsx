import React from 'react';
import ReactDOM from 'react-dom';
import { Provider } from 'react-redux'
import Intervalo from './components/Intervalo.jsx'
import NavBarTop from './components/global/NavBarTop.jsx'
import configStore from './store/storeConfig';
/*
import Media from './components/Media.jsx';
import Soma from './components/Soma.jsx';
import Sorteio from './components/Sorteio.jsx';
*/
import 'modules/bootstrap/dist/css/bootstrap.min.css'

/*  import 'modules/admin-lte/dist/js/app.min' */

/* import 'modules/font-awesome/css/font-awesome.min.css' */
/* import 'modules/ionicons/dist/css/ionicons.min.css' */
/* import 'modules/admin-lte/bootstrap/css/bootstrap.min.css' */
/* import 'modules/admin-lte/dist/css/AdminLTE.min.css' */
import 'modules/admin-lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css'
import './custom.css'
import Media from './components/Media.jsx';
const store = configStore()

ReactDOM.render(
  <Provider store={store}>
    <NavBarTop title="Ué????">
      <div>Esse é um Content</div>
    </NavBarTop>
    <div className="linha">
      <Intervalo></Intervalo>
      <Media></Media>
    </div>
  </Provider>,
  document.querySelector('#app')
)