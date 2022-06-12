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
import Card from './components/Card.jsx';
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